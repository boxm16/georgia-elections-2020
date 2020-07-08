<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'Controllers/ResultsController.php';
        require_once 'Controllers/ParliamentSeatsController.php';
        require_once 'Model/Party.php';


        $parliamentSeatsController = new ParliamentSeatsController();
        $seatsLocation = $parliamentSeatsController->getSeatsLocation();

        $resultsController = new ResultsController();
       
        
        $qualified_parties = $resultsController->getQualifiedParties();
       
        $display_width = 1200;
        $display_height = 800;
        $rectancle_width = $display_width - 1;
        $id_index = 0;
        ?>

        <div class='row'><center> <a href='elections_seats.php'>RESET </a></center></div>
                <?php
                echo ' <svg width = "' . $display_width . '" height = "' . $display_height . '" version = "1.1" xmlns = "http://www.w3.org/2000/svg" xmlns:xlink = "http://www.w3.org/1999/xlink">';
                //first rending majority seats(needed for government support)
                for ($x = 0; $x < 76; $x++) {
                    $seat_location = $seatsLocation[$x];
                    $XA = $seat_location[0] + 20;
                    $YA = $seat_location[1] + 20;
                    echo ' <circle cx="' . $XA . '" cy="' . $YA . '" r="20" stroke="black" stroke-width="3"  fill="black"  fill-opacity="0.2" />';
                }
                //now rending seats neede for constitutianl majority
                for ($x = 76; $x < 113; $x++) {
                    $seat_location = $seatsLocation[$x];
                    $XA = $seat_location[0] + 20;
                    $YA = $seat_location[1] + 20;
                    echo ' <circle cx="' . $XA . '" cy="' . $YA . '" r="20" stroke="black" stroke-width="1" fill="grey"  fill-opacity="0.2" />';
                }
                for ($x = 113; $x < 150; $x++) {
                    $seat_location = $seatsLocation[$x];
                    $XA = $seat_location[0] + 20;
                    $YA = $seat_location[1] + 20;
                    echo ' <circle cx="' . $XA . '" cy="' . $YA . '" r="20" stroke="black" stroke-width="1"  fill="grey"  fill-opacity="0" />';
                }


