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

    public function deleteCandidate($candidateId) {
        $query = "DELETE FROM elections_majoritarians WHERE id=?";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("i", $candidateId);
        $statement->execute();
    }

    public function addCandidate($districtId, $candidate) {

        $query = "INSERT INTO elections_majoritarians (first_name, last_name, county_number, supporting_party_number) VALUES (?,?,?,?)";
        $firstName = $candidate->getFirstName();
        $lastName = $candidate->getLastName();
        $supportingParty = $candidate->getSupportingParty();
        $supportingPartyNumber = $supportingParty->getNumber();
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("ssii", $firstName, $lastName, $districtId, $supportingPartyNumber);
        $statement->execute();
    }

    public function changeSupportingParty($candidateId, $newSupportingPartyNumber) {
        $query = "UPDATE elections_majoritarians SET supporting_party_number=? WHERE id=?";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("ii", $newSupportingPartyNumber, $candidateId);
        $statement->execute();
    }

    public function changeCandidateName($candidate) {
        $id = $candidate->getId();
        $firstName = $candidate->getFirstName();
        $lastName = $candidate->getLastName();
        $query = "UPDATE elections_majoritarians SET first_name=?, last_name=? WHERE id=?";
        $statement = $this->db_connection->prepare($query);
        $statement->bind_param("ssi", $firstName, $lastName, $id);
        $statement->execute();
    }

}
