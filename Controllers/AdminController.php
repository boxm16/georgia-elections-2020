<?php

require_once 'Model/Party.php';
require_once 'Dao/PartyDao.php';

class AdminController {

    private $errors;

    public function __construct() {
        $this->errors = array("PrimeMessage" => "",
            "partyNameError" => "",
            "partyNumberError" => "",
            "partyLogoError" => "");
    }

    public function isAuthorized() {

        if (isset($_POST["id"])) {
            if ($_POST["id"] == "123" && $_POST["password"] == "321") {
                $_SESSION["authorized"] = "authorized";
            }
        }
        return isset($_SESSION["authorized"]);
    }

    public function dispatchRequests() {

        if (isset($_POST["logout"])) {//if request comes for logout
            $this->logout();
        }

        if (isset($_POST["registerParty"])) {
            $this->registerParty();
        }
    }

    public function logout() {
        unset($_POST["id"]);
        unset($_SESSION["authorized"]);
        header("Location:admin.php");
    }

    private function registerParty() {

        $party_name = $_POST['partyName'];
        $party_number = $_POST['partyNumber'];
        $party_color = $_POST['partyColor'];
        $party_logo_name = $_FILES['partyLogo']['name'];

        if ($this->partyNumberValid() & $this->partyLogoValid()) {
            $party = new Party();
            $party->setName($party_name);
            $party->setNumber($party_number);
            $party->setColor($party_color);

            $mime_type = $_FILES['partyLogo']['type'];
            $upfile = 'party_logos/' . $party_number . "." . $mime_type;

            if (!is_uploaded_file($_FILES['partyLogo']['tmp_name'])) {
                $this->errors["partyLogoError"] = 'Problem: Possible file upload attack. Filename: ' . $_FILES['partyLogo']['name'];
            } else {
                //inserting party to database

                $partyDao = new PartyDao();
                $partyDao->registerParty($party);

                $fileExtention = pathinfo($_FILES['partyLogo']['name'], PATHINFO_EXTENSION);
                $fileName = 'partyLogos/' . $party->getNumber() . "_logo." . $fileExtention;
                $logoName = $party->getNumber() . "_logo." . $fileExtention;
                $party->setLogoName($logoName);
                if (!move_uploaded_file($_FILES['partyLogo']['tmp_name'], $fileName)) {//function move_uploaded_file saves into the file
                    $this->errors["partyLogoError"] = 'Problem: Could not move file to destination directory';
                } else {
                    $this->saveSeatLogo($fileName);
                }
            }
            header("Location:admin.php");
        }
    }

    private function saveSeatLogo($fileName) {
        $filename = 'partyLogos/' . $party->getLogoName();
        
        $image_s = imagecreatefromstring(file_get_contents($fileName));
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

        imagepng($image, 'partyLogos/' . $party->getNumber() . '.png');
        imagedestroy($image);
    }

    public function getErrors() {
        return $this->errors;
    }

    private function partyNumberValid() {

        return true;
    }

    private function partyLogoValid() {
        $partyLogoValid = false;
        $mime_type = $_FILES['partyLogo']['type'];
        if ($mime_type === 'image/jpeg' || $mime_type === 'image/png' || $mime_type === 'image/gif') {
            $partyLogoValid = true;
        } else {
            $this->errors["partyLogoError"] = " ΜΟΝΟ JPEG(JPG), PNG και GIF ΑΡΧΕΙΑ ΕΠΙΤΡΕΠΟΝΤΑΙ";
            // exit;
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
            //         exit;
        }

        return $partyLogoValid;
    }

}