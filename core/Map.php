<?php

class Map
{
    public $StartX = -1;
    public $StartY = -1;
    public $DestX = -1;
    public $DestY = -1;

    private $mapArr = array();

    function __construct($chosenMap)
    {
        $rows = explode("\n", $chosenMap);
        $this->mapArr = array();

        $rowInx = 0;
        foreach ($rows as $val) {
            if (!isset($val) || $val == "") continue;
            $row = preg_replace("/\s\s+/", " ", $val);
            if (!isset($row) || $row == "") continue;
            $chars = explode(" ", $row);

            $rowArr = array();
            $colInx = 0;
            foreach ($chars as $char) {
                if (!isset($char) || $char == "") continue;
                $weight = 0;
                if ($char == "S") {
                    $this->StartX = $colInx;
                    $this->StartY = $rowInx;
                } else if ($char == "D") {
                    $this->DestX = $colInx;
                    $this->DestY = $rowInx;
                } else {
                    $weight = (int) $char;
                }
                array_push($rowArr, $weight);
                $colInx++;
            }
            if (count($rowArr) == 0) continue;
            array_push($this->mapArr, $rowArr);
            $rowInx++;
        }

        if ($this->StartX == -1 || $this->StartY == -1) {
            throw new Exception("invalid start " . $this->StartX . " " . $this->StartY);
        }

        if ($this->DestX == -1 || $this->DestY == -1) {
            throw new Exception("invalid dest " . $this->DestX . " " . $this->DestY);
        }
    }

    public function getCoordCost($x, $y)
    {
        $cost = abs($this->mapArr[$y][$x]);
        return $cost;
    }

    public function getWidth()
    {
        $width = count($this->mapArr[0]);
        return $width;
    }

    public function getHeight()
    {
        $height = count($this->mapArr);
        return $height;
    }

    public function isValidCoord($x, $y)
    {
        if ($this->isWithinBounds($x, $y) == false) { 
            return false;
        }
        $cost = $this->getCoordCost($x, $y);
        if ($cost == 8) {
            return false;
        }
        return true;
    }

    public function isWithinBounds($x, $y)
    {
        if ($x < 0) {
            return false;
        } 
        if ($x >= $this->getWidth()) {
            return false;
        }
        if ($y < 0) {
            return false;
        }
        if ($y >= $this->getHeight()) {
            return false;
        } 
        return true;
    }
}