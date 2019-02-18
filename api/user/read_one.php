<?php

namespace API;

use Config\Database;
use Objects\User;
use Objects\Plan;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../objects/user.php';
include_once '../../objects/plan.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id = $_GET['id'] ?? null;

$userArr = $user->id !== null ? $user->readOne()->toArray() : [];

if ($userArr) {

    $plan = new Plan($db);
    $plan->id = $userArr['plan_id'];

    $userArr['plan'] = $plan->id !== null ? $plan->readOne()->toArray() : [];

    http_response_code(200);

    echo json_encode($userArr);
} else {

    http_response_code(404);

    echo json_encode(["message" => "User does not exist."]);
}