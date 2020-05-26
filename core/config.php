<?php
    defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
    defined("SITE_ROOT") ? null: define("SITE_ROOT", DS . "wamp64" . DS . 'www' . DS . "giczi_swd18_projektarbeit_php");
    defined("CORE_PATH") ? null: define("CORE_PATH", SITE_ROOT.DS."core");

    require_once("AlgorithmInvoker.php");
    require_once("AlgorithmResult.php");
    require_once("IAlgorithm.php");
    require_once("Map.php");
    require_once("Paths.php");
    require_once("Probes.php");

    require_once("DepthFirstSearchAlgorithm.php");
    require_once("BreadthFirstSearchAlgorithm.php");
    require_once("DijkstraGraph.php");
    require_once("DijkstraAlgorithm.php");
    require_once("AStarAlgorithm.php");
?>