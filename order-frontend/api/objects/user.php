<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $email;
    public $password;
    public $name;       // Add 'name' property
    public $created_at;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
 
    // signup user
    function signup(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        
        // Set created_at to current datetime
        $this->created_at = date('Y-m-d H:i:s');
        
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, email=:email, password=:password, created_at=:created_at";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created_at", $this->created_at);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
    }
 
    // login user
    function login(){
        // select all query
        $query = "SELECT
                    `id`, `email`, `password`, `name`, `created_at`
                FROM
                    " . $this->table_name . " 
                WHERE
                    email=:email AND password=:password";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        
        // execute query
        $stmt->execute();
        return $stmt;
    }
 
    // check if the user already exists
    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                email=:email";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind value
        $stmt->bindParam(":email", $this->email);
        
        // execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            return true;
        } else {
            return false;
        }
    }
}
?>
