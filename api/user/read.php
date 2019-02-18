<?php

namespace API;

use Config\Database;
use Objects\User;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$usersArr = $user->read();

if ($usersArr) {

    http_response_code(200);

    echo json_encode($usersArr);
} else {

    http_response_code(404);

    echo json_encode(
        ["message" => "No users found."]
    );
}
