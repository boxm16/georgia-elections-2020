<?php
require_once 'Controllers/PartyController.php';
$partyController = new PartyController();
$registeredParties = $partyController->getRegisteredParties();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>პროპორციული არჩევნების ბიულეტენი</title>
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
            <div class="row">
                <h1>Registered parties</h1>



                <div style ="font-size:24px;" class="table-responsive">
                    <table class="table table-bordered  table-hover">
                        <thead>
                            <tr>
                                <td>ლოგო</td>
                                <td>ნომერი</td>
                                <td>დასახელება</td>
                                <td>ხმის მიცემა</td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($registeredParties as $party) {
                                echo '<tr>'
                                . '<td style="background-color:' . $party->getColor() . '"><img src="PartyLogos/' . $party->getLogoName() . '" widht="50" height="50"></td>'
                                . '<td>' . $party->getNumber() . '</td>'
                                . '<td> <input type="hidden" name="voteParty" ><input name="partyId" type="hidden" value="' . $party->getNumber() . '" id="partyId" hidden>' . $party->getName() . '</td>'
                                . '<td> <input name="partyId" type="hidden" value="' . $party->getNumber() . '" id="partyId" hidden><input type="button"  data-toggle="modal" data-target="#deleteConfirmationModal" class="btn btn-primary btn-block btn-lg" value="მიეცი ხმა" onclick="select_party(this)"/></td>'
                                . '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal window start -->
        <form action="majoritarianDistrictsMap.php" method="POST" >
            <div class="modal fade " id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-success" id="exampleModalLabel"> დაადასტურეთ თქვენი არჩევანი</h1>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <center><h2 class="modal-title text-success" id="exampleModalLabel"> თქვენ ხმას აძლევთ</h2></center>
                                <br>

                                <table style ="font-size:36px;" id="confirmationTable" class="table table-bordered">
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">გაუქმება და უკან დაბრუნება</button>
                                <button type="submit" class="btn btn-primary" >ვადასტურებ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- end of modal window --> 

        <!-- start of hidden form for party update -->
        <form action="updateParty.php" method="POST">
            <table id="updatePartyTable" hidden>

            </table>
            <input type="text" name="goCandidateUpdate" hidden>
            <button type="submit" id="updatePartyButton" hidden></button>
        </form>
        <!-- end of hidden form for party update -->
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
