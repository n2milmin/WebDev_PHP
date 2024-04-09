<?php
// Cast, Count, Verify 
class Vote{
    // Members 
    private int     $vote_id;
    private bool    $vote_type; // ENUM 
    private int     $learning_path_id;
    private int     $user_id;
    
    // Constructor
    public function __construct($vote_id, $vote_type, $learning_path_id, $user_id){
        $this->vote_id          = $vote_id;
        $this->vote_type        = $vote_type;
        $this->learning_path_id = $learning_path_id;
        $this->user_id          = $user_id;
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
    private function cast_vote(){
        // must be logged in
        // positive or negative vote 
        // one vote per user per learnin path
        // reference user and user profile 
    }

    private function count_votes(){
        // reference learning path array
        // return votes 
    }
}