<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Session\Handlers;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Exceptions\SessionException;
use MX\CI;
use ReturnTypeWillChange;

/**
 * Base database session handler
 *
 * Do not use this class. Use database specific handler class.
 */
class DatabaseHandler extends BaseHandler
{
    /**
     * The database group to use for storage.
     *
     * @var string
     */
    protected $DBGroup;

    /**
     * The name of the table to store session info.
     *
     * @var string
     */
    protected $table;

    /**
     * The DB Connection instance.
     *
     */
    protected $db;

    /**
     * The database type
     *
     * @var string
     */
    protected $platform;

    /**
     * Row exists flag
     *
     * @var bool
     */
    protected $rowExists = false;

    /**
     * ID prefix for multiple session cookies
     */
    protected string $idPrefix;

    /**
     * @throws SessionException
     */
    public function __construct(BaseConfig $config, string $ipAddress)
    {
        parent::__construct($config, $ipAddress);

        $CI =& get_instance();
        isset($CI->db) OR $CI->load->database();
        $this->db = $CI->db;

        // Add sessionCookieName for multiple session cookies.
        $this->idPrefix = $config->cookieName . ':';

        $this->table = $this->savePath;
        if (empty($this->table)) {
            throw SessionException::forMissingDatabaseTable();
        }

        $this->platform = $this->db->getPlatform();
    }

    /**
     * Re-initialize existing session, or creates a new one.
     *
     * @param string $path The path where to store/retrieve the session
     * @param string $name The session name
     */
    public function open($path, $name): bool
    {
        if (empty($this->db->conn_id) && ! $this->db->db_connect()) {
            $this->db->initialize();
        }

        return true;
    }

    /**
     * Reads the session data from the session storage, and returns the results.
     *
     * @param string $id The session ID
     *
     * @return false|string Returns an encoded string of the read data.
     *                      If nothing was read, it must return false.
     */
    #[ReturnTypeWillChange]
    public function read($id)
    {
        if ($this->lockSession($id) === false) {
            $this->fingerprint = md5('');

            return '';
        }

        if (! isset($this->sessionID)) {
            $this->sessionID = $id;
        }

        // Prevent previous QB calls from messing with our queries
        $this->db->reset_query();

        $this->db
            ->select('data')
            ->from($this->table)
            ->where('id', $this->idPrefix . $id);

        if ($this->matchIP) {
            $this->db->where('ip_address', $this->ipAddress);
        }

        if ( ! ($result = $this->db->get()) OR ($result = $result->row()) === NULL) {
            // PHP7 will reuse the same SessionHandler object after
            // ID regeneration, so we need to explicitly set this to
            // FALSE instead of relying on the default ...
            $this->rowExists   = false;
            $this->fingerprint = md5('');

            return '';
        }

        $result = is_bool($result) ? '' : $this->decodeData($result->data);

        $this->fingerprint = md5($result);
        $this->rowExists   = true;

        return $result;
    }

    /**
     * Decodes column data
     *
     * @param string $data
     *
     * @return false|string
     */
    protected function decodeData($data)
    {
        return $data;
    }

    /**
     * Writes the session data to the session storage.
     *
     * @param string $id   The session ID
     * @param string $data The encoded session data
     */
    public function write($id, $data): bool
    {
        // Prevent previous QB calls from messing with our queries
        $this->db->reset_query();

        if ($this->lock === false) {
            return $this->fail();
        }

        if ($this->sessionID !== $id) {
            $this->rowExists = false;
            $this->sessionID = $id;
        }

        if ($this->rowExists === false) {
            $insertData = [
                'id'         => $this->idPrefix . $id,
                'ip_address' => $this->ipAddress,
                'user_agent' => substr(CI::$APP->input->user_agent() ?? 'unknown', 0, 120),
                'timestamp'  => time(),
                'data'       => $this->prepareData($data),
            ];


            if (! $this->db->insert($this->table, $insertData)) {
                return $this->fail();
            }

            $this->fingerprint = md5($data);
            $this->rowExists   = true;

            return true;
        }

        $this->db->where('id', $this->idPrefix . $id);

        if ($this->matchIP) {
            $this->db->where('ip_address', $this->ipAddress);
        }

        $updateData = ['timestamp' => time()];

        if ($this->fingerprint !== md5($data)) {
            $updateData['data'] = $this->prepareData($data);
        }

        if (! $this->db->update($this->table, $updateData)) {
            return $this->fail();
        }

        $this->fingerprint = md5($data);

        return true;
    }

    /**
     * Prepare data to insert/update
     */
    protected function prepareData(string $data): string
    {
        return $data;
    }

    /**
     * Closes the current session.
     */
    public function close(): bool
    {
        return ($this->lock && ! $this->releaseLock()) ? $this->fail() : true;
    }

    /**
     * Destroys a session
     *
     * @param string $id The session ID being destroyed
     */
    public function destroy($id): bool
    {
        if ($this->lock) {
            // Prevent previous QB calls from messing with our queries
            $this->db->reset_query();

            $this->db->where('id', $this->idPrefix . $id);

            if ($this->matchIP) {
                $this->db->where('ip_address', $this->ipAddress);
            }

            if (! $this->db->delete($this->table)) {
                return $this->fail();
            }
        }

        if ($this->close()) {
            $this->destroyCookie();

            return true;
        }

        return $this->fail();
    }

    /**
     * Cleans up expired sessions.
     *
     * @param int $max_lifetime Sessions that have not updated
     *                          for the last max_lifetime seconds will be removed.
     *
     * @return false|int Returns the number of deleted sessions on success, or false on failure.
     */
    #[ReturnTypeWillChange]
    public function gc($max_lifetime)
    {
        // Prevent previous QB calls from messing with our queries
        $this->db->reset_query();

        return ($this->db->delete($this->table, 'timestamp < '. (time() - $max_lifetime)))
            ? 1
            : $this->fail();
    }

    /**
     * Releases the lock, if any.
     */
    protected function releaseLock(): bool
    {
        if (! $this->lock) {
            return true;
        }

        // Unsupported DB? Let the parent handle the simple version.
        return parent::releaseLock();
    }
}