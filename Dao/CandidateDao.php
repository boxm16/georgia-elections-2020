<?php

require_once 'DataBaseConnection.php';
require_once 'Model/Party.php';

class CandidateDao {

    public function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->db_connection = $dataBaseConnection->getConnection();
    }

    public function getCandidates() {
        $candidates = array();
        $query = "SELECT first_name, last_name, county_number, supporting_party_number, t1.votes, party_name, party_logo_name, party_color"
                . " FROM elections_majoritarians t1 INNER JOIN elections t2 ON t1.supporting_party_number=t2.party_number "
                . " ORDER BY county_number, votes DESC, supporting_party_number";
        if (!($result = $this->db_connection->query($query))) {
            echo 'Could not connect to db<br />';
            printf("Errormessage: %s\n", $db_connection->error);
            exit;
        } else {
            while ($row = $result->fetch_object()) {

                $firstName = $row->first_name;
                $lastName = $row->last_name;
                $votes = $row->votes;
                $districtId = $row->county_number;
                $supportingPartyNumber = $row->supporting_party_number;
                $partyLogoName = $row->party_logo_name;
                $partyName = $row->party_name;
                $partyColor = $row->party_color;


                $candidate = new Candidate();
                $candidate->setFirstName($firstName);
                $candidate->setLastName($lastName);
                $candidate->setVotes($votes);
                $candidate->setDistrictId($districtId);

                $party = new Party();
                $party->setNumber($supportingPartyNumber);
                $party->setName($partyName);
                $party->setLogoName($partyLogoName);
                $party->setColor($partyColor);
                $candidate->setSupportingParty($party);

                array_push($candidates, $candidate);
            }
        }
        return $candidates;
    }

}
