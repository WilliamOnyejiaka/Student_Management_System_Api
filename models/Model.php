<?php

declare(strict_types=1);
namespace Model;
ini_set('display_errors',true);

include_once __DIR__ . "/../config/config.php";
require_once __DIR__ ."/../vendor/autoload.php";

use Lib\Response;
use Lib\Database;

class Model {

    protected $connection;
    protected string $tbl_name;
    protected Response $response;

    public function __construct(){
        $this->connection = (new Database(config('host'),config('username'),config('password'),config('db_name')))->connect();
        $this->response = new Response();
    }

    protected function execution_error($executed)
    {
        if (!$executed) {
            $this->response->send_response(500, [
                'error' => true,
                'message' => "something went wrong"
            ]);
            exit();
        }
    }

    protected function get_data_with_email(string $email){
        $query = "SELECT * FROM $this->tbl_name WHERE email = ?";
        $stmt = $this->connection->prepare($query);

        $email = htmlspecialchars(strip_tags($email));

        $stmt->bind_param("s", $email);
        $executed = $stmt->execute() ? true : false;
        $this->execution_error($executed);
        return $stmt->get_result();
    }
}