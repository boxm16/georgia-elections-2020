<?php

require_once 'DataBaseConnection.php';

class PartyDao {

    private $db_connection;

    public function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->db_connection = $dataBaseConnection->getConnection();
    }

    public function registerParty($party) {
        
    }

    public function numberExists($number) {

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

}
