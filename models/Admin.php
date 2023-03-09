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

    public function get_admin_with_id(int $id){
        $query = "SELECT * FROM $this->tbl_name WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        $stmt->bind_param("i", $id);
        $executed = $stmt->execute() ? true : false;
        $this->execution_error($executed);
        return $stmt->get_result();
    }


    public function update_admin_name(int $id,string $name){
        $query = "UPDATE $this->tbl_name SET name = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        $name = htmlspecialchars(strip_tags($name));

        $stmt->bind_param("si",$name,$id);
        return $stmt->execute() ?? false;
    }

    public function update_admin_email(int $id, string $email)
    {
        $query = "UPDATE $this->tbl_name SET email = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        $email = htmlspecialchars(strip_tags($email));

        $stmt->bind_param("si", $email, $id);
        return $stmt->execute() ?? false;
    }

    public function update_admin_password(int $id, string $password)
    {
        $query = "UPDATE $this->tbl_name SET password = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        $password = htmlspecialchars(strip_tags($password));

        $stmt->bind_param("si", $password, $id);
        return $stmt->execute() ?? false;
    }
}