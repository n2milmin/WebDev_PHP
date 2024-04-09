<?php
class Learning_Path{ 
    // Members
    private $username;
    private $description;
    private $category;
    private $is_expert_certified;

    // Constructor
    public function __construct($username, $description, $category, $is_expert_certified){
        $this->username = $username;
        $this->description = $description;
        $this->category = $category;
        $this->is_expert_certified = $is_expert_certified;
    }

    // Magic method for getting properties
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    // Magic method for setting properties
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    // Methods
    private function set_expert_certification(){
        // must be logged in
        // bool 
        // refer to expert certification
    }

    public function create_learning_path(array $learning_paths, array $users, array $categories){
        // Collect data
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category = $_POST['category'];

            if(empty($title) || empty($description)){
                echo "Please fill in all areas.";
                return false;
            }
            else{
                // is expert 
                $is_expert = $users[$_SESSION['username']]['is_expert'];

                // add to array 
                $learning_paths[$title] = new Learning_Path($_SESSION['username'], $description, $category, $is_expert);

                // add to DB
                //DatabaseManager.addNewLearningPath($_SESSION['username'], $title, $description, $categories[$category]);
            }
        }
        else{
            // Error handling 
        }
    }

    public function delete_learning_path(array $learning_paths){
        // Collect data
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $title = $_POST['title'];

            // remove from array 
            unset($learning_paths[$title]);

            // remove from DB
            //DatabaseManager.removeLearningPath($_SESSION['username'], $title);
        }
        else{
            // Error handling 
        }
    }
}