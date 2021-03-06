<?php

namespace API;

use Config\Database;
use Objects\User;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email)) {

    $user->lastname = $data->lastname;
    $user->firstname = $data->firstname;
    $user->email = $data->email;
    $user->plan_id = $data->plan_id;

    if ($user->create()) {

        http_response_code(201);

        echo json_encode(["message" => "The user was created."]);
    } else {

        http_response_code(503);

        echo json_encode(["message" => "Unable to create the user."]);
    }
} else {

    http_response_code(400);

    echo json_encode(["message" => "Unable to create the user. Data is incomplete."]);
}