<?php

namespace API;

use Config\Database;
use Objects\Plan;
use Objects\User;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/plan.php';
include_once '../../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$plan = new Plan($db);

$data = json_decode(file_get_contents("php://input"));

$plan->id = $data->id;
$plan->name = $data->name;
$plan->description = $data->description;
$plan->users = $plan->readUsers();

foreach ($plan->users as $userArr) {
    $user = new User($db);
    $user->id = $userArr['user_id'];

    $plan->attach($user->readOne());
}

if ($plan->upgrade()) {

    http_response_code(200);

    echo json_encode(["message" => "Plan was updated."]);
} else {

    http_response_code(503);

    echo json_encode(["message" => "Unable to update plan."]);
}