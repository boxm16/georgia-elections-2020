<?php

require_once 'Dao/PartyDao.php';
require_once 'Model/Party.php';
require_once 'Controllers/DistrictController.php';

class ResultsController {

    private $totalVotes;
    private $qualifiedVotes;
    private $qualifiedParties;
    private $disqualifiedParties;
    private $leftMandates;
    private $leftOverParts;
    private $districts;
    private $cuttedMandates;

    function getLeftOverParts() {
        return $this->leftOverParts;
    }

    public function __construct() {

        $qualifiedVotes = 0;
        $this->cuttedMandates = 0;
        $this->qualifiedParties = array();
        $this->disqualifiedParties = array();
        $this->majoritarianMandates = array();
        $this->totalVotes = $this->getAllPartyVotes();
        $this->qualifyParties();
        $this->calculateProportionalMandates();
        $this->calculateGhostMandates();
        $districtController = new DistrictController();
        $this->districts = $districtController->getDistricts();
        $this->calculateDistricts();
        $this->calculateFinalProportional();
    }

    private function getAllPartyVotes() {
        $partyDao = new PartyDao();
        $allPartiesVotes = $partyDao->getAllPartyVotes();
        return $allPartiesVotes;
    }

    private function qualifyParties() {
        if ($this->totalVotes > 0) {
            $partyDao = new PartyDao();
            $parties = $partyDao->getPartiesOrderedByVotes();
            foreach ($parties as $party) {
                $partyVotes = $party->getVotes();
                $partyBlock = $party->getBlock();
                $partyPercents = (100 * $partyVotes) / $this->totalVotes;
                $party->setPercents($partyPercents);
                if ($partyPercents >= $partyBlock) {
                    $this->qualifiedParties = $this->array_push_assoc($this->qualifiedParties, $party->getNumber(), $party);
                    $this->qualifiedVotes += $partyVotes;
                } else {
                    $this->disqualifiedParties = $this->array_push_assoc($this->disqualifiedParties, $party->getNumber(), $party);
                }
            }
        } else {
            header("Locationa:errorPage.php");
        }
    }

    private function calculateProportionalMandates() {
        $totalMandates = 0;


        foreach ($this->qualifiedParties as $party) {
            $fullNumber = ($party->getVotes() * 120) / $this->qualifiedVotes;
            $mandates = (intval($fullNumber)); //integer part equals mandate quantity
            $leftOverPart = $fullNumber - $mandates;
            //saving leftOverParts in array so i can sort it and find the most big, so i can add left mandates be this order

            $this->leftOverParts = $this->array_push_assoc($this->leftOverParts, $party->getNumber(), $leftOverPart);

            $totalMandates += $mandates;
            $party->setMandateFullNumber($fullNumber);
            $party->setMandates($mandates);
            $party->setFirstCalculationMandates($mandates);
        }

        arsort($this->leftOverParts); //sorting leftOverParts to set it in order

        $this->leftMandates = $leftMandatesCount = 120 - $totalMandates;

        $index = 0;
        $keyLeftOverParts = array_keys($this->leftOverParts);

        while ($leftMandatesCount > 0) {

            $leftMandatesCount = $leftMandatesCount - 1;
            $partyNumber = $keyLeftOverParts[$index];
            $party = $this->qualifiedParties[$partyNumber];
            $mandates = $party->getMandates();
            $mandates ++;
            $addedFromLeftMandates = $party->getAddedFromLeftMandates();
            $addedFromLeftMandates++;
            $party->setMandates($mandates);
            $party->setAddedFromLeftMandates($addedFromLeftMandates);
            $this->qualifiedParties[$partyNumber] = $party;
            $index = $index + 1;
            if ($index > count($this->qualifiedParties)) {//if we are at the top of list, and still left mandates, we go again to te bottom
                $index = 0;
            }
        }
    }

    private function calculateGhostMandates() {

        foreach ($this->qualifiedParties as $party) {
            $percent = ($party->getVotes() / $this->totalVotes) * 100;
            $closingMechanismPercentage = $percent + ($percent / 4);
            $topPossibleMandates = intval((150 * $closingMechanismPercentage) / 100);
            $ghostMandates = $topPossibleMandates - $party->getMandates();
            $party->setGhostMandates($ghostMandates);
        }
    }

    private function calculateDistricts() {
        for ($x = 1; $x < 31; $x++) {
            $district = $this->districts[$x];
            $districtVotes = $this->calculateDistrictVotes($district);
            $district->setTotalVotes($districtVotes);
        }
        for ($x = 1; $x < 31; $x++) {
            $district = $this->districts[$x];
            $this->calculateDistrictCandidates($district);
        }
    }

    private function calculateDistrictVotes($district) {
        $districtVotes = 0;
        foreach ($district->getDistrictCandidates() as $candidate) {
            $districtVotes += $candidate->getVotes();
        }
        return $districtVotes;
    }

