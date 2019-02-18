<?php

namespace API;

use Config\Database;
use Objects\Plan;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../objects/plan.php';

$database = new Database();
$db = $database->getConnection();

$plan = new Plan($db);

$plansArr = $plan->read();

if ($plansArr) {

    http_response_code(200);

    echo json_encode($plansArr);
} else {

    http_response_code(404);

    echo json_encode(
        ["message" => "No plans found."]
    );
}
