<?php
require_once 'Controllers/AdminController.php';
require_once 'Controllers/DistrictController.php';
require_once 'Controllers/PartyController.php';
session_start();
$adminController = new AdminController();
$authorized = $adminController->isAuthorized();

if (isset($_POST["goDistrictUpdate"]) && isset($_POST["districtId"])) {
    $districtForUpdate = $_POST["districtId"];
    $_SESSION["districtForUpdate"] = $districtForUpdate;
//   i need this variable in session, to cary around
}
$districtController = new DistrictController();
$districtController->dispatchUpdateRequests();
$errors = $districtController->getErrors();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ADMIN-UPDATE PARTY</title>
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico"/>
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    </head>
    <body>
        <div class="container">


            <?php
            if ($authorized) {
                $districtForUpdate = $districtController->getDistrictForUpdate();
                $candidates = $districtForUpdate->getDistrictCandidates();
                ?>
                <div class="row">

                    <h3> <?php echo $districtForUpdate->getDistrictFullName() ?></h3>
                    <a href="admin.php">GO BACK TO MAIN PAGE</a>
                    <hr>

                    <div class="col col-lg-10">
                        <h4>Add Candidates</h4>
                        <form action="updateDistrict.php" method="post"> 
                            <div class="form-group">
                                <label for="firstName">Enter Candidate`s First Name</label>
                                <input name="firstName" type="text" class="form-control"  aria-describedby="firstNameError" placeholder="Enter First Name">
                                <p style="color:red"><?php echo $errors["firstNameError"] ?></p>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Enter Candidate`s Last Number</label>
                                <input name="lastName" type="text" class="form-control"  aria-describedby="partyNumberError" placeholder="Enter Party Number">
                                <p style="color:red"><?php echo $errors["lastNameError"] ?></p>
                            </div> 

                            <div class="form-group">
                                <label for="inputState">State</label>
                                <select name="supportingPartyId"  class="form-control">
                                    <option selected>Choose Supporting Party</option>
                                    <?php
                                    $partyController = new PartyController();
                                    $registeredParties = $partyController->getRegisteredParties();
                                    foreach ($registeredParties as $party) {
                                        echo ' <option value="' . $party->getNumber() . '">' . $party->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                                <p style="color:red"><?php echo $errors["supportingPartyError"] ?></p>

                            </div>
                            <p style="color:red"><?php echo $errors["PrimeMessage"]; ?></p>
                            <br>
                            <input name="districtId" value="<?php echo $districtForUpdate->getDistrictId() ?>" type="hidden">
                            <input name="addCandidate"  type="hidden">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Candidate"/>
                        </form>
                    </div>

                </div>

                <div class="row">
                    <div class="col col-lg-12">
                        <hr>
                        <?php
                        if (count($candidates) != 0) {
                            echo '<p style = "color:red">' . $errors["UpdatePrimeMessage"] . '</p>';

                            echo '<div style ="font-size:24px;" class="table-responsive"><table class="table table-bordered table-hover">';
                            echo '<thead>'
                            . '<tr>'
                            . '<td>Number</td>'
                            . '<td>Party Logo</td>'
                            . '<td>Name</td>'
                            . '<td>Delete</td>'
                            . '<td>Change Party</td>'
                            . '<td>Change Name</td>'
                            . '</tr>'
                            . '</thead>';
                            foreach ($candidates as $candidate) {
                                $party = $candidate->getSupportingParty();

                                echo "<tr>"
                                . '<td ><input name="candidateId" type="hidden" value="' . $candidate->getId() . '" >' . $party->getNumber() . '</td>'
                                . "<td style=\"background-color:" . $party->getColor() . "\"><img width=\"40px\" heigth=\"40px\" src=\"partyLogos/" . $party->getLogoName() . "\" ></td>"
                                . "<td><span class=\"candidateFullName\" style=\"font-weight:bold\" >" . $candidate->getFirstName() . " " . $candidate->getLastName() . "</span><br><span class=\"small\">" . $party->getName() . "</span></td> "
                                . '<td><input type="button"  data-toggle="modal" data-target="#deleteCandidateModal"  class="btn btn-danger btn-block btn-sm" value="Delete Candidate" onclick="deleteCandidate(this)"/></td>'
                                . '<td><input type="button" data-toggle="modal" data-target="#changeSupportingPartyModal" class="btn btn-warning btn-block btn-sm" value="Change Supporting Party" onclick="changeSupportingParty(this)"/></td>'
                                . '<td><input type="button" data-toggle="modal" data-target="#changeCandidateNameModal"  class="btn btn-success btn-block btn-sm" value="Change Candidate Name" onclick="changeCandidateName(this)"/></td>'
                                . ' </tr>';
                            }
                            echo '</table></div>';
                            ?>                        
                        </div>
                    </div>
                    <?php
                } else {
                    echo "<h1>ოკუპირებულ ტერიტორიებზე არჩევნები არ ტარდება</1> ";
                }
                ?>


                <!-- Modal for deletion confirmation start -->
                <form action="updateParty.php" method="post" > 
                    <div class="modal fade" id="deleteCandidateModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="exampleModalLabel"> ATTANTION, CONFIRMATION IS FINAL</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h3><center>YOU ARE GOING TO DELETE</center></h3>
                                        <table style ="font-size:36px;" id="deleteConfirmationTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Logo</td>
                                                    <td>Number</td>
                                                    <td>Name</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <h3><center>CONFIRM CANDIDATE DELETION</center></h3>

                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="deleteCandidate">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL AND GO BACK</button>
                                        <button type="submit" class="btn btn-danger" >COFIRM DELETION</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end of modal window for delete confirmation--> 


                <!-- Modal for change supporting party start -->
                <form action="updateDistrict.php" method="post" > 
                    <div class="modal fade" id="changeSupportingPartyModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="exampleModalLabel"> CHANGE SUPPORTING PARTY FOR CANDIDATE</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table style ="font-size:36px;" id="changeSupportingPartyTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Logo</td>
                                                    <td>Number</td>
                                                    <td>Name</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <center><h3>Select New Supporting Party</h3></center>
                                        <select name="newSupportingPartyId"  class="form-control">
                                            <option selected>Choose New Supporting Party</option>
                                            <?php
                                            foreach ($registeredParties as $party) {
                                                echo ' <option value="' . $party->getNumber() . '">' . $party->getName() . '</option>';
                                            }
                                            ?>
                                        </select>


                                    </div>
                                    <div class="modal-footer">

                                        <input type="hidden" name="changeSupportingParty">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL AND GO BACK</button>
                                        <button type="submit" class="btn btn-warning" >SAVE CHANGES</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end of modal window for change supporting party--> 

                <!-- Modal for change supporting party start -->
                <form action="updateDistrict.php" method="post" > 
                    <div class="modal fade" id="changeCandidateNameModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="exampleModalLabel"> CHANGE NAME FOR CANDIDATE</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table style ="font-size:36px;" id="changeCandidateNameTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Logo</td>
                                                    <td>Number</td>
                                                    <td>Name</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <center><h3>Enter New Name For Candidate</h3></center>
                                        <div class="form-group">
                                            <label for="firstName">Change Candidate`s First Name</label>
                                           <input  name="newFirstName" id="newFirstName" type="text" class="form-control font-weight-bold" style ="font-size:34px" aria-describedby="firstNameError" >
                                        </div>
                                        <div class="form-group">
                                            <label for="lastName">Change Candidate`s Last Number</label>
                                            <input name="newLastName" id="newLastName" type="text" class="form-control font-weight-bold"  style ="font-size:34px" aria-describedby="partyNumberError"  >
                                        </div> 
                                    </div>
                                    <div class="modal-footer">

                                        <input type="hidden" name="changeCandidateName">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL AND GO BACK</button>
                                        <button type="submit" class="btn btn-success" >SAVE NEW NAME</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end of modal window for change supporting party--> 


            </div>


            <?php
        } else {
            echo 'You are not authorized for this page. Login in or go to start page.'
            . '<br><a href="index.php">CLick to go to start page</a>';
        }
        ?>  
        <script>

            function deleteCandidate(node) {

                var row = node.parentNode.parentNode;
                var clone_row = row.cloneNode(true);
                clone_row.removeChild(clone_row.lastElementChild);
                clone_row.removeChild(clone_row.lastElementChild);
                clone_row.removeChild(clone_row.lastElementChild);

                var confirmation_table = document.getElementById("deleteConfirmationTable");
                confirmation_table.deleteRow(0);
                confirmation_table.appendChild(clone_row);
            }

            function changeSupportingParty(node) {
                var row = node.parentNode.parentNode;
                var clone_row = row.cloneNode(true);
                clone_row.removeChild(clone_row.lastElementChild);
                clone_row.removeChild(clone_row.lastElementChild);
                clone_row.removeChild(clone_row.lastElementChild);

                var confirmation_table = document.getElementById("changeSupportingPartyTable");
                confirmation_table.deleteRow(0);
                confirmation_table.appendChild(clone_row);
            }

            function changeCandidateName(node) {
                var row = node.parentNode.parentNode;
                var clone_row = row.cloneNode(true);
                clone_row.removeChild(clone_row.lastElementChild);
                clone_row.removeChild(clone_row.lastElementChild);
                clone_row.removeChild(clone_row.lastElementChild);

                var confirmation_table = document.getElementById("changeCandidateNameTable");
                confirmation_table.deleteRow(0);
                confirmation_table.appendChild(clone_row);

                var td = clone_row.cells[2];
                var elements = td.querySelectorAll(".candidateFullName");
                var fullName = elements[0].innerHTML;
                var arrayedName = fullName.split(" ");
                var firstName = arrayedName[0];
                var lastName = arrayedName[1];
                var newFirstName = document.getElementById("newFirstName");
                newFirstName.value = firstName;
                var newLastName = document.getElementById("newLastName");
                newLastName.value = lastName;
            }

        </script>

    </body>
</html>
