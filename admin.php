<?php
require_once 'Controllers/AdminController.php';
require_once 'Controllers/PartyController.php';
session_start();
$adminController = new AdminController();
$authorized = $adminController->isAuthorized();
$adminController->dispatchRequests();
$errors = $adminController->getErrors();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ELECETIONS-ADMIN</title>
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
            <h1>ELECTIONS ADMIN PAGE</h1>

            <?php if (!$authorized) { ?>
                <div class="row">
                    <form action="admin.php" method ="POST">
                        <div class="form-group"> 
                            <label for="id">ID</label>
                            <input name="id" id="id" class="form-control" type="text"><br>
                            <label for="password" >Password</label>
                            <input name="password" type="password" class="form-control"><br><br>
                            <input name="authorize" hidden>
                            <p> <?php echo $errors["loginMessage"]; ?></p>
                            <input type="submit" value="SUBMIT">
                        </div>
                    </form>
                </div>
                <?php
            } else {
                ?>

                <div class="row">

                    <form action="admin.php" method="POST">
                        <div class="form-group">
                            <input name="logout" type="text" hidden="hidden" value="logout">
                            <input type="submit" value="Log Out as Admin">
                        </div>
                    </form>
                </div>
                <hr>
                <div class="row">
                    <h2>Register New Party  </h2>

                </div>
                <div class="row">

                    <form action="admin.php" method="post" enctype="multipart/form-data"> 
                        <div class="form-group">
                            <label for="partyName">Enter Party Name</label>
                            <input name="partyName" type="text" class="form-control" id="partyName" aria-describedby="partyNameError" placeholder="Enter Party Name">
                            <small id="partyNameError" class="form-text text-muted"><?php echo $errors["partyNameError"] ?></small>
                        </div>
                        <div class="form-group">
                            <label for="partyName">Enter Party Number</label>
                            <input name="partyNumber" type="number" class="form-control" id="partyNumber" aria-describedby="partyNumberError" placeholder="Enter Party Number">
                            <p style="color:red"><?php echo $errors["partyNumberError"] ?></p>
                        </div> 

                        <div class="form-group">
                            <div class="input-group input-grpup-lg">      

                                <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
                                <input name="partyLogo" type="file" class="custom-file-input" id="partyLogo" >
                                <p style="color:red"><?php echo $errors["partyLogoError"] ?></p>
                                <label class="custom-file-label" for="logo">Upload Party Logo</label>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label for="favcolor">Select Party Color:</label>
                            <input name="partyColor" type="color" value="#ff0000"><br>
                        </div> 
                        <p style="color:red"><?php echo $errors["PrimeMessage"]; ?></p>

                        <input name="registerParty" type="text" value="registerParty" hidden="hidden">
                        <input type="submit" class="btn btn-primary btn-lg" value="Register Party"/>
                    </form>
                </div>
                <hr>
                <div class="row">
                    <h1>Registered parties</h1>



                    <div style ="font-size:24px;" class="table-responsive">
                        <table class="table table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <td>Party Logo</td>
                                    <td>Party Number</td>
                                    <td>Party Name</td>
                                    <td>Delete Party</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $partyController = new PartyController();
                                $registeredParties = $partyController->getRegisteredParties();
                                foreach ($registeredParties as $party) {
                                    echo '<tr>'
                                    . '<td style="background-color:' . $party->getColor() . '"><img src="PartyLogos/' . $party->getLogoName() . '" widht="50" height="50"></td>'
                                    . '<td>' . $party->getNumber() . '</td>'
                                    . '<td> <input type="text" name="deleteParty" hidden><input name="partyId" type="number" value="' . $party->getNumber() . '" id="partyId" hidden>' . $party->getName() . '</td>'
                                    . '<td> <input type="button"  data-toggle="modal" data-target="#deleteConfirmationModal" class="btn btn-danger btn-block btn-lg" value="DELETE PARTY" onclick="select_party(this)"/></td>'
                                    . '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- Modal window start -->
                <form action="admin.php" method="POST" >
                    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title text-danger" id="exampleModalLabel"> ATTANTION, DELETION IS UNRECOVERABLE</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <table style ="font-size:36px;" id="confirmationTable" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Logo</td>
                                                    <td>Number</td>
                                                    <td>Name</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <h3 ><center>CONFIRM DELETION</center></h3>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL AND GO BACK</button>
                                        <button type="submit" class="btn btn-danger" >COFIRM MY VOTE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end of modal window --> 

        </div>

    <?php }
    ?>
    <script>
        function select_party(node) {

            var row = node.parentNode.parentNode;
            var clone_row = row.cloneNode(true);
            clone_row.removeChild(clone_row.lastElementChild);
            var confirmation_table = document.getElementById("confirmationTable");
            confirmation_table.deleteRow(0);
            confirmation_table.appendChild(clone_row);
        }

    </script>

</body>
</html>