//proportional mandates
                echo ' <rect x="1" y="1" width="' . $rectancle_width . '" height="100" fill="white" stroke-width="1" stroke="black" />';
                echo '<text nmae="text" font-weight="bold" x="300" y="15">პროპორციული მანდატები.  მთავეობის შესაქმელად დააწკაპუნე პარტიის ლოგოზე</text>';

                $party_space = $rectancle_width / count($qualified_parties);
                $party_space_center = $party_space / 2;

                $party_space_index = 0;
                $seats_location_index = 0;
                $parties_base_location = array();
                foreach ($qualified_parties as $party) {
                    $party_circle_start_location_X = $party_space_index * $party_space + $party_space_center;
                    $party_circle_start_location_Y = 50;
                    $party_image_start_location_X = $party_circle_start_location_X - 20;
                    $party_image_start_location_Y = $party_circle_start_location_Y - 20;

                    $party_text_start_location = $party_circle_start_location_X - 10;
                    $location = array("party_" . $party->getNumber(), $party_circle_start_location_X, $party_circle_start_location_Y);
                    array_push($parties_base_location, $location);
                    $ghost_mandates = $party->getGhostMandates();
                    $mandates = $party->getMandates();
                    //drawing party base
                    echo '<circle name="base" cx="' . $party_circle_start_location_X . '" cy="' . $party_circle_start_location_Y . '" r="20" stroke="' . $party->getColor() . '" stroke-width="3" fill="white"  fill-opacity="1" >'
                    . '</circle>';
                    //now filling the base with ghost mandates
                    while ($ghost_mandates > 0) {
                        echo '<circle name="ghost_circle" cx="' . $party_circle_start_location_X . '" cy="' . $party_circle_start_location_Y . '" r="20" stroke="' . $party->getColor() . '" stroke-width="3" fill="' . $party->getColor() . '"  fill-opacity="0.3" id="1" class="' . $party->getNumber() . ' ghost_mandate base">'
                        . '</circle>';
                        $ghost_mandates--;
                    }

                    // and now filling the base with mandates
                    while ($mandates > 0) {
                        $id_index_trigger = $id_index + 3;
                        echo ' <image  name="image" href="PartyLogos/' . $party->getNumber() . '_seatLogo.png" x="' . $party_image_start_location_X . '" y="' . $party_image_start_location_Y . '" height="40px" width="40px"   class="party_' . $party->getNumber() . ' proportional base">'
                        . '<animate
        name="image_X"
        attributeName = "x"
        from = "' . $party_image_start_location_X . '"
        to = "500 "
        dur = "1s"
        begin = ""
        fill = "freeze"
        class="party_' . $party->getNumber() . ' proportional base"
        id = ""/>';

                        echo '<animate 
            name="image_Y"        
            attributeName="y"
            from="' . $party_image_start_location_Y . '"
            to="600 " 
            dur="1s"
            begin=""
            fill="freeze" 
             class="party_' . $party->getNumber() . '  proportional base"
            id = ""/>'
                        . '</image>';


                        echo '<circle name="clickable_circle" cx="' . $party_circle_start_location_X . '" cy="' . $party_circle_start_location_Y . '" r="20" stroke="' . $party->getColor() . '" stroke-width="3" fill="' . $party->getColor() . '"  fill-opacity="0"  class="party_' . $party->getNumber() . ' proportional base" onclick="placeMandates(this)" >'
                        . '<animate 
            name="circle_X"
            attributeName="cx"
            from="' . $party_circle_start_location_X . '"
            to="500" 
            dur="1s"
            begin=""
            fill="freeze" 
            class="party_' . $party->getNumber() . ' proportional base"
            id = ""/>';

                        echo '<animate 
            name="circle_Y"
            attributeName="cy"
            from="' . $party_circle_start_location_Y . '"
            to="600" 
            dur="1s"
            begin=""
            fill="freeze" 
             class="party_' . $party->getNumber() . ' proportional base"
            id = ""/>'
                        . '</circle>';

                        $mandates--;
                    }

                    echo '<text names="text" font-weight="bold" x="' . $party_text_start_location . '" y="90">' . $party->getMandates()  . '</text>';
                    $party_space_index++;
                }
