<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

global $learningPaths;
global $users;
global $categories;

// Call the Object Classes
require_once ('User.php');
require_once ('Learning_Path.php');
require_once ('Resource.php');
//require_once ('Certification.php');
require_once ('Main.php');

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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="cc.css">
    <title>Home</title>
    <style>
        .row {
            display: flex;
            margin-left: auto;
            margin-right: auto;
            max-width: 1200px;
        }

        .grid-container {
            flex: 3;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 10px;
        }

        .grid-title {
            grid-column: 1 / -1;
            text-align: center;
            color: mediumseagreen;
        }

        .right-column {
            flex: 1;
            padding: 10px;
            margin-left: 10px;
        }

        .card {

            margin-bottom: 20px;
        }

        .grid-item img {
            max-width: 100%;
            height: auto;
        }

    </style>
</head>
<body>

<!-- Navbar -->
<div class="nav">
    <a class="active" href="index.php">Home</a>
    <a href="Learning_Path_Page.php">Learning Paths</a>
    <a href="Categories.php">Categories</a>
    <a href="Search.php">Search</a>
    <?php if(isset($_SESSION['username'])){ ?>
        <a class="left-btn" href="logout.php">Logout</a>
        <a class="left-btn" href="Profile_Page.php">Profile</a>
    <?php } 
    else{ ?>
        <a class="left-btn" href="Signup.php">Sign up</a>
        <a class="left-btn" href="Login.php">Login</a>
    <?php } ?>
</div>


<div class="header">
  <h2>Home Page</h2>
</div>

<div class="row">
  <div class="grid-container">
      <div class="grid-title">
          <h2>Featured Learning Paths</h2>
      </div>
          <?php foreach ($learningPaths as $path): ?>
          <div class="grid-item">
              <p><a href="Learning_Path_Page.php" style="color: #379361">
                      <?php echo htmlspecialchars($path['title']); ?>
                  </a></p>
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
    <div>
        <div class="grid-title">
            <h2 style="margin-bottom: 40px;">Categories</h2>
        </div>
        <div class="card">
            <?php foreach($categories as $category){ ?>
                <div class="grid-item">
                    <h2><a href="Categories.php?category=" style="color: mediumseagreen"><?php echo htmlspecialchars($category['category_name'])?></a></h2>
                </div>
            <?php } ?>
        </div>
    </div>
  </div>

<div class="footer">
    <p>&copy; 2023 Learning Path Application. All rights reserved.</p>
</div>

</body>
</html>
