<?php
require_once 'Controllers/AdminController.php';
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
                    <p style="color:red"><?php echo $errors["PrimeMessage"]; ?></p>
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
                                <input name="partyLogo" type="file" class="custom-file-input" id="party_logo" >
                                <p style="color:red"><?php echo $errors["partyLogoError"] ?></p>
                                <label class="custom-file-label" for="logo">Upload Party Logo</label>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label for="favcolor">Select Party Color:</label>
                            <input name="partyColor" type="color" value="#ff0000"><br>
                        </div> 


                        <input name="registerParty" type="text" value="registerParty" hidden="hidden">
                        <input type="submit" class="btn btn-primary btn-lg" value="Register Party"/>
                </div>
            </div>
        </form>
    <?php }
    ?>
</div>
</div>
</body>
</html>
