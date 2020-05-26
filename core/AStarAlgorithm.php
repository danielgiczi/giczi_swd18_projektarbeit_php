<?php

class AStarNode
{
    public $X = 0;
    public $Y = 0;

    public $Parent;

    public $F = 0;
    public $G = 0;
    public $H = 0;

    function __construct($X, $Y)
    {
        $this->X = $X;
        $this->Y = $Y;
    }

    public function equals($other)
    {
        if (!$other instanceof AStarNode) return false;
        $eq =  $this->X == $other->X && $this->Y == $other->Y;
        return $eq;
    }
}

class AStarAlgorithm implements IAlgorithm
{
    private $P;
    private $G;
    private $visited;
    private $map;

    function __construct($startX, $startY, $goalX, $goalY, $rawData)
    {
        $this->P = new AStarNode($startX, $startY);
        $this->G = new AStarNode($goalX, $goalY);
        $this->map = new Map($rawData);
    }

    function getCostFromStartToNode($node) {
        return abs($this->G->X - $node->X) + abs($this->G->Y - $node->Y);
    }
    
    function getEstimatedCostFromStartToNode($node) {
        if (!isset($node->Parent)) {
            return 0;
        }
    
        return $node->Parent->G + $this->map->getCoordCost($node->X, $node->Y) + 1;
    }

    function calculateCostsForNode($node) {
        $node->G = $this->getEstimatedCostFromStartToNode($node);
        $node->H = $this->getCostFromStartToNode($node);
        $node->F = $node->G + $node->H;
    }
    
    function run()
    {
        $result = new AlgorithmResult();

        $OpenList = array();
        array_push($OpenList, $this->P);
        $ClosedList = array();

        while (true) {
            if (count($OpenList) == 0) {
                break;
            }

            usort($OpenList, function($a, $b) {return $a->F - $b->F;});
            
            $B = array_shift($OpenList);
            array_push($ClosedList, $B);

            $connectedNodes = $this->getConnectedNodes($B);

            foreach ($connectedNodes as $C) {    
                $this->calculateCostsForNode($C);

                $nodeInOpenList = null;
                foreach ($OpenList as $OpenListNode) {
                    if ($OpenListNode->equals($C)) {
                        $nodeInOpenList = $C;
                        break;
                    }
                }

                $nodeInClosedList = null;
                foreach ($ClosedList as $ClosedListNode) {
                    if ($ClosedListNode->equals($C)) {
                        $nodeInClosedList = $C;
                        break;
                    }
                }
        
                array_push($result->probes, new Probes($C->X, $C->Y));

                if ($C->equals($this->G)) {
                    $node = $C;
                    $neXtNode = $node->Parent;
                    while (isset($node->Parent)) {
                        array_push($result->paths, new Paths($node->X, $node->Y));
                        $node = $neXtNode;
                        $neXtNode = $node->Parent;
                    }
                    array_push($result->paths, new Paths($node->X, $node->Y));

                    break 2;
                }
                else if (isset($nodeInOpenList)) {
                    if ($C->F >= $nodeInOpenList->F) {
                        continue;
                    }
                }
                else if (isset($nodeInClosedList)) {
                    if ($C->F >= $nodeInClosedList->F) {
                        continue;
                    }
                    else {
                        array_push($OpenList, $C);
                    }
                }
                else {
                    array_push($OpenList, $C);
                }
            }
        }

        return $result;
    }

    function getConnectedNodes($parent) {
        $nodes = array();

        //above
        if ($this->map->isValidCoord($parent->X, $parent->Y - 1)) {
            $node = new AStarNode($parent->X, $parent->Y - 1);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        //below
        if ($this->map->isValidCoord($parent->X, $parent->Y + 1)) {
            $node = new AStarNode($parent->X, $parent->Y + 1);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        //left
        if ($this->map->isValidCoord($parent->X - 1, $parent->Y)) {
            $node = new AStarNode($parent->X - 1, $parent->Y);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        //right
        if ($this->map->isValidCoord($parent->X + 1, $parent->Y)) {
            $node = new AStarNode($parent->X + 1, $parent->Y);
            $node->Parent = $parent;
            array_push($nodes, $node);
        }

        return $nodes;
    }
}
