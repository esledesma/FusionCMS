<?php

use CodeIgniter\Database\BaseConnection;

class Data_model extends CI_Model
{
    public $realm;
    private BaseConnection $connection;
    private bool|string $emuStr = false;

    public function GetStatement($key): false|string
    {
        $statements = [];

        if (!$this->emuStr) {
            return false;
        }

        switch ($this->emuStr) {
            default:
            {
                $statements = [
                    'TopHKPlayers' => "SELECT `guid`,
                                              `name`,
                                              `level`,
                                              `race`,
                                              `class`,
                                              `gender`,
                                              `totalKills` AS kills
                                        FROM `characters`
                                        WHERE `totalKills` > 0
                                        ORDER BY `totalKills`
                                        DESC LIMIT ?;",

                    'TopArenaTeams' => "SELECT `rated_pvp_info`.`guid`,
                                               `rated_pvp_info`.`season`,
                                               `rated_pvp_info`.`rank`,
                                               `rated_pvp_info`.`rating`,
                                               `rated_pvp_info`.`season_games`,
                                               `rated_pvp_info`.`season_wins`,
                                               `rated_pvp_info`.`slot`,
                                               `characters`.`name` AS character_name,
                                               `characters`.`race` AS character_race,
                                               `characters`.`gender` AS character_gender,
                                               `characters`.`class` AS character_class
                                        FROM `rated_pvp_info`
                                        RIGHT JOIN `characters` ON `characters`.`guid` = `rated_pvp_info`.`guid`
                                        WHERE `rated_pvp_info`.`slot` = ?
                                        ORDER BY `rating` DESC
                                        LIMIT ?;",

                              
                                ];
                break;
            }
        }

        return $statements[$key];
    }

    /**
     * Assign the realm object to the model
     */
    public function setRealm($id): void
    {
        $this->realm = $this->realms->getRealm($id);

        $replace = [
            '_sph',
            '_soap',
            '_rbac'
        ];
        //Remove the sph/soap/rbac
        $this->emuStr = str_replace($replace, '', $this->realm->getConfig('emulator'));
    }

    /**
     * Connect to the character database
     */
    public function connect(): void
    {
        $this->realm->getCharacters()->connect();
        $this->connection = $this->realm->getCharacters()->getConnection();
    }

    /***************************************
     *            TOP ARENA FUNCTIONS
     ***************************************/

    public function getTeams(int $count = 5, int $type = 2)
    {
        //make sure the count param is digit
       // if (!ctype_digit($count)) {
       // $count = 5;
       // }

        $this->connect();

        $result = $this->connection->query($this->GetStatement('TopArenaTeams'),
            [
                $type,
                $count
            ]);

        if ($result && $result->getNumRows() > 0) {
            $players = $result->getResultArray();

            // Add rank
            $i = 1;
            foreach ($players as $key => $player) {
                $players[$key]['ord_rank'] = $this->addNumberSuffix($i);
                $i++;
            }

            // Add Gender
            $i = 1;
            foreach ($players as $key => $player) {
                //var_dump($player);
                $players[$key]['nomber_race'] = $this->addNomberRace($player['character_race']);
                $players[$key]['nomber_faction'] = $this->addNomberFaction($player['character_race']);
                $players[$key]['number_faction'] = $this->addNumberFaction($player['character_race']);
                $i++;
            }

            // Add categoria
            $i = 1;
            foreach ($players as $key => $player) {
                $players[$key]['category'] = $this->addNomberCategory($players[$key]['rating']);
                $i++;
            }            

            return $players;
        }

        unset($result);

        return false;
    }

    public function getTopHKPlayers(int $count = 10)
    {
        $this->connect();

        $result = $this->connection->query($this->GetStatement('TopHKPlayers'), [$count]);

        if ($result && $result->getNumRows() > 0) {
            $players = $result->getResultArray();

            // Add rank
            $i = 1;
            foreach ($players as $key => $player) {
                $players[$key]['rank'] = $this->addNumberSuffix($i);
                $i++;
            }

            return $players;
        }

        unset($result);

        return false;
    }

    private function addNomberFaction($num): string
    {
        switch ($num) {
            # Aliance
            case 1:
            case 3:
            case 4:
            case 7:
            case 11:
            case 22:
            case 25: 
                return 'Aliance';
                break;
            
            # Horde
            case 2:
            case 5:
            case 6:
            case 8:
            case 9:
            case 10:
            case 26:
                return 'Horde';
                break;
            default:
                return '';
                break;
        }
    }

    private function addNumberFaction($num): string
    {
        switch ($num) {
            # Aliance
            case 1:
            case 3:
            case 4:
            case 7:
            case 11:
            case 22:
            case 25: 
                return 1;
                break;
            
            # Horde
            case 2:
            case 5:
            case 6:
            case 8:
            case 9:
            case 10:
            case 26:
                return 2;
                break;
            default:
                return '';
                break;
        }
    }

    private function addNomberRace($num): string
    {
        switch ($num) {
            # Alianza
            case 1:
                return 'Humano';
                break;
            case 3:
                return 'Enano';
                break;
            case 4:
                return 'Elfo de la noche';
                break;
            case 7:
                return 'Gnomo';
                break;
            case 11:
                return 'Draenei';
                break;
            case 22:
                return 'Huragen';
                break;
            case 25:
                return 'Pandaren';
                break;
            
            # Horde
            case 2:
                return 'Orco';
                break;
            case 5:
                return 'No-muerto';
                break;
            case 6:
                return 'Tauren';
                break;
            case 8:
                return 'Trol';
                break;
            case 9:
                return 'Goblin';
                break;
            case 10:
                return 'Elfa';
                break;
            case 26:
                return 'Pandarien';
                break;
                
            default:
                return '';
                break;
        }
    }

    private function addNomberCategory($num): string
    {
        switch ($num) {
            case ($num > 0 and $num <= 1599):
                # Es Combatiente
                return 'combatant';
                break;

                case ($num >= 1600 and $num <= 1799):
                # Es Contendiente
                return 'challenger';
                break;
            case ($num >= 1800 and $num <= 2099):
                # Es Rival
                return 'rival';
                break;
            case ($num >= 2100 and $num <= 2399):
                # Es Duelista
                return 'duelist';
                break;
            case ($num >= 2400):
                # Es Gladiador
                return 'gladiator';
                break;
            
            default:
                # code...
                return '';
                break;
        }
    }

    private function addNumberSuffix($num): string
    {
        if (!in_array(($num % 100), array(11, 12, 13))) {
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1:
                    return $ord_num = $num . 'st';
                case 2:
                    return $ord_num = $num . 'nd';
                case 3:
                    return $ord_num = $num . 'rd';
            }
        }

        return $ord_num = $num . 'th';
    }
}
