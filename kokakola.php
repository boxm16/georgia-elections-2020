<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'Controllers/DistrictController.php';
        $districtController = new DistrictController();

        $districts = $districtController->getDistricts();
        foreach ($districts as $district){
            var_dump($district);
            echo "<br>";
        }
        ?>

    </body>
</html>
