<?php
declare(strict_types=1);
namespace Model;
ini_set("display_errors", 1);

require_once __DIR__ . "/../vendor/autoload.php";

use Model\Model;

class Admin extends Model
{

    public function __construct(){
        parent::__construct();
        $this->tbl_name = "admins";
    }

    public function create_admin(string $name,string $email,string $password,string $type="admin"){
        $query = "INSERT INTO $this->tbl_name(name,email,password,type) VALUES(?,?,?,?)";
        $stmt = $this->connection->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));
        $type = htmlspecialchars(strip_tags($type));

        $stmt->bind_param("ssss",$name,$email,$password,$type);
        return $stmt->execute() ?? false;
    }

    public function get_admin_with_type(string $type="admin"){
        $query = "SELECT * FROM $this->tbl_name WHERE type = ?";
        $stmt = $this->connection->prepare($query);

        $type = htmlspecialchars(strip_tags($type));

        $stmt->bind_param("s",$type);
        $executed = $stmt->execute() ? true : false;
        $this->execution_error($executed);
        return $stmt->get_result();
    }

    public function get_admin_with_email(string $email)
    {
        return $this->get_data_with_email($email);
    }
}