<?php

require_once ('Main.php');
require_once ('Learning_Path.php');

global $learningPaths;

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
  <h2><?php echo $_GET['category']; ?></h2>
</div>

<div class="row">
    <div class="left-column">
        <?php foreach($learningPaths as $path => $value){
            if($value->category == $_GET['category']){ ?>
            <div class="card">
            <h2><a href="Learning_Path_Page.php?path='<?php echo $path;?>'"><?php echo $path; ?></a></h2>
            <div class="fakeimg" style="height:200px;">Image</div>
            <p>Creator: <?php echo $value->username; ?></p>
            <p>Category: <?php echo $value->category; ?></p>
            <p>Description: <?php echo $value->description; ?></p>
            </div>
        <?php }} ?>  
    </div>
</div>

<div class="footer">
  <h2>Footer</h2>
</div>

</body>
</html>
