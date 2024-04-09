<?php
require_once ('Main.php');

class User{
    // Members
    private $password;
    private $email;
    private $bio;
    private $picture;
    private $is_expert;

    // Constructor
    public function __construct($password, $email, $bio = null, $picture = null, $is_expert = false){
        $this->password = $password;
        $this->email    = $email;
        $this->bio            = $bio;
        $this->picture        = $picture;
        $this->is_expert      = $is_expert;
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
    public static function register(array $users, $username, $password, $email){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Create the new user in the users array
        $users[$username] = new User($hashed_password, $email);

        //Add to DB
        // DatabaseManager.createAccount($username, $password, $email, null, null); // b/c it's not in /Program

        $_SESSION["username"]= $username;

        return true;
    }

    private function delete_account(array $users){ // needs exception handling
        // Remove from array
        unset($users[$_SERVER['username']]);

        // remove from DB
        // $database->removeAccount($_SERVER['username']); //not part of /Program

        // logout user
        session_destroy();
    }

    public static function login(array $users, $username, $password){
        if(array_key_exists($username, $users ) && password_verify($password, $users['username']->password))
            return true;
        else
            return false;
    }

    private function change_password(array $users){
        // collect data 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $old_password = $_POST["old_password"];
            $new_password = $_POST["new_password"];

            // validate 
            if(empty($old_password) || empty($new_password)){
                echo "Please fill in all areas.";
                return false;
            }
            else{
                $hashed_old_password = password_hash($old_password, PASSWORD_DEFAULT);

                if($hashed_old_password == $users[$_SERVER['username']]['password']){ // check password against current one
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // safety
                    //set new password
                    $users[$_SERVER['username']]->password = $hashed_password;
                    return true;
                }
                else{
                    echo "Current password does not match.";
                    return false;
                }
            }
        }
        else{
            // Error handling if necessary 
        }
    }
}