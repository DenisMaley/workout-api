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

$plan->id = $data->id;

if ($plan->delete()) {

    http_response_code(200);

    echo json_encode(["message" => "Plan was deleted."]);
} else {

    http_response_code(503);

    echo json_encode(["message" => "Unable to delete plan."]);
}