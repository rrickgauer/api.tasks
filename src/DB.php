<?php
/************************************************************************
 DB.php

 This class is responsible for communicating with the database.
***********************************************************************/

class DB 
{
    /**
     * Return a pdo database object
     */
    public static function dbConnect() {
        include('db-info.php');
        
        try {
            // connect to database
            $pdo = new PDO("mysql:host=$host;dbname=$dbName",$user,$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
            
        } catch(PDOexception $e) {
            return 0;
        }
    }

    /**
     * Insert a new user into the database
     */
    public static function insertUser(string $email, string $password) {
        $stmt = 'INSERT INTO Users 
        (id, email, password, created_on) VALUES 
        (UUID(), :email, :password, NOW())';

        $sql = DB::dbConnect()->prepare($stmt);
        
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);


        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        
        $sql->execute();

        return $sql;
    }

    /**
     * Get a user's password by checking the email and password combination.
     * 
     * Returns false if the password passed in does not match the password
     * retrieved from the database that matches the email passed in.
     */
    public static function getUserId(string $email, string $password) {
        $stmt = 'SELECT u.id, u.password FROM Users u WHERE u.email = :email LIMIT 1';

        $sql = DB::dbConnect()->prepare($stmt);
        
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $sql->bindParam(':email', $email, PDO::PARAM_STR);

        $sql->execute();
        $resultSet = $sql->fetch(PDO::FETCH_ASSOC);

        // check if the password matches the one in the database
        $hashedPassword = $resultSet['password'];

        if (!password_verify($password, $hashedPassword)) {
            return false;
        } else {
            return $resultSet['id'];
        }
    }


    /**
     * Retrieve user info.
     * 
     * id
     * email
     * created_on
     */
    public static function getUser(string $id) {
        $stmt = 'SELECT 
        u.id,
        u.email,
        u.created_on
        FROM Users u
        WHERE u.id = :id 
        LIMIT 1';

        $sql = DB::dbConnect()->prepare($stmt);
        
        $id = filter_var($id, FILTER_SANITIZE_EMAIL);
        $sql->bindParam(':id', $id, PDO::PARAM_STR);

        $sql->execute();

        return $sql;
    }

}


?>