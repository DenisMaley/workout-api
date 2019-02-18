<?php

namespace API;

use Config\Database;
use Objects\Day;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../objects/day.php';

$database = new Database();
$db = $database->getConnection();

$day = new Day($db);

$daysArr = $day->read();

if ($daysArr) {

    http_response_code(200);

    echo json_encode($daysArr);
} else {

    http_response_code(404);

    echo json_encode(
        ["message" => "No days found."]
    );
}
