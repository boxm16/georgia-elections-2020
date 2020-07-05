<?php
require_once 'Controllers/ResultsController.php';
$resultsController = new ResultsController();
$barrierUpParties = $resultsController->getQualifiedParties();
$barrierDownParties = $resultsController->getDisqualifiedParties();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>შედეგები</title>
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico"/>
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    </head>
    <body>

        <h1>პროპორციული შედეგები</h1>
        <hr>
        <div style="text-align: center"> 

            <img src='Controllers/proportional_results.php' width="1400" height="400" style="border-style:solid;border-color:black;border-width:1px;">
        </div>


        <hr>

        <table style="border:solid">
            <th>name</th>
            <th>percents</th>
            <th>Number of parties in Block</th>
            <th>first calculation mandates</th>
            <th>Full mandate number</th>
            <th>added from left mandates</th>
            <th>Proportional Mandates  </th>
            <th>Ghost mandates  </th>
            <th>Majoritarian mandates  </th>
            <th>Cutted mandates  </th>
            <th>Added from Cutted mandates  </th>
            <th>Final Proportional mandates  </th>
            <?php
            foreach ($barrierUpParties as $party) {

                echo "<tr><td>" . $party->getNumber() . " " . $party->getName() . "</td><td>  " . $party->getPercents() . "%.</td><td>" . $party->getBlock() . "</td><td>" . "" . $party->getFirstCalculationMandates() . "</td><td>" . $party->getMandateFullNumber() . "</td><td>" . $party->getAddedFromLeftMandates() . "</td><td>" . $party->getMandates() . "</td><td>" . $party->getGhostMandates() . "</td><td>" . $party->getMajoritarianMandates() . "</td><td>" . $party->getCuttedMandates() . "</td><td>" . $party->getAddedFromCuttedMandates() . "</td><td>" . $party->getFinalProportionalMandates() . "</td></tr>";
            }
            echo "<tr><td>-----------------</td><td>-----------------</td><td>-----1%----------</td><td>-----------------</td><td>-----------------</td></tr>";
            foreach ($barrierDownParties as $party) {
                echo "<tr><td>" . $party->getNumber() . " " . $party->getName() . "</td><td>  " . $party->getPercents() . "%.</td><td>" . $party->getBlock() . "</td><td>" . "" . $party->getFirstCalculationMandates() . "</td><td>" . $party->getMandateFullNumber() . "</td><td>" . $party->getAddedFromLeftMandates() . "</td><td>" . $party->getMandates() . "</td><td>" . $party->getGhostMandates() . "</td><td>" . $party->getMajoritarianMandates() . "</td><td>" . $party->getCuttedMandates() . "</td><td>" . $party->getAddedFromCuttedMandates() . "</td><td>" . $party->getFinalProportionalMandates() . "</td></tr>";
            }
            ?>
        </table >
        <?php
        echo "<br>Total Voted:" . $resultsController->getTotalVotes();
        echo "<br>Qualified VOtes:" . $resultsController->getQualifiedVotes();
        echo "<br>Left Mandates:" . $resultsController->getLeftMandates();
        echo "<br>";
        ?>
    </body>
</html>
