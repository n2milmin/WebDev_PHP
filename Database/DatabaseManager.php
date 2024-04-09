<?php

include 'config.php';

class DatabaseManager {
    private $host;
    private $username;
    private $password;
    private $dbName;
    private $connection;

    public function __construct(){
        $this->host = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->dbName = DB_NAME;
    }

    public $usersDataSet = array();
    public $resourcesDataSet = array();
    public $resourceTypesDataSet = array();
    public $learningPathsDataSet = array();
    public $categoriesDataSet = array();
    public $votesDataSet = array();
    public $expertCertificationsDataSet = array();

    // Function used to establish connection to learningPathDB database
    public function connect() {
        // Create connection, using host, username, password & database name
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);
        // Test connection for errors
        if($this->connection->connect_error){
            die("Connection error: " . mysqli_connect_error());
        }

        // If the connection is successful, proceed to return statement
        return $this->connection;
    }

    // Function used to close connection to learningPathDB database
    public function disconnect(){
        if($this->connection){
            $this->connection->close();
        }
    }

    // Functions for acquiring specific datasets (Useful after updating data)

    public function fetchUsersDataSet(){
        $result = $this->connection->query("SELECT * FROM users;");
        if ($result){
            $this->usersDataSet = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function fetchResourcesDataSet(){
        $result = $this->connection->query("SELECT * FROM resources;");
        if ($result){
            $this->resourcesDataSet = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function fetchResourceTypesDataSet(){
        $result = $this->connection->query("SELECT * FROM resource_types;");
        if ($result){
            $this->resourceTypesDataSet = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function fetchLearningPathsDataSet(){
        $result = $this->connection->query("SELECT * FROM learning_paths;");
        if ($result){
            $this->learningPathsDataSet = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function fetchCategoriesDataSet(){
        $result = $this->connection->query("SELECT * FROM categories;");
        if ($result){
            $this->categoriesDataSet = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function fetchVotesDataSet(){
        $result = $this->connection->query("SELECT * FROM votes;");
        if ($result){
            $this->votesDataSet = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function fetchExpertCertificationsDataSet(){
        $result = $this->connection->query("SELECT * FROM expert_certifications;");
        if ($result){
            $this->expertCertificationsDataSet = $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    // Function used to fetch all data sets
    public function fetchAllDataSets(){
        // Connect to database
        $this->connect();

        $this->fetchUsersDataSet();
        $this->fetchLearningPathsDataSet();
        $this->fetchResourcesDataSet();
        $this->fetchResourceTypesDataSet();
        $this->fetchCategoriesDataSet();
        $this->fetchVotesDataSet();
        $this->fetchExpertCertificationsDataSet();

        // Disconnect from database
        $this->disconnect();
    }

    // Function used to authenticate user login, returns boolean value
    public function loginAuthentication($username, $password) {
        // Connect to database
        $this->connect();

        // Query to retrieve hashed password for given username
        $stmt1 = $this->connection->prepare("SELECT password FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // Execute query
        if ($stmt1->execute()) {
            $result = $stmt1->get_result();
            if ($row = $result->fetch_assoc()) {
                $hashedPassword = $row['password'];

                // Verify the provided password against the stored hash
                if (password_verify($password, $hashedPassword)) {
                    // Password is correct
                    $stmt1->close();
                    // Disconnect from database
                    $this->disconnect();
                    return true;
                }
            }
        }
        // Password is incorrect, or user does not exist
        $stmt1->close();

        // Disconnect from database
        $this->disconnect();
        return false;
    }

    public function acquireUserId($username){
        // Connect to database
        $this->connect();

        // Query used to acquire user_id from users table, before deleting user from database
        $stmt1 = $this->connection->prepare("SELECT user_id FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // If user is found in users table, store user's user_id in variable $userId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt1->get_result();
            $row = $result->fetch_assoc();
            // Return user_id of $username
            return $row['user_id'];
        } else {
            $userId = null;
        }
        $stmt1->close();

        // Disconnect from database
        $this->disconnect();

        // Return null value, if user does not exist
        return null;
    }

    public function createAccount($username, $password, $email, $bio, $picture){
        $this->connect();

        // Hash the new user's password, using default algorithm
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare & Bind parameters to prevent SQL injection for newly created user
        $stmt1 = $this->connection->prepare("INSERT INTO users(username,password,email,bio,picture,is_expert) VALUES(?,?,?,?,?,0);");
        $stmt1->bind_param("sss", $username, $hashedPassword, $email, $bio, $picture);

        // Execute query
        $stmt1->execute();
        $stmt1->close();


        // Update usersDataSet multidimensional array
        $this->fetchUsersDataSet();

        // Disconnect from database
        $this->disconnect();

        return true;
    }

    public function removeAccount($username){
        // Connect to learningPathDB database
        $this->connect();

        // Query used to acquire user_id from users table, before deleting user from database
        $stmt1 = $this->connection->prepare("SELECT user_id FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // If user is found in users table, store user's user_id in variable $userId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $userId = $this->acquireUserId($username);
        } else {
            $userId = null;
        }
        $stmt1->close();

        if($userId !== null) {
            // Query to remove user from users table in learningPathDB database
            $stmt2 = $this->connection->prepare("DELETE FROM users WHERE username=?;");
            $stmt2->bind_param("s", $username);
            // Execute query
            $stmt2->execute();
            $stmt2->close();
        }

        // Update $usersDataSet multidimensional array
        $this->fetchUsersDataSet();

        // Disconnect from learningPathDB database
        $this->disconnect();
    }

    public function getLearningPathsByUserId($username) {
        $userId = $this->acquireUserId($username);
        $stmt = $this->connection->prepare("SELECT * FROM learning_paths WHERE user_id=?;");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function addNewLearningPath($username, $title, $description, $category){
        // Connect to learningPathDB database
        $this->connect();

        // Query used to acquire user_id from users table
        $stmt1 = $this->connection->prepare("SELECT user_id FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // If user is found in users table, store user's user_id in variable $userId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $userId = $this->acquireUserId($username);
        } else {
            $userId = null;
        }
        $stmt1->close();

        // Query used to acquire category_id from categories table
        $stmt2 = $this->connection->prepare("SELECT category_id FROM categories WHERE category_name=?;");
        $stmt2->bind_param("s", $category);

        // If category is found in categories table, store category's category_id in variable $categoryId
        if($stmt2->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            // Store category_id in $categoryId variable
            $categoryId = $row['category_id'];
        } else {
            $categoryId = null;
        }
        $stmt2->close();

        if($userId !== null && $categoryId !== null){
            // Query used to create new learning path
            $stmt3 = $this->connection->prepare("INSERT INTO learning_paths(user_id,title,description,category_id,is_expert_certified) VALUES(?,?,?,?,0);");
            $stmt3->bind_param("issi", $userId,$title,$description,$categoryId);
            $stmt3->execute();
            $stmt3->close();
        }

        // Disconnect from learningPathDB database
        $this->disconnect();
    }

    public function removeLearningPath($username, $title){
        $this->connect();

        // Query used to acquire user_id from users table, using $username
        $stmt1 = $this->connection->prepare("SELECT user_id FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // If user is found in users table, store user's user_id in variable $userId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $userId = $this->acquireUserId($username);
        } else {
            $userId = null;
        }
        $stmt1->close();

        // Query used to acquire learning_path_id from learning_paths table, using $title
        $stmt2 = $this->connection->prepare("SELECT learning_path_id FROM learning_paths WHERE title=?;");
        $stmt2->bind_param("s", $title);

        // If learning path is found in learning_paths table, store learning_path_id in $learningPathId
        if($stmt2->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt1->get_result();
            $row = $result->fetch_assoc();
            // Store learning_path_id in $learningPathId variable
            $learningPathId = $row['learning_path_id'];
        } else {
            $learningPathId = null;
        }
        $stmt2->close();

        // Execute query, if $userId is NOT NULL
        if($userId !== null){
            // Query used to remove learning path from database
            $stmt3 = $this->connection->prepare("DELETE FROM learning_paths WHERE user_id=? AND title=?;");
            $stmt3->bind_param("is", $userId, $title);

            // Execute query to remove learning path
            $stmt3->execute();
            $stmt3->close();

            // Update $learningPathDataSet multidimensional array
            // $this->fetchLearningPathsDataSet();
        }

        if($learningPathId !== null){
            // Query used to delete all resource from resources table that were created for this learning path
            $stmt4 = $this->connection->prepare("DELETE FROM resources WHERE learning_path_id=?;");
            $stmt4->bind_param("i", $learningPathId);

            $stmt4->execute();
            $stmt4->close();
        }

        $this->disconnect();
    }

    public function addResource($pathTitle, $url, $description, $resourceType){
        // Connect to learningPathDB database
        $this->connect();
        // REQUIRED - learning_path_id; resource_type_id;

        // Query used to acquire learning_path_id from learning_paths table, using $pathTitle
        $stmt1 = $this->connection->prepare("SELECT learning_path_id FROM learning_paths WHERE title=?;");
        $stmt1->bind_param("s", $pathTitle);

        // If learning path is found in learning_paths table, store learning_path_id in $learningPathId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt1->get_result();
            $row = $result->fetch_assoc();
            // Store learning_path_id in $learningPathId variable
            $learningPathId = $row['learning_path_id'];
        } else {
            $learningPathId = null;
        }
        $stmt1->close();

        // Query used to acquire resource_type_id from resource_types table, using $resourceType
        $stmt2 = $this->connection->prepare("SELECT resource_type_id FROM resource_types WHERE resource_type_name=?;");
        $stmt2->bind_param("s", $resourceType);

        // If resource type is found in resource_types table, store resource_type_id in $resourceTypeId
        if($stmt2->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            // Store user_id in $userId variable
            $resourceTypeId = $row['resource_type_id'];
        } else {
            $resourceTypeId = null;
        }
        $stmt2->close();

        if($learningPathId !== null && $resourceTypeId !== null){
            // Query used to create new resource
            $stmt3 = $this->connection->prepare("INSERT INTO resources(learning_path_id, resource_type_id, url, resource_description) VALUES(?,?,?,?);");
            $stmt3->bind_param("iiss", $learningPathId, $resourceTypeId, $url, $description);

            // Execute query
            $stmt3->execute();
            $stmt3->close();
        }

        // Disconnect from learningPathDB database
        $this->disconnect();
    }

    public function removeResource($pathTitle){
        // Connect to database
        $this->connect();

        // Query used to acquire learning_path_id from learning_paths table, using $pathTitle
        $stmt1 = $this->connection->prepare("SELECT learning_path_id FROM learning_paths WHERE title=?;");
        $stmt1->bind_param("s", $pathTitle);

        // If learning path is found in learning_paths table, store learning_path_id in $learningPathId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt1->get_result();
            $row = $result->fetch_assoc();
            // Store learning_path_id in $learningPathId variable
            $learningPathId = $row['learning_path_id'];
        } else {
            $learningPathId = null;
        }
        $stmt1->close();

        // Query used to acquire resource_id from resources table, using $learningPathId
        $stmt2 = $this->connection->prepare("SELECT resource_id FROM resources WHERE learning_path_id=?;");
        $stmt2->bind_param("i", $learningPathId);

        // If learning_path_id is found in resources table, store correlating resource_id in $resourceId
        if($stmt2->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            // Store resource_id in $resourceId variable
            $resourceId = $row['resource_id'];
        } else {
            $resourceId = null;
        }
        $stmt2->close();

        if($learningPathId !== null && $resourceId !== null){
            // Query used to delete resource from resources table
            $stmt3 = $this->connection->prepare("DELETE FROM resources WHERE resource_id=? AND learning_path_id=?;");
            $stmt3->bind_param("ii", $resourceId, $learningPathId);

            $stmt3->execute();
            $stmt3->close();
        }

        // Disconnect from database
        $this->disconnect();
    }

    public function submitVote($username, $title, $vote){
        // Connect to database
        $this->connect();

        // Query used to acquire user_id from users table, using $username
        $stmt1 = $this->connection->prepare("SELECT user_id FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // If user is found in users table, store user's user_id in variable $userId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $userId = $this->acquireUserId($username);
        } else {
            $userId = null;
        }
        $stmt1->close();

        // Query used to acquire learning_path_id from learning_paths table, using $title
        $stmt2 = $this->connection->prepare("SELECT learning_path_id FROM learning_paths WHERE title=?;");
        $stmt2->bind_param("s", $title);

        // If learning path is found in learning_paths table, store learning_path_id in $learningPathId
        if($stmt2->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            // Store learning_path_id in $learningPathId variable
            $learningPathId = $row['learning_path_id'];
        } else {
            $learningPathId = null;
        }
        $stmt2->close();

        $stmt3 = $this->connection->prepare("SELECT * FROM votes WHERE user_id=? AND learning_path_id=?;");
        $stmt3->bind_param("ii", $userId, $learningPathId);
        $stmt3->execute();
        // Store query results to check for existing vote in votes table
        $resultCheck = $stmt3->get_result();
        $stmt3->close();

        if($resultCheck->num_rows == 0 && $userId !== null && $learningPathId !== null){
            // Check vote for boolean value, set to string due to vote_type value in table being ENUM data type
            if($vote){
                $voteType = 'upvote';
            } else {
                $voteType = 'downvote';
            }

            // Query used to insert user vote into votes table in learningPathDB database
            $stmt4 = $this->connection->prepare("INSERT INTO votes(vote_type, learning_path_id, user_id) VALUES(?,?,?);");
            $stmt4->bind_param("sii", $voteType, $learningPathId, $userId);
            $stmt4->execute();

            $stmt4->close();
        }

        // Disconnect from database
        $this->disconnect();
    }

    public function updateUserBiography($username, $bio){
        // Connect to database
        $this->connect();

        $userId = $this->acquireUserId($username);

        // Query used to modify bio in 'users' table
        $stmt1 = $this->connection->prepare("UPDATE users SET bio=? WHERE user_id=?;");
        $stmt1->bind_param("si", $bio, $userId);

        $stmt1->close();

        // Disconnect from database
        $this->disconnect();
    }

    public function updateUserProfilePicture($username, $picture){
        // Connect to database
        $this->connect();

        $userId = $this->acquireUserId($username);

        // Query used to acquire user_id from users table, using $username
        $stmt1 = $this->connection->prepare("UPDATE users SET picture=? WHERE user_id=?;");
        $stmt1->bind_param("si", $picture, $userId);
        $stmt1->close();

        // Disconnect from database
        $this->disconnect();
    }

    public function modifyLearningPath($username, $pathTitle, $modifiedTitle, $modifiedDescription){
        // Connect to database
        $this->connect();

        // Query used to acquire user_id from users table, using $username
        $stmt1 = $this->connection->prepare("SELECT user_id FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // If user is found in users table, store user's user_id in variable $userId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $userId = $this->acquireUserId($username);
        } else {
            $userId = null;
        }
        $stmt1->close();

        // Query used to acquire learning_path_id from learning_paths table, using $pathTitle
        $stmt2 = $this->connection->prepare("SELECT learning_path_id FROM learning_paths WHERE title=?;");
        $stmt2->bind_param("s", $pathTitle);

        // If learning path is found in learning_paths table, store learning_path_id in $learningPathId
        if($stmt2->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            // Store learning_path_id in $learningPathId variable
            $learningPathId = $row['learning_path_id'];
        } else {
            $learningPathId = null;
        }
        $stmt2->close();

        if($userId !== null && $learningPathId !== null){
            // Query used to update learning path title
            $stmt3 = $this->connection->prepare("UPDATE learning_paths SET title=? WHERE learning_path_id=? AND user_id=?;");
            $stmt3->bind_param("sii", $modifiedTitle, $learningPathId, $userId);
            $stmt3->execute();
            $stmt3->close();

            // Query used to update learning path description
            $stmt4 = $this->connection->prepare("UPDATE learning_paths SET description=? WHERE learning_path_id=? AND user_id=?;");
            $stmt4->bind_param("sii", $modifiedDescription, $learningPathId, $userId);
            $stmt4->execute();
            $stmt4->close();
        }

        // Disconnect from database
        $this->disconnect();
    }

    public function certifyLearningPath($username, $pathTitle){
        // Connect to database
        $this->connect();

        // Query used to acquire user_id from users table, using $username
        $stmt1 = $this->connection->prepare("SELECT user_id FROM users WHERE username=?;");
        $stmt1->bind_param("s", $username);

        // If user is found in users table, store user's user_id in variable $userId
        if($stmt1->execute()) {
            // Store resulting data set in $result variable
            $userId = $this->acquireUserId($username);
        } else {
            $userId = null;
        }
        $stmt1->close();

        // Query used to acquire learning_path_id from learning_paths table, using $pathTitle
        $stmt2 = $this->connection->prepare("SELECT learning_path_id FROM learning_paths WHERE title=?;");
        $stmt2->bind_param("s", $pathTitle);

        // If learning path is found in learning_paths table, store learning_path_id in $learningPathId
        if($stmt2->execute()) {
            // Store resulting data set in $result variable
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            // Store learning_path_id in $learningPathId variable
            $learningPathId = $row['learning_path_id'];
        } else {
            $learningPathId = null;
        }
        $stmt2->close();

        if($userId !== null && $learningPathId !== null){
            $stmt3 = $this->connection->prepare("UPDATE learning_paths SET is_expert_certified=1 WHERE learning_path_id=? AND user_id=?;");
            $stmt3->bind_param("ii", $learningPathId, $userId);
            $stmt3->execute();
            $stmt3->close();
        }

        // Disconnect from database
        $this->disconnect();
    }
}

?>