<?php

class District implements JsonSerializable {

    private $districtId;
    private $districtFullName;
    private $message;
    private $totalVotes;
    private $winner;
    private $districtCandidates;
    private $restCandidatesPercent;
    private $bottomMessage;

    public function __construct() {
        
        $this->districtCandidates = array();
        $this->message = "";
        $this->bottomMessage = "";
    }

    public function jsonSerialize() {
        return array(
            'districtId' => $this->districtId,
            'districtFullName' => $this->districtFullName,
            'message' => $this->message,
            'totalVotes' => $this->totalVotes,
            'winner' => $this->winner,
            'districtCandidates' => $this->districtCandidates,
            'restCandidatesPercent' => $this->restCandidatesPercent,
            'bottomMessage' => $this->bottomMessage
        );
    }

    function getDistrictId() {
        return $this->districtId;
    }

    function getDistrictFullName() {
        return $this->districtFullName;
    }

    function getMessage() {
        return $this->message;
    }

    function getTotalVotes() {
        return $this->totalVotes;
    }

    function getWinner() {
        return $this->winner;
    }

    function getDistrictCandidates() {
        return $this->districtCandidates;
    }

    function getRestCandidatesPercent() {
        return $this->restCandidatesPercent;
    }

    function getBottomMessage() {
        return $this->bottomMessage;
    }

    function setDistrictId($districtId) {
        $this->districtId = $districtId;
    }

    function setDistrictFullName($districtFullName) {
        $this->districtFullName = $districtFullName;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setTotalVotes($totalVotes) {
        $this->totalVotes = $totalVotes;
    }

    function setWinner($winner) {
        $this->winner = $winner;
    }

    function setDistrictCandidates($districtCandidates) {
        $this->districtCandidates = $districtCandidates;
    }

    function setRestCandidatesPercent($restCandidatesPercent) {
        $this->restCandidatesPercent = $restCandidatesPercent;
    }

    function setBottomMessage($bottomMessage) {
        $this->bottomMessage = $bottomMessage;
    }

}
