<?php
declare(strict_types=1);

ini_set("display_errors", 1);

require_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/../../config/config.php";

use Lib\Router;
use Lib\Controller;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Lib\TokenAttributes;
use Lib\Validator;

$token = new Router("token", true);
$controller = new Controller();

$token->get('/access-token', fn() => $controller->access_token_controller(function ($payload, $body, $response) {
    $id = $payload->data->id;
    $type = $payload->data->type;
    $user_type = $type;

    $data = [
        'needed_values' => ['id','type'],
        'data' => [
            'id' => $id,
            'type' => $type
    ]];

    $token_attributes = new TokenAttributes($data,$user_type);
    $access_token = JWT::encode($token_attributes->access_token_payload(), config("secret_key"), config("hash"));

    $response->send_response(200, [
        'error' => false,
        'token' => $access_token,
        'aud' => $payload->data
    ]);
}));

$token->get('/validate-token', fn() => $controller->public_controller(function ( $body, $response) {
    $validator = new Validator();
    $token = $validator->validate_query_strings(['token'])['token'];

    $payload = null;
    try {
        $payload = (JWT::decode($token, new Key(config('secret_key'), config('hash'))));
    } catch (\Firebase\JWT\ExpiredException $ex) {
        $response->send_response(400, [
            'error' => true,
            'message' => $ex->getMessage()
        ]);
        exit();
    }
    $response->send_response(200, [
        'error' => false,
        'payload' => $payload
    ]);
    $response->send_response(400,[
        'error' => true,
        'message' => "invalid jwt"
    ]);
}));

$token->run();