<?php

require_once 'Model/Party.php';
require_once 'Dao/PartyDao.php';

class AdminController {

    private $errors;

    public function __construct() {
        $this->errors = array("loginMessage" => "",
            "PrimeMessage" => "",
            "partyNameError" => "",
            "partyNumberError" => "",
            "partyLogoError" => "");
    }

    public function isAuthorized() {
        return isset($_SESSION["authorized"]);
    }

    public function authorize() {

        if ($_POST["id"] == "123" && $_POST["password"] == "321") {
            $_SESSION["authorized"] = "authorized";
            header("Location:admin.php");
        } else {
            $this->errors["loginMessage"] = "Wrong credentials. Try again.";
        }
    }

    public function dispatchRequests() {

        if (isset($_POST["authorize"])) {
            $this->authorize();
        }
        if ($this->isAuthorized()) {
            if (isset($_POST["logout"])) {//if request comes for logout
                $this->logout();
            }

            if (isset($_POST["registerParty"])) {
                $this->registerParty();
            }
            if (isset($_POST["deleteParty"])) {
                $this->deleteParty();
            }
        }
    }

    public function dispatchUpdateRequests() {

        if (isset($_POST["authorize"])) {
            $this->authorize();
        }


        if ($this->isAuthorized()) {

            if (isset($_POST["updateParty"])) {
                $party = new Party();
                $partyNumber = $_POST["partyNumber"];
                $partyName = $_POST["partyName"];
                $partyColor = $_POST["partyColor"];

                $party->setNumber($partyNumber);
                $party->setName($partyName);
                $party->setColor($partyColor);
                if ($_POST["newPartyNumber"] != $_POST["partyNumber"]) {
                    //      diladi now i change number, name, and color
                    if ($this->partyNumberValid($_POST["newPartyNumber"])) {

                        $partyLogoName = $_POST["partyLogoName"];
                        $newPartyNumber = $_POST["newPartyNumber"];


                        $party->setLogoName($partyLogoName);
                        $this->updateParty($party, $newPartyNumber);
                        $_SESSION["partyForUpdate"] = $newPartyNumber;
                    }
                } else {
//                    diladi i can change name and color
                    $this->updatePartyNameAndColor($party);
                }
            }
            if (isset($_POST["updatePartyLogo"])) {

                if ($this->partyLogoValid()) {
                    $partyNumber = $_POST["partyId"];
                    $partyLogoName = $_POST["partyLogoName"];
                    $party = new Party();
                    $party->setNumber($partyNumber);
                    $this->deletePartyLogos($party);
                    $this->updatePartyLogo($party);
                    $this->saveSeatLogo($party);
                }
            }

        }
    }

    private function updatePartyLogo($party) {
//maybe i shoud make some function form this

        $partyNumber = $party->getNumber();
        $mime_type = $_FILES['partyLogo']['type'];


        if (!is_uploaded_file($_FILES['partyLogo']['tmp_name'])) {
            $this->errors["partyLogoError"] = 'Problem: Possible file upload attack. Filename: ' . $_FILES['partyLogo']['name'];
        } else {

            $fileExtention = pathinfo($_FILES['partyLogo']['name'], PATHINFO_EXTENSION);
            $fileName = 'PartyLogos/' . $partyNumber . "_logo." . $fileExtention;
            $logoName = $partyNumber . "_logo." . $fileExtention;
            $party->setLogoName($logoName);


            if (!move_uploaded_file($_FILES['partyLogo']['tmp_name'], $fileName)) {//function move_uploaded_file saves into the file
                $this->errors["partyLogoError"] = 'Problem: Could not move file to destination directory';
            } else {
                $this->saveSeatLogo($party);

//inserting party to database
                $partyDao = new PartyDao();

                $partyDao->updatePartyLogo($party);
            }
        }header("Location:updateParty.php");
    }

    private function updatePartyNameAndColor($party) {
        $partyDao = new PartyDao();
        $partyDao->updatePartyNameAndColor($party);
    }

    private function updateParty($party, $newPartyNumber) {

        $newLogoName = $this->updatePartyLogoName($party, $newPartyNumber);

        $party->setLogoName($newLogoName);
        $partyDao = new PartyDao();
        $partyDao->updateParty($party, $newPartyNumber);
    }

    private function updatePartyLogoName($party, $newPartyNumber) {
        $logoName = $party->getLogoName();
        $arrayedLogoName = explode("_", $logoName);
        $newLogoName = $newPartyNumber . "_" . $arrayedLogoName[1];
        rename("PartyLogos/" . $logoName, "PartyLogos/" . $newLogoName);
        $seatLogoName = $party->getNumber() . "_seatLogo.png";
        $newSeatLogoName = $newPartyNumber . "_seatLogo.png";
        rename("PartyLogos/" . $seatLogoName, "PartyLogos/" . $newSeatLogoName);

        return $newLogoName;
    }

    public function logout() {
        unset($_POST["id"]);
        unset($_SESSION["authorized"]);
        header("Location:admin.php");
    }

