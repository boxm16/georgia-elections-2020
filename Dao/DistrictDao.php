<?php

require_once 'DataBaseConnection.php';
require_once 'Model/District.php';
require_once 'Model/Candidate.php';

class DistrictDao {

    private $db_connection;

    public function __construct() {
        $dataBaseConnection = new DataBaseConnection();
        $this->db_connection = $dataBaseConnection->getConnection();
    }

    public function getDistrictById($districtId) {
        $district = new District();
        $districtCandidates = array();
        $query = "SELECT id, first_name, last_name, county_number, supporting_party_number, t1.votes, party_name, party_logo_name, party_color"
                . " FROM elections_majoritarians t1 INNER JOIN elections t2 ON t1.supporting_party_number=t2.party_number "
                . " WHERE t1.county_number=" . $districtId . " ORDER BY county_number, supporting_party_number";
        if (!($result = $this->db_connection->query($query))) {
//header('Location:errorPage.php');
            echo 'Could not connect to db<br />';
            printf("Errormessage: %s\n", $db_connection->error);
            exit;
        } else {
            while ($row = $result->fetch_object()) {
                $id = $row->id;
                $firstName = $row->first_name;
                $lastName = $row->last_name;

                $districtId = $row->county_number; //why on earth i named this field in mySQL "county" and not "district"????????
                $supportingPartyNumber = $row->supporting_party_number;
                $partyLogoName = $row->party_logo_name;
                $partyName = $row->party_name;
                $partyColor = $row->party_color;
                $supportingParty = new Party();
                $supportingParty->setNumber($supportingPartyNumber);
                $supportingParty->setLogoName($partyLogoName);
                $supportingParty->setName($partyName);
                $supportingParty->setColor($partyColor);

                $candidate = new Candidate();
                $candidate->setSupportingParty($supportingParty);
                $candidate->setId($id);
                $candidate->setFirstName($firstName);
                $candidate->setLastName($lastName);
                $candidate->setDistrictId($districtId);
                array_push($districtCandidates, $candidate);
                $district->setDistrictId($districtId);
                $district->setDistrictCandidates($districtCandidates);
            }
        }
        return $district;
    }

}
