<?php
// Start the session
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// require_once 'Learning_Path_Page.php';
include 'Main.php';
include 'Learning_Path.php';

global $learningPaths;
global $categories;
global $users;
global $resources;
global $resourceTypes;

// Used for debugging purposes
// $_SESSION['username'] = 'johndoe';

if (!isset($_SESSION['username'])) {
    header('Location: Login_Popup.php');
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = $_POST["title"];
    $category = $_POST["category"];
    $description    = $_POST["description"];
    // $expertStatus = $users[$_SESSION['username']]['is_expert'] ? 1 : 0;

    $newLearningPath = new Learning_Path ($_SESSION['username'], $description, $category, 0);
    $learningPaths['title']=$newLearningPath;

    header('Location: Learning_Path_Page.php?category=' . $category);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="cc.css">
    <title>Add Resource</title>
    <style>

        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            background-color: #04AA6D;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        #description {
            width: 100%;
            height: 200px;
        }

        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }
            .cancel-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="nav">
    <a href="index.php">Home</a>
    <a href="Learning_Path_Page.php">Learning Paths</a>
    <a href="Categories.php">Categories</a>
    <a href="Search.php">Search</a>
    <?php if(isset($_SESSION['username'])){ ?>
        <a class="left-btn" href="#">Logout</a>
        <a href="Profile_Page.php">Profile</a>
    <?php }
    else{ ?>
        <a class="left-btn" href="Signup.php">Sign up</a>
        <a class="left-btn" href="Login.php">Login</a>
    <?php } ?>
</div>


<div class="header">
    <h2>Add New Resource</h2>
</div>

<div class="row">
    <div class="card">
        <form action="Create_Resource.php" method="post">
            <label for="title"><strong>Title</strong></label>
            <input type="text" id="title" name="title" required>

            <br><br>

            <label for="category"><strong>Select Type</strong></label>
            <select class="category" id="category" name="category" required>
                <option disabled selected>Type</option>
                <?php foreach ($resourceTypes as $resourceType) { ?>
                    <option value="<?php echo htmlspecialchars($resourceType['resource_type_name']); ?>">
                        <?php echo htmlspecialchars($resourceType['resource_type_name']); ?>
                    </option>
                <?php } ?>
            </select>


            <br><br><br>

            <label for="description"><strong>URL</strong></label><br><br>
            <textarea id="description" name="description" required></textarea>

            <br>

            <br>

            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<div class="footer">
    <p>&copy; 2023 Learning Path Application. All rights reserved.</p>
</div>

</body>
</html>