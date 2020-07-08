<?php
require_once 'Controllers/DistrictController.php';

if (isset($_POST["goDistrictVoting"]) && isset($_POST["districtId"])) {
    $districtController = new DistrictController();
    $districtId = $_POST["districtId"];
    $district = $districtController->getDistrict($districtId);
    $candidates = $district->getDistrictCandidates();
} else {
    header("Location:errorPage.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>მაჟორიტარული არჩევნების ბიულეტენი</title>
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico"/>
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    </head>
</head>
<body>
    <div class="container">



        <div class="row">
            <h3> <?php echo $district->getDistrictFullName() ?></h3>
            <div class="col col-lg-12">
                <hr>

                <div style ="font-size:24px;" class="table-responsive">
                    <table class="table table-bordered  table-hover">
                        <thead>
                            <tr>
                                <td>ლოგო</td>
                                <td>ნომერი</td>
                                <td><center>სახელი და გვარი <br><span class="small">მხარდამჭერი პარტია</span></center></td>
                        <td>ხმის მიცემა</td>

                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($candidates) != 0) {
                                foreach ($candidates as $candidate) {
                                    $party = $candidate->getSupportingParty();

                                    echo "<tr>"
                                    . "<td style=\"background-color:" . $party->getColor() . "\"><img width=\"40px\" heigth=\"40px\" src=\"PartyLogos/" . $party->getLogoName() . "\" ></td>"
                                    . '<td ><input name="candidateId" type="hidden" value="' . $candidate->getId() . '" >' . $party->getNumber() . '</td>'
                                    . "<td><span class=\"candidateFullName\" style=\"font-weight:bold\" >" . $candidate->getFirstName() . " " . $candidate->getLastName() . "</span><br><span class=\"small\">" . $party->getName() . "</span></td> "
                                    . '<td><input type="button"  data-toggle="modal" data-target="#deleteCandidateModal"  class="btn btn-primary btn-block btn-lg" value="მიეცი ხმა" onclick="voteCandidate(this)"/></td>'
                                    . ' </tr>';
                                }
                                ?> </table></div>                       
                </div>
            </div>
            <?php
        } else {
            echo "<h1>ოკუპირებულ ტერიტორიებზე არჩევნები არ ტარდება</1> ";
        }
        ?>


        <!-- Modal for deletion confirmation start -->
        <form action="votingDispatcher.php" method="post" > 
            <div class="modal fade" id="deleteCandidateModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title text-success" id="exampleModalLabel"> დაადასტურეთ თქვენი არჩევანი</h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <center><h2 class="modal-title text-success" id="exampleModalLabel"> თქვენ ხმას აძლევთ</h2></center>
                                <br>
                                <table style ="font-size:36px;" id="votingConfirmationTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>ლოგო</td>
                                            <td>ნომერი</td>
                                            <td>დასახელება</td>
                                        </tr>
                                    </thead>
                                </table>
                                <h3 class="modal-title text-danger" ><center>ყურადღება, ეს არჩევანი საბოლოოა</center></h3>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="voteCandidate">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">გაუქმება და უკან დაბრუნება</button>
                                <button type="submit" class="btn btn-primary" >ვადასტურებ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- end of modal window for delete confirmation--> 

    </div>
    <script>
        function voteCandidate(node) {

            var row = node.parentNode.parentNode;
            var clone_row = row.cloneNode(true);
            clone_row.removeChild(clone_row.lastElementChild);


            var confirmation_table = document.getElementById("votingConfirmationTable");
            confirmation_table.deleteRow(0);
            confirmation_table.appendChild(clone_row);
        }
    </script>
</body>
</html>
