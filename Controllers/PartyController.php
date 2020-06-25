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

}
