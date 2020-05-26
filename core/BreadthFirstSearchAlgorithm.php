<?php

class BreadthFirstSearchNode
{
    public $X = 0;
    public $Y = 0;

    public $Parent;

    function __construct($x, $y)
    {
        $this->X = $x;
        $this->Y = $y;
    }

    public function equals($other)
    {
        if (!$other instanceof BreadthFirstSearchNode) return false;
        $eq =  $this->X == $other->X && $this->Y == $other->Y;
        return $eq;
    }
}

class BreadthFirstSearchAlgorithm implements IAlgorithm
{
    private $P;
    private $G;
    private $visited;
    private $map;

    function __construct($startX, $startY, $goalX, $goalY, $rawData)
    {
        $this->P = new BreadthFirstSearchNode($startX, $startY);
        $this->G = new BreadthFirstSearchNode($goalX, $goalY);
        $this->map = new Map($rawData);
    }

    function run()
    {
        $result = new AlgorithmResult();

        $this->visited = array();
        $queue = array();
        array_push($queue, $this->P);

        while (true) {
            if (count($queue) == 0) {
                break;
            }
 
            $B = array_shift($queue);

            $adjacentNodes = $this->getUnivisitedAdjacentNodes($B);
            array_push($this->visited, $B);

            foreach ($adjacentNodes as $C) {
                if ($C->equals($this->G)) {
                    $node = $C;
                    $nextNode = $node->Parent;
                    while (isset($node->Parent)) {
                        array_push($result->paths, new Paths($node->X, $node->Y));
                        $node = $nextNode;
                        $nextNode = $node->Parent;
                    }
                    array_push($result->paths, new Paths($node->X, $node->Y));

                    break 2;
                }

                array_push($result->probes, new Probes($C->X, $C->Y));
                array_push($this->visited, $C);
                array_push($queue, $C);
            }
        }

        return $result;
    }

    public function isNodeUnivisited($node)
    {
        $found = null;
        foreach ($this->visited as $visitedNode) {
            if ($node->equals($visitedNode)) {
                return false;
            }
        }

        return true;
    }

    public function isValidAdjacent($x, $y)
    {
        return $this->map->isValidCoord($x, $y) && $this->isNodeUnivisited(new BreadthFirstSearchNode($x, $y));
    }

    public function getUnivisitedAdjacentNodes(BreadthFirstSearchNode $parent)
    {
        $nodes = array();

        //above
        if ($this->isValidAdjacent($parent->X, $parent->Y - 1)) {
            $node = new BreadthFirstSearchNode($parent->X, $parent->Y - 1);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        //below
        if ($this->isValidAdjacent($parent->X, $parent->Y + 1)) {
            $node = new BreadthFirstSearchNode($parent->X, $parent->Y + 1);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        //left
        if ($this->isValidAdjacent($parent->X - 1, $parent->Y)) {
            $node = new BreadthFirstSearchNode($parent->X - 1, $parent->Y);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        //right
        if ($this->isValidAdjacent($parent->X + 1, $parent->Y)) {
            $node = new BreadthFirstSearchNode($parent->X + 1, $parent->Y);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        return $nodes;
    }
}
