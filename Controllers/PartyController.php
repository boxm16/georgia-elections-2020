<?php

require_once 'Model/Party.php';
require_once 'Dao/PartyDao.php';

class PartyController {

    private $registeredParties;

    public function getRegisteredParties() {
        $partyDao = new PartyDao();
        $this->registeredParties = $partyDao->getRegisteredParties();
        return $this->registeredParties;
    }

    public function getPartyForUpdate() {
        if (isset($_SESSION["partyForUpdate"])) {
            $partyId = $_SESSION["partyForUpdate"];
            $partyDao = new PartyDao();
            $partyForUpdate = $partyDao->getPartyById($partyId);
            return $partyForUpdate;
        } else {
            header("Location:./errorPage.php");
        }
    }

    public function voteParty($partyId) {
        $partyDao = new PartyDao();
        $partyDao->voteParty($partyId);
    }

}
