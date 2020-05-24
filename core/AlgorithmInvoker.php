<?php


class AlgorithmInvoker {

    public function Invoke($data) {
        $startTime = microtime(true);

        switch ($data->AlgorithmIndex)
        {/*
            case 0:
                algorithm = new AStarAlgorithm(data.StartX, data.StartY, data.DestX, data.DestY, data.MapData);
                break;
            case 1:
                algorithm = new DijkstraAlgorithm(data.StartX, data.StartY, data.DestX, data.DestY, data.MapData);
                break;
            case 2:
                algorithm = new BreadthFirstSearchAlgorithm(data.StartX, data.StartY, data.DestX, data.DestY, data.MapData);
                break;*/
            case 3:
                $algorithm = new DepthFirstSearchAlgorithm($data->StartX, $data->StartY, $data->DestX, $data->DestY, $data->MapData);
                break;
            default:
                throw new Exception("invalid arlgorithm index " . $data->AlgorithmIndex);
        }

        $result = $algorithm->run();
        
        $elapsed = microtime(true) - $startTime;

        $result->ms = $elapsed;

        return $result;
    }
}