<?php
// Update URL & Update description
// must be an object class
// must be descendant of learning path 
// must reference only one learning path 
// resources are learning path specific 

class Resource{ 
    // Members
    private string  $learning_path;
    private string  $type;
    private string  $url;
    private string  $description;

    // Constructors
    private function __construct($learning_path, $type, $url, $description){
        $this->learning_path        = $learning_path;
        $this->type                 = $type;
        $this->url                  = $url;
        $this->description          = $description;
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

    private function add_resource(array $resources){
        // Collect info
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $title = $_POST['title'];
            $learning_path = $_POST['learning_path']; // provided 
            $type = $_POST['type']; // provided
            $url = $_POST['url'];
            $description = $_POST['description'];

            if(empty($title)|| empty($url) || empty($description)){
                echo "Please fill in all areas.";
            }
            else{
                // add too array
                $resources[$title] = new Resource($learning_path, $type, $url, $description);

                // add to DB 
                DatabaseManager.addResource($learning_path, $url, $description, $type);
            }
        }
        else{
            // Error handling 
        }
    }

    private function remove_resource(array $resources){
        // Collect data
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $title = $_POST['title'];

            
            // Remove from array 
            unset($resources, $title);

            // remove from DB 
            DatabaseManager.removeResource($title);

            // error handling 
        }
    }
}