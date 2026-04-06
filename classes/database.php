<?php

class database{

    function opencon(): PDO{
        return new PDO(
            dsn: 'mysql:host=localhost;
            dbname=dbs_apps',
            username:'root',
            password:'');
        }
        function insertUser($email, $password_hash, $is_active){ 
            $con = $this->opencon();

            try{

            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Users (username,password_hash,is_active) VALUES (?,?,?)');
                $stmt->execute([$email, $password_hash, $is_active]);
                $user_id = $con->lastInsertId();
                $con->commit();
                return $user_id;



            }catch(PDOException $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
                throw $e;

            }
      
    }

}