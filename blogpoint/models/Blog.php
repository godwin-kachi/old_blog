<?php

class Blog
{

    private $table_name = "blogs";
    public $blog_id;
    public $title;
    public $content;
    public $image;
    public $user_id;
    public $tags;
    public $categories;
    public $click_count;
    public $active;
    public $created_at;
    public $updated_at;

    // db conn instance
    private $conn = NULL;


    // Constructor to assign conn
    public function __construct($db){
        $this->conn = $db;
    }

    public function getBlog()
    {

         $query = "SELECT * FROM $this->table_name WHERE blog_id=$this->blog_id";
            
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

    public function getUserBlogs()
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


    // Get all bloges
    public function getAllBlogs()
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

    // create user
    function createBlog()
    {
        // query to insert record
        $query = "INSERT INTO $this->table_name (title, content, image, user_id, tags, categories) VALUES (:title, :content, :image, :user_id, :tags, :categories) ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":tags", $this->tags);
        $stmt->bindParam(":categories", $this->categories);
      

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
        
        
        
     // update a blog
    function updateBlog()
    {
        $this->updated_at = date("Y-m-d H:i:sa");

        // update query
        $query = "UPDATE $this->table_name SET title=:title, content=:content, image=:image, user_id=:user_id, tags=:tags, categories=:categories, click_count=:click_count, active=:active, created_at=:created_at, updated_at=:updated_at WHERE blog_id = :blog_id";
        
        // prepare query statement
        $update_stmt = $this->conn->prepare($query);

        // bind values
        $update_stmt->bindParam(":title", $this->title);
        $update_stmt->bindParam(":content", $this->content);
        $update_stmt->bindParam(":image", $this->image);
        $update_stmt->bindParam(":user_id", $this->user_id);
        $update_stmt->bindParam(":tags", $this->tags);
        $update_stmt->bindParam(":categories", $this->categories);
        $update_stmt->bindParam(":click_count", $this->click_count);
        $update_stmt->bindParam(":active", $this->active);
        $update_stmt->bindParam(":created_at", $this->created_at);
        $update_stmt->bindParam(":updated_at", $this->updated_at);

        $update_stmt->bindParam(":blog_id", $this->blog_id);
     
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

        
     // update a blog
     function updateUserBlog()
     {
         $this->updated_at = date("Y-m-d H:i:sa");
 
         // update query
         $query = "UPDATE $this->table_name SET title, content, image, tags,categories, updated_at WHERE blog_id = :blog_id AND user_id = :user_id";
         
         // prepare query statement
         $update_stmt = $this->conn->prepare($query);
 
         // bind values
         $update_stmt->bindParam(":title", $this->title);
         $update_stmt->bindParam(":content", $this->content);
         $update_stmt->bindParam(":image", $this->image);
         $update_stmt->bindParam(":tags", $this->tags);
         $update_stmt->bindParam(":categories", $this->categories);
         $update_stmt->bindParam(":updated_at", $this->updated_at);
 
         $update_stmt->bindParam(":blog_id", $this->blog_id);
      
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
    function deleteBlog()
    {
        // delete query
        $query = "DELETE FROM $this->table_name WHERE blog_id = ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind blog_id of record to delete
        $stmt->bindParam(1, $this->blog_id);

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


    // search for a particular blog(s) in a given column
    function searchBlog($searchstring, $col)
    {
       $query = "SELECT * FROM blogs WHERE $col LIKE '%$searchstring%'";

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
