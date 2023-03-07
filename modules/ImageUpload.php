<?php

declare(strict_types=1);
namespace Module;
// ini_set("display_errors", 1);

require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/../config/config.php";
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;


class ImageUpload {

    private string $cloud_name;
    private string $api_key;
    private string $api_secret;

    public function __construct(){
        $this->cloud_name = "dyjhe7cg2";
        $this->api_key = "343737699854672";
        $this->api_secret = "IYHjgMp8sCl0Qc9K_5HP4V3T03U";
    }

    public function uploadImage($tmp_name){
        $con = Configuration::instance([
            'cloud' => [
                'cloud_name' => $this->cloud_name,
                'api_key' => $this->api_key,
                'api_secret' => $this->api_secret
            ]
        ]);

        $upload = new UploadApi();
        $response = $upload->upload($tmp_name);
        return $response;
    }


}