    private function registerParty() {
//mysql_real_escape_string --need to learn php sanitation
        $partyName = $_POST['partyName'];
        $partyNumber = $_POST['partyNumber'];
        $partyColor = $_POST['partyColor'];
        $partyLogoName = $_FILES['partyLogo']['name'];

        if ($this->partyNumberValid($partyNumber) & $this->partyLogoValid()) {

            $party = new Party();
            $party->setName($partyName);
            $party->setNumber($partyNumber);
            $party->setColor($partyColor);

            $mime_type = $_FILES['partyLogo']['type'];


            if (!is_uploaded_file($_FILES['partyLogo']['tmp_name'])) {
                $this->errors["partyLogoError"] = 'Problem: Possible file upload attack. Filename: ' . $_FILES['partyLogo']['name'];
            } else {

                $fileExtention = pathinfo($_FILES['partyLogo']['name'], PATHINFO_EXTENSION);
                $fileName = 'PartyLogos/' . $party->getNumber() . "_logo." . $fileExtention;
                $logoName = $party->getNumber() . "_logo." . $fileExtention;
                $party->setLogoName($logoName);


                if (!move_uploaded_file($_FILES['partyLogo']['tmp_name'], $fileName)) {//function move_uploaded_file saves into the file
                    $this->errors["partyLogoError"] = 'Problem: Could not move file to destination directory';
                } else {
                    $this->saveSeatLogo($party);

//inserting party to database
                    $partyDao = new PartyDao();

                    $partyDao->registerParty($party);
                }
            }
            header("Location:admin.php");
        }
    }

    private function saveSeatLogo($party) {
        $filename = 'PartyLogos/' . $party->getLogoName();
        $image_s = imagecreatefromstring(file_get_contents($filename));
        $width = imagesx($image_s);
        $height = imagesy($image_s);
        $newwidth = 40;
        $newheight = 40;
        $image = imagecreatetruecolor($newwidth, $newheight);
// imagealphablending($image, true);
        imagecopyresampled($image, $image_s, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
//create masking
        $mask = imagecreatetruecolor($newwidth, $newheight);
        $transparent = imagecolorallocate($mask, 255, 0, 0);
        imagecolortransparent($mask, $transparent);
        imagefilledellipse($mask, $newwidth / 2, $newheight / 2, $newwidth, $newheight, $transparent);
        $red = imagecolorallocate($mask, 0, 0, 0);
        imagecopymerge($image, $mask, 0, 0, 0, 0, $newwidth, $newheight, 100);
        imagecolortransparent($image, $red);
        imagefill($image, 0, 0, $red);
        Header('Content-type:image/png');

        imagepng($image, 'PartyLogos/' . $party->getNumber() . '_seatLogo.png');
        imagedestroy($image);
    }

    public function getErrors() {
        return $this->errors;
    }

    private function partyNumberValid($partyNumber) {
        $partyDao = new PartyDao();
        if ($partyDao->partyNumberExists($partyNumber)) {
            $this->errors["partyNumberError"] = "Party with the number " . $partyNumber . " already exists. Choose another one";

            return false;
        } else {
            return true;
        }
    }

    private function partyLogoValid() {
        $partyLogoValid = false;
        $mime_type = $_FILES['partyLogo']['type'];
        if ($mime_type === 'image/jpeg' || $mime_type === 'image/png' || $mime_type === 'image/gif') {
            $partyLogoValid = true;
        } else {
            $this->errors["partyLogoError"] = " ΜΟΝΟ JPEG(JPG), PNG και GIF ΑΡΧΕΙΑ ΕΠΙΤΡΕΠΟΝΤΑΙ";
        }

        if ($_FILES['partyLogo']['error'] > 0) {
            $this->errors["PrimeMessage"] = "Party registration unseccussful";
            switch ($_FILES['partyLogo']['error']) {
                case 1: $this->errors["partyLogoError"] = 'File exceeded upload_max_filesize';
                    break;
                case 2: $this->errors["partyLogoError"] = 'File exceeded max_file_size';
                    break;
                case 3: $this->errors["partyLogoError"] = 'File only partially uploaded';
                    break;
                case 4: $this->errors["partyLogoError"] = 'No file uploaded';
                    break;
                case 6: $this->errors["partyLogoError"] = 'Cannot upload file: No temp directory specified';
                    break;
                case 7: $this->errors["partyLogoError"] = 'Upload failed: Cannot write to disk';
                    break;
            }
        }

        return $partyLogoValid;
    }

    public function deleteParty() {
        $partyId = $_POST["partyId"];
        $partyLogoName = $_POST["logoName"];
        $party = new Party();
        $party->setNumber($partyId);
        $party->setLogoName($partyLogoName);

        $this->deletePartyFromDataBase($party);

        $this->deletePartyLogos($party);
    }

    private function deletePartyFromDataBase($party) {
        //delete from database
        $partyDao = new PartyDao();
        $partyDao->deleteParty($party);
    }

    private function deletePartyLogos($party) {
        //now delete logos from folder
        $partyLogoName = $party->getLogoName();
        $partyId = $party->getNumber();
        $partyLogoPath = "PartyLogos/" . $partyLogoName;
        $seatLogoPath = "PartyLogos/" . $partyId . "_seatLogo.png";
        if (@unlink($partyLogoPath)) {
            // 
        } else {
            //
        }
        if (@unlink($seatLogoPath)) {
            //
        } else {
            //
        }
    }

}