    private function calculateDistrictCandidates($district) {
        $district->setWinner(null);
        $message = "გამარჯვებული ვერ ვლინდება პირველივე ტურში. საჭიროა მეორე ტური";

        $districtTotalVotes = $district->getTotalVotes();
        $restCandidatesPercent = 100;
        $candidateIndex = 0;
        foreach ($district->getDistrictCandidates() as $candidate) {
            $candidateIndex++;
            if ($districtTotalVotes > 0) {
                $candidatePercent = ($candidate->getVotes() * 100) / $districtTotalVotes;
                $candidatePercentRounded = round($candidatePercent, 2);
                $candidate->setPercent($candidatePercentRounded);
                if ($candidateIndex < 3) {
                    $restCandidatesPercent -= $candidatePercentRounded;
                }

                if ($candidatePercentRounded > 50) {

                    $winnerParty = $candidate->getSupportingParty();
                    $winnerPartyNumber = $winnerParty->getNumber();
                    $this->addMajoritarianMandateToParty($winnerPartyNumber);
                    $district->setWinner($winnerParty);


                    $message = "გამარჯვებული ვლინდება პირველივე ტურში";
                }
            } else {
                $candidate->setPercent(0);
                $message = "არ არსებობს არცერთი მიცემული ხმა";
                $restCandidatesPercent = 0;
            }
            $bottomMessage = "დანარჩენი კანდიდატები ";
            $district->setRestCandidatesPercent(round($restCandidatesPercent, 2));
            $district->setMessage($message);
            $district->setBottomMessage($bottomMessage);
        }
    }

    //this is function to push key=>values into associative array
    private function array_push_assoc($array, $key, $value) {
        $array[$key] = $value;
        return $array;
    }

    function getLeftMandates() {
        return $this->leftMandates;
    }

    function setLeftMandates($leftMandates) {
        $this->leftMandates = $leftMandates;
    }

    function getTotalVotes() {
        return $this->totalVotes;
    }

    function getQualifiedVotes() {
        return $this->qualifiedVotes;
    }

    function getQualifiedParties() {
        return $this->qualifiedParties;
    }

    function getDisqualifiedParties() {
        return $this->disqualifiedParties;
    }

    function setTotalVotes($totalVotes) {
        $this->totalVotes = $totalVotes;
    }

    function setQualifiedVotes($qualifiedVotes) {
        $this->qualifiedVotes = $qualifiedVotes;
    }

    function setQualifiedParties($qualifiedParties) {
        $this->qualifiedParties = $qualifiedParties;
    }

    function setDisqualifiedParties($disqualifiedParties) {
        $this->disqualifiedParties = $disqualifiedParties;
    }

    public function getDistricts() {
        return $this->districts;
    }

    public function addMajoritarianMandateToParty($partyNumber) {
        if (array_key_exists($partyNumber, $this->qualifiedParties)) {
            $party = $this->qualifiedParties[$partyNumber];
            $partyMajoritarianMandates = $party->getMajoritarianMandates();
            $partyMajoritarianMandates++;
            $party->setMajoritarianMandates($partyMajoritarianMandates);
            $this->qualifiedParties[$partyNumber] = $party;
        }
        if (array_key_exists($partyNumber, $this->disqualifiedParties)) {
            $party = $this->disqualifiedParties[$partyNumber];
            $partyMajoritarianMandates = $party->getMajoritarianMandates();
            $partyMajoritarianMandates++;
            $party->setMajoritarianMandates($partyMajoritarianMandates);
            $this->disqualifiedParties[$partyNumber] = $party;
        }
    }

    private function calculateFinalProportional() {
        $closingMechanismCuttedMandates = 0;
        //first cutting closingMechanismMandates
        foreach ($this->qualifiedParties as $party) {
            $ghostMandates = $party->getGhostMandates();
            $majoritarianMandates = $party->getMajoritarianMandates();
            if ($majoritarianMandates > $ghostMandates) {
                $closingMechanismCutter = $majoritarianMandates - $ghostMandates;
                $closingMechanismCuttedMandates++;
                $this->cuttedMandates++;
                $party->setFinalProportionalMandates($party->getMandates() - $closingMechanismCutter);
                $partyCuttedMandate = $party->getCuttedMandates();
                $partyCuttedMandate++;
                $party->setCuttedMandates($partyCuttedMandate);
            } else {
                $party->setFinalProportionalMandates($party->getMandates());
            }
            //now dispensing those cutted mandates to the best parties that are not subject to cutting
            while ($closingMechanismCuttedMandates > 0) {
                foreach ($this->qualifiedParties as $party) {
                    $finalProportionalMandates = $party->getFinalProportionalMandates();
                    $ghostMandates = $party->getGhostMandates();
                    $mandates = $party->getMandates();
                    if ($finalProportionalMandates < $ghostMandates + $mandates) {
                        $finalProportionalMandates++;
                        $party->setFinalProportionalMandates($finalProportionalMandates);
                        $addedFromCuttedMandates = $party->getAddedFromCuttedMandates();
                        $addedFromCuttedMandates++;
                        $party->setAddedFromCuttedMandates($addedFromCuttedMandates);
                        $closingMechanismCuttedMandates--;
                        if ($closingMechanismCuttedMandates < 1) {
                            break;
                        }
                    }
                }
            }
        }
    }

    function getCuttedMandates() {
        return $this->cuttedMandates;
    }

    public function countQualifiedParties() {
        return count($this->qualifiedParties);
    }

    function getMajoritarianMandates() {

        $majoritarianMandates = array();

        for ($x = 1; $x < 31; $x++) {
            $district = $this->districts[$x];
            if ($district->getWinner() == null) {
                $party = new Party();
                $party->setNumber(0);
                array_push($majoritarianMandates, $party);
            } else {
                $winnerParty = $district->getWinner();
                array_push($majoritarianMandates, $winnerParty);
            }
        } return $majoritarianMandates;
    }

}
