<?php

declare(strict_types=1);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/../../config/config.php";

use Lib\Router;
use Lib\Controller;
use \Firebase\JWT\JWT;
use Lib\TokenAttributes;

$token = new Router("token", true);
$controller = new Controller();

$token->get('/access-token', fn() => $controller->access_token_controller(function ($payload, $body, $response) {
    $id = $payload->data->id;
    $user_type = $payload->aud;
    $data = [
        'needed_values' => ['id'],
        'data' => [
            'id' => $id,
    ]];

    $token_attributes = new TokenAttributes($data,$user_type);
    $access_token = JWT::encode($token_attributes->access_token_payload(), config("secret_key"), config("hash"));

    $response->send_response(200, [
        'error' => false,
        'token' => $access_token
    ]);
}));

$token->run();