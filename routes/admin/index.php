<?php

declare(strict_types=1);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/../../config/config.php";

use Lib\Router;
use Lib\Controller;
use Lib\Validator;
use Lib\Serializer;
use \Firebase\JWT\JWT;
use Lib\TokenAttributes;
use Model\Admin;

$admin = new Router("admin", true);
$controller = new Controller();

$admin->post("/sign-up", fn() => $controller->protected_controller(function ($payload,$body, $response) {

    $admin_type = $payload->aud;

    (!in_array($admin_type,['admin','super_admin','main_admin'])) && $response->send_response(401, [
        'error' => true,
        'message' => "user not authorized"
    ]);

    $validator = new Validator();
    $validator->validate_body($body,['name','email','password',"type"]);
    [$name,$email,$password,$type] = [$body->name,$body->email,$body->password,$body->type];
    
    $validator->validate_email_with_response($email);
    $validator->validate_password_with_response($password,5);
    $password = password_hash($password,PASSWORD_DEFAULT);

    $allowed_types = ['admin','super_admin'];

    (!in_array($type,$allowed_types) && $response->send_response(400,[
        'error' => true,
        'message' => "type not allowed"
    ]));

    (($admin_type == "admin" &&  $type == "super_admin") &&
        $response->send_response(400,[
            'error' => true,
            'message' => "not authorized for this type"
    ]));

    $admin = new Admin();
    $admin_exist = (new Serializer(['email']))->tuple($admin->get_admin_with_email($email));

    if($admin_exist){
        $response->send_response(400, [
            'error' => true,
            'message' => "email exists",
        ]);
    }

    if($admin->create_admin($name,$email,$password,$type)){
        $response->send_response(200, [
            'error' => false,
            'message' => "$type created successfully",
        ]);
    }

    $response->send_response(500, [
        'error' => true,
        'message' => "something went wrong",
    ]);
}));

$admin->get("/login",fn() => $controller->public_controller(function($body,$response){
    $email = $_SERVER['PHP_AUTH_USER'] ?? null;
    $password = $_SERVER['PHP_AUTH_PW'] ?? null;

    if(!$email || !$password){
        $response->send_response(400, [
            'error' => true,
            'message' => "all values needed"
        ]);
    }

    $admin = new Admin();
    $current_admin = (new Serializer(['email','password','type']))->tuple($admin->get_admin_with_email($email));

    if($current_admin){
        $valid_password = password_verify($password,$current_admin['password']);
        if($valid_password){
            $needed_values = [
                'id',
                'name',
                'email',
                'type',
                'created_at',
                'updated_at'
            ];

            $active_admin = (new Serializer($needed_values))->tuple($admin->get_admin_with_email($email));

            $data = [
                'needed_values' => ['id','type'],
                'data' => $active_admin,
            ];

            $token_attributes = new TokenAttributes($data,$current_admin['type']);
            $access_token = JWT::encode($token_attributes->access_token_payload(), config("secret_key"), config("hash"));
            $refresh_token = JWT::encode($token_attributes->refresh_token_payload(), config("secret_key"), config("hash"));

            $response->send_response(200, [
                'error' => false,
                'data' => [
                    "user" => $active_admin,
                    'access_token' => $access_token,
                    'refresh_token' => $refresh_token
                ],
            ]);
        }

        $response->send_response(400,[
            'error' => true,
            'message' => "invalid password"
        ]);
    }

    $response->send_response(404,[
        'error' => true,
        'message' => "email does not exist"
    ]);
}));

$admin->patch("/update-name",fn() => $controller->protected_controller(function($payload,$body,$response){
    $admin_type = $payload->aud;

    (!in_array($admin_type, ['admin', 'super_admin', 'main_admin'])) && $response->send_response(401, [
        'error' => true,
        'message' => "user not authorized"
    ]);


    $validator = new Validator();
    $validator->validate_body($body, ['name']);
    $name = $body->name;
    $id = $payload->data->id;

    $admin = new Admin();

    if($admin->update_admin_name($id,$name)){
        $response->send_response(200,[
            'error' => false,
            'message' => "name updated successfully",
        ]);
    }

    $response->send_response(500, [
        'error' => true,
        'message' => "something went wrong",
    ]);

}));

$admin->patch("/update-email", fn() => $controller->protected_controller(function ($payload, $body, $response) {
    $admin_type = $payload->aud;

    (!in_array($admin_type, ['admin', 'super_admin', 'main_admin'])) && $response->send_response(401, [
        'error' => true,
        'message' => "user not authorized"
    ]);


    $validator = new Validator();
    $validator->validate_body($body, ['email']);

    $email = $body->email;
    $id = $payload->data->id;

    $validator->validate_email_with_response($email);

    $admin = new Admin();
    $email_exits = (new Serializer(['email']))->tuple($admin->get_admin_with_email($email));

    (((isset($email_exits['email']) && $email_exits['email'] == $email) && $response->send_response(200,[
        'error' => true,
        'message' => "new email required"
    ])));

    if(!$email_exits){
        if ($admin->update_admin_email($id, $email)) {
            $response->send_response(200, [
                'error' => false,
                'message' => "email updated successfully",
            ]);
        }

        $response->send_response(500, [
            'error' => true,
            'message' => "something went wrong",
        ]);
    }

    $response->send_response(400,[
        'error' => true,
        'message' => "email exits"
    ]);
}));

$admin->patch("/update-password", fn() => $controller->protected_controller(function ($payload, $body, $response) {
    $admin_type = $payload->aud;

    (!in_array($admin_type, ['admin', 'super_admin', 'main_admin'])) && $response->send_response(401, [
        'error' => true,
        'message' => "user not authorized"
    ]);


    $validator = new Validator();
    $validator->validate_body($body, ['password',"new_password"]);

    $password = $body->password;
    $new_password = $body->new_password;
    $id = $payload->data->id;

    $validator->validate_password_with_response($new_password,5);

    $admin = new Admin();
    $current_admin = (new Serializer(['password']))->tuple($admin->get_admin_with_id($id));
    $valid_password = password_verify($password, $current_admin['password']);


    if ($valid_password) {
        $new_password = password_hash($new_password,PASSWORD_DEFAULT);

        if ($admin->update_admin_password($id, $new_password)) {
            $response->send_response(200, [
                'error' => false,
                'message' => "password updated successfully",
            ]);
        }

        $response->send_response(500, [
            'error' => true,
            'message' => "something went wrong",
        ]);
    }

    $response->send_response(400, [
        'error' => true,
        'message' => "invalid password"
    ]);
}
));


$admin->get("/dummy-sign-up", fn() => $controller->public_controller(function ($body, $response) {

    [$name, $email,$type] = ["Dummy Admin","dummy@email.com","main_admin"];

    $password = password_hash((config('super_password') ?? "password"), PASSWORD_DEFAULT);

    $admin = new Admin();
    $admin_exist = (new Serializer(['type']))->tuple($admin->get_admin_with_type($type));

    if ($admin_exist) {
        $response->send_response(400, [
            'error' => true,
            'message' => "admin exists",
        ]);
    }

    if ($admin->create_admin($name, $email, $password,$type)) {
        $response->send_response(200, [
            'error' => false,
            'message' => "admin created successfully",
        ]);
    }

    $response->send_response(500, [
        'error' => true,
        'message' => "something went wrong",
    ]);
}));

$admin->run();

