<?php

class ParliamentSeatsController {

    function getLeftLine($seats) {


        $seat_length = 45;

        $points = array();
        for ($x = $seats - 1; $x > -1; $x--) {
            $pointX = 500 - $seat_length * $x;
            $pointY = 450 + $x * $x;
            $point = array();
            array_push($point, $pointX);
            array_push($point, $pointY);
            array_push($points, $point);
        }
        return $points;
    }

    function getRightLine($seats) {

        $seat_length = 45;

        $points = array();
        for ($x = 0; $x < $seats; $x++) {
            $pointX = 600 + $seat_length * $x;
            $pointY = 450 + $x * $x;
            $point = array();
            array_push($point, $pointX);
            array_push($point, $pointY);
            array_push($points, $point);
        }
        return $points;
    }

    public function getSeatsLocation() {
        $seats_location = array();
        // for the left side
        for ($x = 0; $x < 8; $x++) {

            $seats;
            if ($x == 0 || $x == 2) {
                $seats = 8;
            }
            if ($x == 1 || $x == 3 || $x == 4) {
                $seats = 9;
            }
            if ($x == 5 || $x == 7) {
                $seats = 11;
            }
            if ($x == 6) {
                $seats = 10;
            }

            $left_line = $this->getLeftLine($seats);
            foreach ($left_line as $l) {
                $seat_location = array();
                $X = $l[0];
                $Y = $l[1];
                $Y = $Y - $x * 45;
                array_push($seat_location, $X);
                array_push($seat_location, $Y);
                array_push($seats_location, $seat_location);
            }
        }
        //for the rigth side
        for ($x = 7; $x > -1; $x --) {

            $seats;
            if ($x == 0 || $x == 2) {
                $seats = 8;
            }
            if ($x == 1 || $x == 3 || $x == 4) {
                $seats = 9;
            }
            if ($x == 5 || $x == 7) {
                $seats = 11;
            }
            if ($x == 6) {
                $seats = 10;
            }

            $right_line = $this->getRightLine($seats);
            foreach ($right_line as $l) {
                $seat_location = array();
                $X = $l[0];
                $Y = $l[1];
                $Y = $Y - $x * 45;
                array_push($seat_location, $X);
                array_push($seat_location, $Y);
                array_push($seats_location, $seat_location);
            }
        }

        return $seats_location;
    }

    public function getMajoritarianSeatsLocation() {
        $majoritarian_seats_location = array();
        $X = 0;
        $Y = 600;
        for ($x = 0; $x < 15; $x++) {
            $X = $X + 75;
            $seat_location = array($X, $Y);
            array_push($majoritarian_seats_location, $seat_location);
        }

        $X = 0;
        $Y = 670;
        for ($x = 15; $x < 30; $x++) {
            $X = $X + 75;
            $seat_location = array($X, $Y);
            array_push($majoritarian_seats_location, $seat_location);
        }
        return $majoritarian_seats_location;
    }

}
