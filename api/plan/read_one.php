<?php

namespace API;

use Config\Database;
use Objects\Plan;
use Objects\Day;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../objects/plan.php';
include_once '../../objects/day.php';

$database = new Database();
$db = $database->getConnection();

$plan = new Plan($db);

$plan->id = $_GET['id'] ?? null;

$planArr = $plan->id !== null ? $plan->readOne()->toArray() : [];

if ($planArr) {

    foreach ($planArr['days'] as &$dayArr){
        $day = new Day($db);
        $day->id = $dayArr['day_id'];

        $dayArr['day_exercises'] = $day->readAssociations();
    }

    http_response_code(200);

    echo json_encode($planArr);
} else {

    http_response_code(404);

    echo json_encode(["message" => "Plan does not exist."]);
}