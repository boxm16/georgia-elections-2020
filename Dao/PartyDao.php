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

    public function updateParty($party) {
        $query = "UPDATE elections SET party_logo_name='" . $party->getNewName() . "' WHERE party_number=" . $party->getNumber();
        $result = @$this->db_connection->query($query);
    }

}
