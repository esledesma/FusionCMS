<?php

use MX\MX_Controller;

/**
 * Pvp_statistics Controller Class
 * @property data_model $data_model data_model Class
 */
class Pvp_statistics extends MX_Controller
{
    public function __construct()
    {
        //Call the constructor of MX_Controller
        parent::__construct();

        $this->load->model("data_model");
        $this->load->config('pvp_statistics/pvps_config');
    }

    public function index(int|bool $RealmId = false)
    {
        $this->template->setTitle("PVP Statistics");

        $user_id = $this->user->getId();

        $data = array(
            'user_id'           => $user_id,
            'realms_count'      => !isset($this->realms),
            'selected_realm'    => $RealmId,
            'url'               => $this->template->page_url,
        );

        // Get the realms
        if (!isset($this->realms) > 0) {
            foreach ($this->realms->getRealms() as $realm) {
                //Set the first realm as realmid
                if (!$RealmId) {
                    $RealmId = $realm->getId();
                    $data['selected_realm'] = $RealmId;
                }

                $data['realms'][$realm->getId()] = array('name' => $realm->getName());
            }
        }

        //Set the realmid for the data model
        $this->data_model->setRealm($RealmId);

        //Get Top Honorable Kills Players
        $data['TopHK'] = $this->data_model->getTopHKPlayers($this->config->item("hk_players_limit"));
        
        //Get the top teams
        $data['Teams2'] = $this->data_model->getTeams($this->config->item("arena_teams_limit"), 0);
        $data['Teams3'] = $this->data_model->getTeams($this->config->item("arena_teams_limit"), 1);
        $data['Teams5'] = $this->data_model->getTeams($this->config->item("arena_teams_limit"), 2);
        $data['RBGs'] = $this->data_model->getTeams($this->config->item("arena_teams_limit"), 3);

        //Get Rated PVP Info
        // $data['RatedPVP'] = $this->data_model->getRatedPVPInfo($this->config->item("topRatedPVPInfo"));

        $output = $this->template->loadPage("pvp_statistics.tpl", $data);

        $this->template->box("PVP Statistics", $output, true, "modules/pvp_statistics/css/style.css", "modules/pvp_statistics/js/scripts.js");
    }
}
