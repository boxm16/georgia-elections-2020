<?php

require_once 'DataBaseConnection.php';

class PartyDao {

    private $db_connection;

    public function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->db_connection = $dataBaseConnection->getConnection();
    }

    public function registerParty($party) {
        $query = "INSERT INTO elections (party_name, party_number,  party_color, party_logo_name) VALUES(?, ?, ?, ?)";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("siss", $party->getName(), $party->getNumber(), $party->getColor(), $party->getLogoName());

        $statement->execute();
    }

    public function partyNumberExists($number) {

        $query = "SELECT party_number FROM elections WHERE party_number=? LIMIT 1";

        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("i", $number);
        $statement->execute();


        $result = $statement->get_result();
        if ($result->num_rows === 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getRegisteredParties() {
        $registeredParties = array();
        $query = "SELECT * FROM elections ORDER BY party_number ASC";
        if (!($result = @$this->db_connection->query($query))) {
            header("Location:../errorPage.php");
        } else {
            while ($row = $result->fetch_object()) {
                $party = new Party();
                $party->setNumber($row->party_number);
                $party->setName($row->party_name);
                $party->setColor($row->party_color);
                $party->setLogoName($row->party_logo_name);
                array_push($registeredParties, $party);
            }
            return $registeredParties;
        }
    }

    public function deleteParty($party) {
        $query = "DELETE FROM elections WHERE party_number=?";
        $partyId = $party->getNumber();
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("i", $partyId);
        $statement->execute();
    }

    public function getPartyById($partyId) {
        $party = new Party();
        $query = "SELECT * FROM elections WHERE party_number=?";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("i", $partyId);
        $statement->execute();
        $result = $statement->get_result();
        while ($row = $result->fetch_assoc()) {

            $party->setNumber($row['party_number']);
            $party->setName($row['party_name']);
            $party->setLogoName($row['party_logo_name']);
            $party->setColor($row['party_color']);
        }
        return $party;
    }

    public function updateParty($party, $newPartyNumber) {
        $partyNumber = $party->getNumber();
        $partyName = $party->getName();
        $partyColor = $party->getColor();
        $partyLogoName = $party->getLogoName();


        $query = "UPDATE elections SET party_number=?, party_name=?, party_color=?, party_logo_name=? WHERE party_number=?";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("isssi", $newPartyNumber, $partyName, $partyColor, $partyLogoName, $partyNumber);

        $statement->execute();
    }

    public function updatePartyNameAndColor($party) {
        $partyNumber = $party->getNumber();
        $partyName = $party->getName();
        $partyColor = $party->getColor();

        $query = "UPDATE elections SET party_name=?, party_color=? WHERE party_number=?";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("ssi", $partyName, $partyColor, $partyNumber);

        $statement->execute();
    }

    public function updatePartyLogo($party) {
        $party_logo_name = $party->getLogoName();
        $query = "UPDATE elections SET party_logo_name=? WHERE party_number=?";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("si", $party_logo_name, $partyNumber);

        $statement->execute();
    }

}
