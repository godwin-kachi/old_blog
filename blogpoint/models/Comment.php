<?php

class Comment
{

    private $table_name = "comments";
    public $comment_id;
    public $blog_id;
    public $user_id;
    public $comment;
    public $likes;
    public $created_at;
    public $updated_at;

    // db conn instance
    private $conn = NULL;


    // Constructor to assign conn
    public function __construct($db){
        $this->conn = $db;
    }


    // Get a particular comment
    public function getComment()
    {

         $query = "SELECT * FROM $this->table_name WHERE comment_id=$this->comment_id";
            
        // prepare query statement
        $select_stmt = $this->conn->prepare($query);

        try {
            // execute query
            $select_stmt->execute();
            return ["output" => $select_stmt, "outputStatus" => 1000];
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        };
    }

// Get all user comments
    public function getUserComments()
    {

        // select query if student ID is provided
        $query = "SELECT * FROM $this->table_name WHERE user_id = $this->user_id";
            
        // prepare query statement
        $select_stmt = $this->conn->prepare($query);

        try {
            // execute query
            $select_stmt->execute();
            return ["output" => $select_stmt, "outputStatus" => 1000];
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        };
    }


    // Get blog comments
    public function getBlogComments()
    {

        // select query if student ID is provided
        $query = "SELECT * FROM $this->table_name WHERE blog_id = $this->blog_id";
            
        // prepare query statement
        $select_stmt = $this->conn->prepare($query);

        try {
            // execute query
            $select_stmt->execute();
            return ["output" => $select_stmt, "outputStatus" => 1000];
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        };
    }

// Get all user comment for a particular blog post
    public function getUserCommentsByBlog()
    {

        // select query if student ID is provided
        $query = "SELECT * FROM $this->table_name WHERE user_id = $this->user_id AND blog_id = $this->blog_id";
            
        // prepare query statement
        $select_stmt = $this->conn->prepare($query);

        try {
            // execute query
            $select_stmt->execute();
            return ["output" => $select_stmt, "outputStatus" => 1000];
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        };
    }


    // Get all commentes
    public function getAllComments()
    {

        // select query if student ID is provided
        $query = "SELECT * FROM $this->table_name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        try {
            // execute query
            $stmt->execute();
            return ["output" => $stmt, "outputStatus" => 1000];
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "erorr" => "Netork issue. Please try again.", "outputStatus" => 1400];
        };
    }

    // create comment
    function createComment()
    {
        // query to insert record
        $query = "INSERT INTO $this->table_name (blog_id, user_id, comment, likes) VALUES (:blog_id, :user_id, :comment, :likes) ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":blog_id", $this->blog_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":comment", $this->comment);
        $stmt->bindParam(":likes", $this->likes);
      

        try {
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return ["output"=>true, "outputStatus"=>1000];
            } else {
                return ["output" => false, "outputStatus" => 1200];
            }        
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        }
    }
        
        
        
     // update a comment
    function updateComment()
    {
        $this->updated_at = date("Y-m-d H:i:sa");

        // update query
        $query = "UPDATE $this->table_name SET blog_id=:blog_id, user_id=:user_id, comment=:comment, likes=:likes, created_at=:created_at, updated_at=:updated_at WHERE comment_id = :comment_id";
        
        // prepare query statement
        $update_stmt = $this->conn->prepare($query);

        // bind values
        $update_stmt->bindParam(":blog_id", $this->blog_id);
        $update_stmt->bindParam(":user_id", $this->user_id);
        $update_stmt->bindParam(":comment", $this->comment);
        $update_stmt->bindParam(":likes", $this->likes);
        $update_stmt->bindParam(":created_at", $this->created_at);
        $update_stmt->bindParam(":updated_at", $this->updated_at);

        $update_stmt->bindParam(":comment_id", $this->comment_id);
     
        try {
            $update_stmt->execute();
            if($update_stmt->rowCount() > 0){
                return ["output"=>true, "outputStatus"=>1000];
            } else {
                return ["output" => false, "outputStatus" => 1200];
            }        
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        }
    }

        
     // update a comment
     function updateUserComment()
     {
        $this->updated_at = date("Y-m-d H:i:sa");

        // update query
        $query = "UPDATE $this->table_name SET blog_id=:blog_id, comment=:comment, likes=:likes, created_at=:created_at, updated_at=:updated_at WHERE comment_id = :comment_id AND user_id=:user_id";
        
        // prepare query statement
        $update_stmt = $this->conn->prepare($query);

        // bind values
        $update_stmt->bindParam(":blog_id", $this->blog_id);
        $update_stmt->bindParam(":user_id", $this->user_id);
        $update_stmt->bindParam(":comment", $this->comment);
        $update_stmt->bindParam(":likes", $this->likes);
        $update_stmt->bindParam(":created_at", $this->created_at);
        $update_stmt->bindParam(":updated_at", $this->updated_at);

        $update_stmt->bindParam(":comment_id", $this->comment_id);
     
        try {
            $update_stmt->execute();
            if($update_stmt->rowCount() > 0){
                return ["output"=>true, "outputStatus"=>1000];
            } else {
                return ["output" => false, "outputStatus" => 1200];
            }        
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        }
     }
       

    // delete a user
    function deleteComment()
    {
        // delete query
        $query = "DELETE FROM $this->table_name WHERE comment_id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind comment_id of record to delete
        $stmt->bindParam(1, $this->comment_id);

        try {
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return ["output"=>true, "outputStatus"=>1000];
            } else {
                return ["output" => false, "outputStatus" => 1200];
            }        
        } catch (Exception $e) {
            return ["output" => $e->getMessage(), "eror" => "Netork issue. Please try again.", "outputStatus" => 1400];
        }
    }


    // search for a particular comment(s) in a given column
    function searchComment($searchstring, $col)
    {
       $query = "SELECT * FROM comments WHERE $col LIKE '%$searchstring%'";

        // prepare query statement
        $search_stmt = $this->conn->prepare($query);

        try {
            // execute query
            $search_stmt->execute();

            return ["output" => $search_stmt, "outputStatus" => 1000];
        } catch (Exception $e) {

            return ["output" => $e->getMessage(), "outputStatus" => 1400];
        }
    }


}
