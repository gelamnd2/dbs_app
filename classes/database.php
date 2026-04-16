<?php
 
class database{
    function opencon(): PDO{
    return new PDO(
dsn: 'mysql:host=localhost;
    dbname=dbs_app',
    username: 'root',
    password: '');
    }
   
    function insertUser($email,$password_hash,$is_active){
        $con = $this->opencon();
 
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Users (username,user_password_hash,is_active) VALUES (?,?,?)');
            $stmt->execute([$email,$password_hash,$is_active]);
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
   
    function insertBorrower($firstname,$lastname,$email,$phone,$member_since,$is_active){
        $con = $this->opencon();
 
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Borrowers (borrower_firstname,borrower_lastname,borrower_email,borrower_phone_number,borrower_member_since,is_active) VALUES(?,?,?,?,?,?)');
            $stmt->execute([$firstname,$lastname,$email,$phone,$member_since,$is_active]);
            $borrower_id = $con->lastInsertId();
            $con->commit();
            return $borrower_id;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }
 
    function insertBorrowerUser($user_id, $borrower_id){
        $con = $this->opencon();
 
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO BorrowerUser (user_id, borrower_id) VALUES(?,?)');
            $stmt->execute([$user_id,$borrower_id]);
            $con->commit();
            return true;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }    
    }

    function viewBorrowerUser(){
        $con = $this->opencon();
        return $con->query("SELECT * from borrowers")->fetchAll();
    }

    function insertBorrowerAddress($borrower_id,$house_number,$street,$barangay,$city,$province,$postal_code,$is_primary){
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Borroweraddress (borrower_id,ba_house_number,ba_street,ba_barangay,ba_city,ba_province,ba_postal_code,is_primary) VALUES(?,?,?,?,?,?,?,?)');
            $stmt->execute([$borrower_id,$house_number,$street,$barangay,$city,$province,$postal_code,$is_primary]);
            $ba_id = $con->lastInsertId();
            $con->commit();
            return $ba_id;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }
    function insertBook($title, $isbn, $publication_year, $edition, $publisher){
        $con = $this->opencon();
     
        try{
            $con->beginTransaction();
     
            $stmt = $con->prepare('INSERT INTO books (book_title, book_isbn, book_publication_year, book_edition, book_publisher) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$title, $isbn, $publication_year, $edition, $publisher]);
            $book_id = $con->lastInsertId();
            $con->commit();
            return $book_id;
     
        }catch(PDOException $e){  
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e; // Re-throw the exception after rolling back
        }
    }
  



function viewCopies(){
$con = $this->opencon();
return $con->query("SELECT
books.book_id,
books.book_title,books.book_isbn,
books.book_publication_year,
books.book_publisher,
COUNT(bookcopy.copy_id) AS Copies,
SUM(bookcopy.bc_status - 'Available') AS Available_Copies
FROM
books
LEFT JOIN bookcopy ON bookcopy.book_id = books.book_id
GROUP BY 1;")->fetchAll();



}

}
    