<?php

class DijkstraNode
{
    public $X = 0;
    public $Y = 0;

    public $Parent;
    public $Distance;
    public $Adjacents;
    public $Selected;
    public $Weight;

    function __construct($x, $y)
    {
        $this->X = $x;
        $this->Y = $y;
    }

    public function equals($other)
    {
        if (!$other instanceof DijkstraNode) return false;
        $eq =  $this->X == $other->X && $this->Y == $other->Y;
        return $eq;
    }

    public function key() {
        return $this->X . "|" . $this->Y;
    }
}

class DijkstraAlgorithm implements IAlgorithm
{
    private $P;
    private $G;
    private $graph;

    function __construct($startX, $startY, $goalX, $goalY, $rawData)
    {
        $this->P = new DijkstraNode($startX, $startY);
        $this->G = new DijkstraNode($goalX, $goalY);
        $this->graph = new DijkstraGraph($rawData);
    }

    function run()
    {
        $result = new AlgorithmResult();

        $this->graph->reset();
        $this->graph->get($this->P->X, $this->P->Y)->Distance = 0;

        while (true) {

            $UnexploredList = $this->graph->getSortedUnexploredList();

            if (count($UnexploredList) == 0) {
                break;
            }
 
            $B = array_shift($UnexploredList);
            $B->Selected = true;

            if ($B->equals($this->G)) {
                $node = $B;
                $nextNode = $node->Parent;
                while (isset($node->Parent)) {
                    array_push($result->paths, new Paths($node->X, $node->Y));
                    $node = $nextNode;
                    $nextNode = $node->Parent;
                }
                array_push($result->paths, new Paths($node->X, $node->Y));

                break;
            }

            $adjacentNodes = $B->Adjacents;

            foreach ($adjacentNodes as $C) {
                if($C->Selected == true) continue;

                if($C->Weight != PHP_INT_MAX) {
                    array_push($result->probes, new Probes($C->X, $C->Y));
                }

                $distance = $B->Distance + $C->Weight + 1;

                if($distance < $C->Distance) {
                    $C->Distance = $distance;
                    $C->Parent = $B;
                }
            }
        }

        return $result;
    }
}
