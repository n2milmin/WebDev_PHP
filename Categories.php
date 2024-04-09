<?php
session_start();
require_once ('Main.php');

global $categories;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories</title>
<link rel="stylesheet" href="cc.css">
</head>
<body>

<!-- Navbar -->
<div class="nav">
    <a href="index.php">Home</a>
    <a href="Learning_Path_Page.php">Learning Paths</a>
    <a class="active" href="Categories.php">Categories</a>
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
  <h2>Categories</h2>
</div>

<div class="row">
  <div class="grid-container">
  <?php foreach($categories as $category){ ?>
    <div class="grid-item">
      <h2><a href="Learning_Path_Page.php?category="><?php echo htmlspecialchars($category['category_name'])?></a></h2>
      <img alt="placeholder-image" src="https://clook.net/blog/wp-content/themes/clook/placeholders/blog-3.png" style="width: 400px; height: 250px; text-align: center">
    </div>
    <?php } ?>  
</div>

<div class="footer">
    <p>&copy; 2023 Learning Path Application. All rights reserved.</p>
</div>

</body>
</html>
