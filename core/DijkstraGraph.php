<?php

class DijkstraGraph {

    private $map = null;
    private $nodes = array();

    public function __construct($rawData)
    {
        $this->map = new Map($rawData);

        for ($x = 0; $x < $this->map->getWidth(); $x++) {
            for ($y = 0; $y < $this->map->getHeight(); $y++) {
                $node = new DijkstraNode($x,$y);
                $node->Weight = $this->map->getCoordCost($x, $y);
                if($node->Weight == 8) $node->Weight = PHP_INT_MAX;
                $this->nodes[$node->key()] = $node;
            }
        }
    
        foreach ($this->nodes as $node) {
            if($node->Selected == false) {
                $this->calcAdjacentNodes($node);
            }
        }
    }

    private function calcAdjacentNodes($parent) {
        $nodes = array();
    
        $above = $this->get($parent->X, $parent->Y -1);
        if(isset($above)) {
            array_push($nodes, $above);
        }
    
        $below = $this->get($parent->X, $parent->Y + 1);
        if(isset($below)){
            array_push($nodes, $below);
        }
    
        $left = $this->get($parent->X - 1, $parent->Y);
        if(isset($left)){
            array_push($nodes, $left);
        }
    
        $right = $this->get($parent->X + 1, $parent->Y);
        if(isset($right)){
            array_push($nodes, $right);
        }
    
        $parent->Adjacents = $nodes;
    }

    
    public function get($x,$y) {
        if(!$this->map->isWithinBounds($x,$y)){
            return null;
        }
        else {
            return $this->nodes[$x . "|" . $y];
        }
    }

    public function getSortedUnexploredList() {
        $list = array();

        foreach ($this->nodes as $node) {            
            if($node->Selected == false) {
                array_push($list,$node);
            }
        }

        usort($list, function($a, $b) {return $a->Distance - $b->Distance;});

        return $list;
    }

    public function reset() {
        foreach ($this->nodes as $node) {
            $node->Distance = PHP_INT_MAX;
            $node->Selected = false;
        }
    }
}