//majoritarian olqebi
                echo ' <rect x="1" y="550" width="' . $rectancle_width . '" height="150" fill="white" stroke-width="1" stroke="black" />';
                echo '<text name="text" font-weight="bold" x="500" y="540">მაჟორიტარული მანდატები</text>';

                $majoritarian_seats_location = $parliamentSeatsController->getMajoritarianSeatsLocation();

                foreach ($majoritarian_seats_location as $seat_location) {
                    $X = $seat_location[0];
                    $Y = $seat_location[1];
                    echo ' <circle cx="' . $X . '" cy="' . $Y . '" r="20" stroke="black" stroke-width="1"  fill="grey"  fill-opacity="0" />';
                }


                $majoritarians = $resultsController->getMajoritarianMandates();

                $majoritarian_seats_location_index = 0;
   
                for ($x = 0; $x < count($majoritarians); $x++) {
                    $seat_location = $majoritarian_seats_location[$majoritarian_seats_location_index];
                    $X = $seat_location[0];
                    $Y = $seat_location[1];
                    $XA = $seat_location[0] - 20;
                    $YA = $seat_location[1] - 20;
                    $majoritarian = $majoritarians[$x];

      
                    echo ' <image  name="image" href="PartyLogos/' . $majoritarian->getNumber() . '_seatLogo.png" x="' . $XA . '" y="' . $YA . '" height="40px" width="40px"   class="party_' . $majoritarian->getNumber() . ' majoritarian base ' . $x . '">'
                    . '<animate
        name="image_X"
        attributeName = "x"
        from = "' . $party_image_start_location_X . '"
        to = "500 "
        dur = "1s"
        begin = ""
        fill = "freeze"
        class="party_' . $majoritarian->getNumber() . ' majoritarian base ' . $x . '"
        id = ""/>';

                    echo '<animate 
            name="image_Y"        
            attributeName="y"
            from="' . $party_image_start_location_Y . '"
            to="600 " 
            dur="1s"
            begin=""
            fill="freeze" 
            class="party_' . $majoritarian->getNumber() . ' majoritarian base ' . $x . '"
            id = ""/>'
                    . '</image>';


                    echo '<circle name="clickable_circle" cx="' . $X . '" cy="' . $Y . '" r="20" stroke="' . $majoritarian->getColor() . '" stroke-width="3" fill="' . $majoritarian->getColor() . '"  fill-opacity="0"  class="party_' . $majoritarian->getNumber() . ' majoritarian base ' . $x . '" onclick="placeMandates(this)" >'
                    . '<animate 
            name="circle_X"
            attributeName="cx"
            from="' . $party_circle_start_location_X . '"
            to="500" 
            dur="1s"
            begin=""
            fill="freeze" 
            class="party_' . $majoritarian->getNumber() . ' majoritarian base ' . $x . '"
            id = ""/>';

                    echo '<animate 
            name="circle_Y"
            attributeName="cy"
            from="' . $party_circle_start_location_Y . '"
            to="600" 
            dur="1s"
            begin=""
            fill="freeze" 
             class="party_' . $majoritarian->getNumber() . ' majoritarian base ' . $x . '"
            id = ""/>'
                    . '</circle>';
                    $majoritarian_seats_location_index++;
                }
                echo '<text name="text" font-weight="bold" x="400" y="740">მთავეობის შესაქმელად დააწკაპუნე პარტიის ლოგოზე</text>';

                echo '</svg>';
                ?>
        <script>
            var parties_base_location = <?php echo json_encode($parties_base_location); ?>;
            var seats_location = <?php echo json_encode($seatsLocation); ?>;
            var majoritarian_base_location =<?php echo json_encode($majoritarian_seats_location); ?>;


            var seats_location_index = 0;
            function placeMandates(element) {
                var classes = element.getAttribute("class");
                var classesArray = classes.split(" ");
                var partyNumber = classesArray[0];

                //now find Party Base Locaton(coordinates)
                var partyBaseLocation;
                for (x = 0; x < parties_base_location.length; x++) {
                    partyBaseLocation = parties_base_location[x];
                    if (partyBaseLocation[0] == partyNumber) {
                        break;
                    }

                }
                var ghostMandatesElements = document.getElementsByClassName(partyNumber + " ghost_mandate");
                //first rending majoritarian   
                var majoritarianMandatesElements = document.getElementsByClassName(partyNumber + " majoritarian");

                var mandateType = "majoritarian";
                var mandateLocation = "seat";

                var switch_index = 0;

                for (x = 0; x < majoritarianMandatesElements.length; x++) {
                    var element = majoritarianMandatesElements[x];
                    var seat_location = seats_location[seats_location_index];



                    var elementName = element.getAttribute("name");
                    var elementClasses = element.getAttribute("class");
                    var classesArray = elementClasses.split(" ");
                    var majoritarianBaseIndex = classesArray[3];
                    var newClassName = partyNumber + ' ' + mandateType + ' ' + mandateLocation + ' ' + majoritarianBaseIndex;

                    element.setAttribute("class", newClassName);//this i`ll use later
                    switch (elementName) {
                        case "image":

                            break;
                        case "image_X":

                            element.setAttribute("from", majoritarian_base_location[majoritarianBaseIndex][0] - 20);
                            element.setAttribute("to", seat_location[0]);
                            break;
                        case "image_Y":
                            element.setAttribute("from", majoritarian_base_location[majoritarianBaseIndex][1] - 20);
                            element.setAttribute("to", seat_location[1]);
                            break;
                        case "clickable_circle":
                            element.setAttribute("onclick", "returnMandatesToBase(this)");
                            break;
                        case "circle_X":
                            element.setAttribute("from", majoritarian_base_location[majoritarianBaseIndex][0]);
                            element.setAttribute("to", seat_location[0] + 20);
                            break;
                        case "circle_Y":
                            element.setAttribute("from", majoritarian_base_location[majoritarianBaseIndex][1]);
                            element.setAttribute("to", seat_location[1] + 20);
                            break;

                    }
                    switch_index++;
                    if (switch_index > 5) {
                        seats_location_index++;
                        switch_index = 0;
                    }


                }
                //now start those elements to move
                for (x = 0; x < majoritarianMandatesElements.length; x++) {
                    var element = majoritarianMandatesElements[x];
                    var beginAttribute = element.getAttribute("begin");
                    if (beginAttribute != null) {
                        majoritarianMandatesElements[x].beginElement();
                    }
                }

//now proportional

                var proportionalMandatesElements = document.getElementsByClassName(partyNumber + " proportional");
                var mandateType = "proportional";
                var mandateLocation = "seat";
                var newClassName = partyNumber + ' ' + mandateType + ' ' + mandateLocation;
                var switch_index = 0;
                for (x = 0; x < proportionalMandatesElements.length; x++) {
                    var seat_location = seats_location[seats_location_index];
                    var element = proportionalMandatesElements[x];
                    var elementName = element.getAttribute("name");
                    element.setAttribute("class", newClassName);//this i`ll use later
                    switch (elementName) {
                        case "image":

                            break;
                        case "image_X":
                            element.setAttribute("from", partyBaseLocation[1] - 20);
                            element.setAttribute("to", seat_location[0]);
                            break;
                        case "image_Y":
                            element.setAttribute("from", partyBaseLocation[2] - 20);
                            element.setAttribute("to", seat_location[1]);
                            break;
                        case "clickable_circle":
                            element.setAttribute("onclick", "returnMandatesToBase(this)");
                            break;
                        case "circle_X":
                            element.setAttribute("from", partyBaseLocation[1]);
                            element.setAttribute("to", seat_location[0] + 20);
                            break;
                        case "circle_Y":
                            element.setAttribute("from", partyBaseLocation[2]);
                            element.setAttribute("to", seat_location[1] + 20);
                            break;

                    }
                    switch_index++;
                    if (switch_index > 5) {
                        seats_location_index++;
                        switch_index = 0;
                    }


                }
                //now start those elements to move
                for (x = 0; x < proportionalMandatesElements.length; x++) {
                    var element = proportionalMandatesElements[x];
                    var beginAttribute = element.getAttribute("begin");
                    if (beginAttribute != null) {
                        proportionalMandatesElements[x].beginElement();
                    }
                }



                for (x = 0; x < ghostMandatesElements.length; x++) {
                    //  console.log(ghostMandatesElements[x]);
                }

            }


            function  returnMandatesToBase(element) {



                var classes = element.getAttribute("class");
                var classesArray = classes.split(" ");
                var partyNumber = classesArray[0];


                var ghostMandatesElements = document.getElementsByClassName(partyNumber + " ghost_mandate");

                for (x = 0; x < ghostMandatesElements.length; x++) {
                    // console.log(ghostMandatesElements[x]);
                }
                //first return majoritarian   
                var majoritarianMandatesElements = document.getElementsByClassName(partyNumber + " majoritarian");

                var mandateType = "majoritarian";
                var mandateLocation = "base";

                var switch_index = 0;

                for (x = 0; x < majoritarianMandatesElements.length; x++) {
                    var element = majoritarianMandatesElements[x];
                    var classes = element.getAttribute("class");
                    var classesArray = classes.split(" ");
                    var majoritarianBaseIndex = classesArray[3];


                    var newClassName = partyNumber + ' ' + mandateType + ' ' + mandateLocation + ' ' + majoritarianBaseIndex;
                    var elementName = element.getAttribute("name");
                    var elementClasses = element.getAttribute("class");
                    var classesArray = elementClasses.split(" ");
                    var majoritarianBaseIndex = classesArray[3];

                    element.setAttribute("class", newClassName);//this i`ll use later
                    var temp;
                    switch (elementName) {
                        case "image":

                            break;
                        case "image_X":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", majoritarian_base_location[majoritarianBaseIndex][0] - 20);
                            break;
                        case "image_Y":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", majoritarian_base_location[majoritarianBaseIndex][1] - 20);
                            break;
                        case "clickable_circle":
                            element.setAttribute("onclick", "placeMandates(this)");
                            break;
                        case "circle_X":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", majoritarian_base_location[majoritarianBaseIndex][0]);
                            break;
                        case "circle_Y":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", majoritarian_base_location[majoritarianBaseIndex][1]);
                            break;

                    }
                    switch_index++;
                    if (switch_index > 5) {
                        seats_location_index++;
                        switch_index = 0;
                    }


                }
                for (x = 0; x < majoritarianMandatesElements.length; x++) {
                    var element = majoritarianMandatesElements[x];
                    var beginAttribute = element.getAttribute("begin");
                    if (beginAttribute != null) {
                        majoritarianMandatesElements[x].beginElement();
                    }
                }
                //now proportional
                //now find Party Base Locaton(coordinates)
                var partyBaseLocation;

                var mandateType = "proportional";
                var mandateLocation = "base";
                var newClassName = partyNumber + ' ' + mandateType + ' ' + mandateLocation;
                for (x = 0; x < parties_base_location.length; x++) {
                    partyBaseLocation = parties_base_location[x];
                    if (partyBaseLocation[0] == partyNumber) {

                        break;
                    }

                }
                var proportionalMandatesElements = document.getElementsByClassName(partyNumber + " proportional");
                var switch_index = 0;
                for (x = 0; x < proportionalMandatesElements.length; x++) {
                    var element = proportionalMandatesElements[x];
                    var elementName = element.getAttribute("name");
                    element.setAttribute("class", newClassName);//this i`ll use later

                    var temp;
                    switch (elementName) {
                        case "image":
                            break;
                        case "image_X":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", partyBaseLocation[1] - 20);
                            break;
                        case "image_Y":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", partyBaseLocation[2] - 20);
                            break;
                        case "clickable_circle":
                            element.setAttribute("onclick", "placeMandates(this)");
                            break;
                        case "circle_X":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", partyBaseLocation[1]);
                            break;
                        case "circle_Y":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", partyBaseLocation[2]);
                            break;

                    }
                    switch_index++;
                    if (switch_index > 5) {
                        // seats_location_index--;
                        switch_index = 0;
                    }


                }
                //now start those elements to move
                for (x = 0; x < proportionalMandatesElements.length; x++) {
                    var element = proportionalMandatesElements[x];
                    var beginAttribute = element.getAttribute("begin");
                    if (beginAttribute != null) {
                        proportionalMandatesElements[x].beginElement();
                    }
                }

                reseatSeatedMandates();
            }

            function reerangeSamePartyMandates(allMandates) {
                let set = new Set();
                let partyElements = [];
                for (x = 0; x < allMandates.length; x++) {

                    let element = allMandates[x];
                    let classes = element.getAttribute("class");
                    let classesArray = classes.split(" ");
                    let partyNumber = classesArray[0];

                    if (!set.has(partyNumber)) {
                        set.add(partyNumber);
                    }
                }

                for (let partyNumber of set) {
                    var party_elements = document.getElementsByClassName(partyNumber + " seat");
                    var arrayedElements = Array.from(party_elements);
                    partyElements = partyElements.concat(arrayedElements);
                }
                //   console.log(partyElements);
                return partyElements;
            }


            function reseatSeatedMandates() {
                var seatedMandates = document.getElementsByClassName("seat");

                seatedMandates = reerangeSamePartyMandates(seatedMandates);

                var switch_index = 0;
                seats_location_index = 0;
                for (x = 0; x < seatedMandates.length; x++) {
                    var seat_location = seats_location[seats_location_index];
                    var element = seatedMandates[x];
                    var elementName = element.getAttribute("name");
                    var temp;
                    switch (elementName) {
                        case "image":

                            break;
                        case "image_X":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", seat_location[0]);
                            break;
                        case "image_Y":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", seat_location[1]);
                            break;
                        case "clickable_circle":
                            element.setAttribute("onclick", "returnMandatesToBase(this)");
                            break;
                        case "circle_X":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", seat_location[0] + 20);
                            break;
                        case "circle_Y":
                            temp = element.getAttribute("to");
                            element.setAttribute("from", temp);
                            element.setAttribute("to", seat_location[1] + 20);
                            break;

                    }
                    switch_index++;
                    if (switch_index > 5) {
                        seats_location_index++;
                        switch_index = 0;
                    }


                }
                //now start those elements to move
                for (x = 0; x < seatedMandates.length; x++) {
                    var element = seatedMandates[x];
                    var beginAttribute = element.getAttribute("begin");
                    if (beginAttribute != null) {
                        seatedMandates[x].beginElement();
                    }
                }
            }
        </script>
    </body>
</html>
