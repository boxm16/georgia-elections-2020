<?php
require_once 'Controllers/AdminController.php';
require_once 'Controllers/PartyController.php';
session_start();
$adminController = new AdminController();
$authorized = $adminController->isAuthorized();
if (isset($_POST["goPartyUpdate"]) && isset($_POST["partyId"])) {
    $partyForUpdate = $_POST["partyId"];
    $_SESSION["partyForUpdate"] = $partyForUpdate;
//   i need this variable in session, to cary around
}
$adminController->dispatchUpdateRequests();
$errors = $adminController->getErrors();
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
                $partyController = new PartyController();
                $partyForUpdate = $partyController->getPartyForUpdate();
                ?> 
                <h2>Update Party</h2>
                <hr>
                <div class="row">
                    <div class="col-lg-8">
                        <h1>Registered party</h1>
                    </div>
                    <div class="col col-sm-4"> 
                        <a href="admin.php"> Go Back To Main Page </a>
                    </div>
                </div>
                <div class="row">
                    <div style ="font-size:24px;" class="table-responsive">
                        <table class="table table-bordered  table-hover">
                            <thead>
                                <tr>
                                    <td>Party Logo</td>
                                    <td>Party Number</td>
                                    <td>Party Name</td>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                echo '<tr>'
                                . '<td style="background-color:' . $partyForUpdate->getColor() . '"><img src="PartyLogos/' . $partyForUpdate->getLogoName() . '" widht="50" height="50"></td>'
                                . '<td>' . $partyForUpdate->getNumber() . '</td>'
                                . '<td>' . $partyForUpdate->getName() . '</td>'
                                . '</tr>';
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-lg-4">
                        <br>
                        <form action="updateParty.php" method="post" enctype="multipart/form-data"> 



                            <div class="form-group">
                                <div class="input-group input-grpup-lg">      

                                    <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
                                    <input name="partyLogo" type="file" class="custom-file-input" id="partyLogo" >
                                    <p style="color:red"><?php echo $errors["partyLogoError"] ?></p>
                                    <label class="custom-file-label" for="logo">Upload Party Logo</label>
                                </div>
                            </div> 

                <!-- <p style="color:red"><?php echo $errors["PrimeMessage"]; ?></p> -->
                            <input type="hidden" name="updatePartyLogo">
                            <input type="hidden" name="partyLogoName" value="<?php echo $partyForUpdate->getLogoName() ?>">
                            <input type="hidden" name="partyId" value="<?php echo $partyForUpdate->getNumber() ?>">
                            <input type="submit" class="btn btn-primary btn-lg" value="Save New Logo"/>
                        </form>
                    </div>
                    <div class="col col-lg-8">
                        <form action="updateParty.php" method="post" enctype="multipart/form-data"> 
                            <div class="form-group">
                                <label for="partyName">Change Party Name</label>
                                <input name="partyName" type="text" value="<?php echo $partyForUpdate->getName(); ?>" class="form-control font-weight-bold" id="partyName" aria-describedby="partyNameError" >
                                <small id="partyNameError" class="form-text text-muted"><?php echo $errors["partyNameError"] ?></small> 
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="partyNumber">Change Party Number</label>
                                <input name="partyNumber" type="hidden" value="<?php echo $partyForUpdate->getNumber(); ?>">
                                <input name="newPartyNumber" type="number" value="<?php echo $partyForUpdate->getNumber(); ?>" class="form-control font-weight-bold" id="partyNumber" aria-describedby="partyNumberError">
                                <p style="color:red"><?php echo $errors["partyNumberError"] ?></p>
                            </div> 
                            <hr>
                            <div class="form-group">
                                <label for="favcolor">Change Party Color:</label>
                                <input name="partyColor" type="color" value="<?php echo $partyForUpdate->getColor(); ?>"><br>
                            </div> 
                            <hr>
                            <input type ="hidden" name="partyLogoName" value="<?php echo $partyForUpdate->getLogoName(); ?>">
                            <input type="hidden" name="updateParty">
                           <!-- <p style="color:red"><?php echo $errors["PrimeMessage"]; ?></p> -->
                            <input type="submit" class="btn btn-primary btn-lg" value="Save Update"/>
                        </form>
                    </div>

                </div>  
                <hr>
                <?php
            } else {
                echo 'You are not authorized for this page. Login in or go to start page.'
                . '<br><a href="index.php">CLick to go to start page</a>';
            }
            ?>
        </div>

    </body>
</html>
