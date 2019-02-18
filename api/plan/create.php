<?php

namespace API;

use Config\Database;
use Objects\Plan;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/plan.php';

$database = new Database();
$db = $database->getConnection();

$plan = new Plan($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->description)) {

    $plan->name = $data->name;
    $plan->description = $data->description;
    $plan->days = $data->days;
    $plan->created = date('Y-m-d H:i:s');

    if ($plan->createWithAssociations()) {

        http_response_code(201);

        echo json_encode(["message" => "The plan was created."]);
    } else {

        http_response_code(503);

        echo json_encode(["message" => "Unable to create the plan."]);
    }
} else {

    http_response_code(400);

    echo json_encode(["message" => "Unable to create the plan. Data is incomplete."]);
}