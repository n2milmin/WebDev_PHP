<?php
session_start();
include('Main.php');
// include_once('../Database/DatabaseManager.php');

global $learningPaths;
global $users;
global $categories;

// Create a map of user IDs to usernames
$usernamesById = [];
foreach ($users as $user) {
    $usernamesById[$user['user_id']] = $user['username'];
}

// Create a map of category IDs to category names
$categoriesById = [];
foreach ($categories as $category) {
    $categoriesById[$category['category_id']] = $category['category_name'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Learning Path Center</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="cc.css">
</head>
<body>

<!-- Navbar -->
<div class="nav">
    <a  href="index.php">Home</a>
    <a class="active" href="Learning_Path_Page.php">Learning Paths</a>
    <a href="Categories.php">Categories</a>
    <a href="Search.php">Search</a>
    <?php if(isset($_SESSION['username'])){ ?>
        <a class="left-btn" href="#">Logout</a>
        <a class="left-btn" href="Profile_Page.php">Profile</a>
    <?php } else { ?>
        <a class="left-btn" href="Signup.php">Sign up</a>
        <a class="left-btn" href="Login.php">Login</a>
    <?php } ?>
</div>

<div class="header">
    <h1>Learning Path Center</h1>
</div>
<div class="grid-container">
    <?php foreach ($learningPaths as $path): ?>
        <div class="grid-item">
            <h3><?php
                if ($path['is_expert_certified'] == 1){
                    $certified = ' - ⭐ CERTIFIED ⭐';
                } else {
                    $certified = '';
                }
                echo htmlspecialchars($path['title'] ?? 'No title') . $certified;?></h3>


            <!-- Use the user_id from the learning path to get the username with $usernamesById -->
            <p>Creator: @<?php
                $creatorUserId = $path['user_id'] ?? null;
                echo htmlspecialchars($usernamesById[$creatorUserId] ?? 'Unknown');
                ?></p>
            <img alt="Placeholder Image" src="https://img.freepik.com/free-vector/placeholder-concept-illustration_114360-4727.jpg?size=626&ext=jpg&ga=GA1.1.1413502914.1696809600&semt=sph">

            <!-- Use the category_id from the learning path to get the category_name with $categoriesById -->
            <p>Category: <?php
                $category_id = $path['category_id'] ?? null;
                echo htmlspecialchars($categoriesById[$category_id] ?? 'Uncategorized');
                ?></p>
            <p>Description: <?php echo htmlspecialchars($path['description'] ?? 'No description provided'); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="footer">
    <p>&copy; 2023 Learning Path Application. All rights reserved.</p>
</div>

</body>
</html>