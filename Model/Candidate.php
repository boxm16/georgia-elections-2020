<?php

class Candidate implements JsonSerializable {

    private $id;
    private $firstName;
    private $lastName;
    private $votes;
    private $percent;
    private $districtId;
    private $supportingParty;

    public function __construct() {
        $this->votes = 0;
        $this->percent = 0;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'votes' => $this->votes,
            'percent' => $this->percent,
            'districtId' => $this->districtId,
            'supportingParty' => $this->supportingParty
        );
    }

    function getId() {
        return $this->id;
    }

    function getFirstName() {
        return $this->firstName;
    }

    function getLastName() {
        return $this->lastName;
    }

    function getVotes() {
        return $this->votes;
    }

    function getPercent() {
        return $this->percent;
    }

    function getDistrictId() {
        return $this->districtId;
    }

    function getSupportingParty() {
        return $this->supportingParty;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    function setVotes($votes) {
        $this->votes = $votes;
    }

    function setPercent($percent) {
        $this->percent = $percent;
    }

    function setDistrictId($districtId) {
        $this->districtId = $districtId;
    }

    function setSupportingParty($supportingParty) {
        $this->supportingParty = $supportingParty;
    }

}
