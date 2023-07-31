<?php

session_start();


$response = array();


if (isset($_SESSION['user']) && isset($_SESSION['room'])) {
    $response['user'] = $_SESSION['user'];
    $response['room'] = $_SESSION['room'];
}

header('Content-Type: application/json');

echo json_encode($response);
