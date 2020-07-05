<?php

class Party implements JsonSerializable {

    private $name;
    private $number;
    private $logoName;
    private $color;
    private $votes;
    private $block;
    private $percents;
    private $mandateFullNumber;
    private $firstCalculationMandates;
    private $addedFromLeftMandates;
    private $majoritarianMandates;
    private $mandates;
    private $addedFromCuttedMandates; //mandates added from cutting mechanisma
    private $finalProportionalMandates;
    private $ghostMandates;
    private $cuttedMandates;

    public function __construct() {
        $this->ghostMandates = 0;
        $this->mandates = 0;
        $this->cuttedMandates = 0;
        $this->addedFromCuttedMandates=0;
        $this->finalProportionalMandates = 0;
        $this->addedFromLeftMandates = 0;
        $this->majoritarianMandates = 0;
    }

    public function jsonSerialize() {
        return array(
            'name' => $this->name,
            'number' => $this->number,
            'logoName' => $this->logoName,
            'color' => $this->color,
            'votes' => $this->votes,
            'block' => $this->block,
            'percents' => $this->percents,
            'mandates' => $this->mandates,
            'ghostMandates' => $this->ghostMandates
        );
    }

    function getAddedFromCuttedMandates() {
        return $this->addedFromCuttedMandates;
    }

    function setAddedFromCuttedMandates($addedFromCuttedMandates) {
        $this->addedFromCuttedMandates = $addedFromCuttedMandates;
    }

    function getFinalProportionalMandates() {
        return $this->finalProportionalMandates;
    }

    function setFinalProportionalMandates($finalProportionalMandates) {
        $this->finalProportionalMandates = $finalProportionalMandates;
    }

    function getMajoritarianMandates() {
        return $this->majoritarianMandates;
    }

    function setMajoritarianMandates($majoritarianMandates) {
        $this->majoritarianMandates = $majoritarianMandates;
    }

    function getMandateFullNumber() {
        return $this->mandateFullNumber;
    }

    function setMandateFullNumber($mandateFullNumber) {
        $this->mandateFullNumber = $mandateFullNumber;
    }

    function getFirstCalculationMandates() {
        return $this->firstCalculationMandates;
    }

    function getAddedFromLeftMandates() {
        return $this->addedFromLeftMandates;
    }

    function getMandates() {
        return $this->mandates;
    }

    function setFirstCalculationMandates($firstCalculationMandates) {
        $this->firstCalculationMandates = $firstCalculationMandates;
    }

    function setAddedFromLeftMandates($addedFromLeftMandates) {
        $this->addedFromLeftMandates = $addedFromLeftMandates;
    }

    function setMandates($mandates) {
        $this->mandates = $mandates;
    }

    function getPercents() {
        return $this->percents;
    }

    function getGhostMandates() {
        return $this->ghostMandates;
    }

    function setPercents($percents) {
        $this->percents = $percents;
    }

    function setGhostMandates($ghostMandates) {
        $this->ghostMandates = $ghostMandates;
    }

    function getBlock() {
        return $this->block;
    }

    function setBlock($block) {
        $this->block = $block;
    }

    function getName() {
        return $this->name;
    }

    function getNumber() {
        return $this->number;
    }

    function getLogoName() {
        return $this->logoName;
    }

    function getColor() {
        return $this->color;
    }

    function getVotes() {
        return $this->votes;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setLogoName($logoName) {
        $this->logoName = $logoName;
    }

    function setColor($color) {
        $this->color = $color;
    }

    function setVotes($votes) {
        $this->votes = $votes;
    }
    function getCuttedMandates() {
        return $this->cuttedMandates;
    }

    function setCuttedMandates($cuttedMandates) {
        $this->cuttedMandates = $cuttedMandates;
    }


}
