<?php


class AlgorithmInvoker {

    public function Invoke($data) {

        switch ($data->AlgorithmIndex)
        {
            case 0:
                $algorithm = new AStarAlgorithm($data->StartX, $data->StartY, $data->DestX, $data->DestY, $data->MapData);
                break;
            case 1:
                $algorithm = new DijkstraAlgorithm($data->StartX, $data->StartY, $data->DestX, $data->DestY, $data->MapData);
                break;
            case 2:
                $algorithm = new BreadthFirstSearchAlgorithm($data->StartX, $data->StartY, $data->DestX, $data->DestY, $data->MapData);
                break;
            case 3:
                $algorithm = new DepthFirstSearchAlgorithm($data->StartX, $data->StartY, $data->DestX, $data->DestY, $data->MapData);
                break;
            default:
                throw new Exception("invalid arlgorithm index " . $data->AlgorithmIndex);
        }

        $startTime = microtime(true);
        $result = $algorithm->run();
        $result->ms = microtime(true) - $startTime;
        $result->found = count($result->paths) > 0;

        return $result;
    }
}