<?php

require_once 'Controllers/PartyController.php';
require_once 'Controllers/CandidateController.php';

  

if (isset($_POST["voteParty"]) && isset($_POST["partyId"])) {
    $partyId = $_POST["partyId"];
    $partyController = new PartyController();
    $partyController->voteParty($partyId);
    header("Location:majoritarianDistrictsMap.php");
}elseif (isset($_POST["voteCandidate"]) && isset($_POST["candidateId"])) {
    $candidateId = $_POST["candidateId"];
    $candidateController = new CandidateController();
    $candidateController->voteCandidate($candidateId);

    header("Location:results.php");
}else {
    header("Location:errorPage.php");
}



