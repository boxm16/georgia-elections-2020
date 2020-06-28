<?php

class Party implements JsonSerializable {

    private $name;
    private $number;
    private $logoName;
    private $color;
    private $votes;

    public function jsonSerialize() {
        return array(
            'name' => $this->name,
            'number' => $this->number,
            'logoName' => $this->logoName,
            'color' => $this->color,
            'votes' => $this->votes,
        );
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

}
