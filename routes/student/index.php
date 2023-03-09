<?php

declare(strict_types=1);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/../../config/config.php";

use Lib\Router;
use Lib\Controller;
use Lib\Validator;
use Lib\Serializer;
use Module\UploadImage;
use Model\Student;
use Module\ImageUpload;

$student = new Router("student", true);
$controller = new Controller();

$student->post("/upload-image", fn() => $controller->protected_controller(function ($payload, $body, $response) {
    $image_file_exists = $_FILES['image_file'] ?? false;

    (!$image_file_exists) && $response->send_response(404, [
        'error' => true,
        'message' => "image file missing"
    ]);

    (!$_FILES['image_file']['tmp_name']) && $response->send_response(404, [
        'error' => true,
        'message' => "image file not valid"
    ]);

    $upload_response = (new ImageUpload())->uploadImage($_FILES['image_file']['tmp_name']);

    (!$upload_response) && $response->send_response(500, [
        'error' => true,
        'message' => "something went wrong"
    ]);

    $id = $payload->data->id;
    $studentDB = new Student();
    $image_url = $upload_response['url'];

    if($studentDB->update_image_name($id,$image_url)){
        $response->send_response(200, [
            'error' => false,
            'message' => "image added successfully"
        ]);
    }

    $response->send_response(500, [
        'error' => true,
        'message' => "something went wrong"
    ]);

    // $response->send_response(500, [
    //     'error' => true,
    //     'message' => $_FILES['image_file']
    // ]);
}));

$student->get('/get-student-image',fn() => $controller->protected_controller(function($payload,$body,$response){
    $id = $payload->data->id;
    $student = new Student();
    $image_url = (new Serializer(['image_name']))->tuple($student->get_student_with_id($id))['image_url'];

    $response->send_response(200,[
        'error' => $image_url
    ]);
}));

$student->get('/get-student',fn() => $controller->protected_controller(function($payload,$body,$response){
    $student_id = $payload->data->id;
    $student = new Student();

    $current_student = (new Serializer([
        'id',
        'name',
        'email',
        'class',
        'image_url',
        'created_at',
        'updated_at'
    ]))->tuple($student->get_student_with_id($student_id));
    if($current_student){
        $response->send_response(200,[
            'error' => false,
            'data' => $current_student
        ]);
    }

    $response->send_response(500,[
        'error' => true,
        'message' => "something went wrong"
    ]);
}));

$student->post('/file', fn() => $controller->public_controller(function ($body, $response) {

    $upload_response = (new ImageUpload())->uploadImage($_FILES['image_file']['tmp_name']);


    $response->send_response(200,[
        'error' => false,
        'message' => $upload_response
    ]);
}
));




$student->run();

