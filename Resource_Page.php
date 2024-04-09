<?php
session_start();

require_once ('Main.php');
// require_once ('Learning_Path.php');

global $resources
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="cc.css">
</head>
<body>

<!-- Navbar -->
<div class="nav">
    <a href="index.php">Home</a>
    <a href="Learning_Path_Page.php">Learning Paths</a>
    <a class="active" href="#">Resources</a>
    <a href="Search.php">Search</a>
    <?php if(isset($_SESSION['username'])){ ?>
        <a class="left-btn" href="logout.php">Logout</a>
        <a class="left-btn" href="UI Design/Profile_Page.php">Profile</a>
    <?php } 
    else{ ?>
        <a class="left-btn" href="UI Design/SignUp.php">Sign up</a>
        <a class="left-btn" href="Login.php">Login</a>
    <?php } ?>
</div>


<div class="header">
  <h2>Resources in <?php echo $_GET['path']; ?></h2>
</div>

<div class="row">
  <div class="left-column">
    <?php foreach($resources as $resource => $value){ 
      if($value->learning_path == $_GET['path']){ ?>
        <div class="card">
          <h2><?php echo $resource; ?></h2>
          <h4><?php echo $value->type; ?></h4>
          <?php if($value->is_expert_certified){ ?>
            <h4>Expert Certified!</h4>
          <?php } ?>
          <div class="fake-img" style="height:200px;">Image</div>
          <p>URL: <a href="<?php echo $value->url; ?>"><?php echo $value->url; ?></a></p>
          <p>Description: <?php echo $value->description; ?></p>
        </div>
      <?php }
    } ?>  
  </div>
</div>

<div class="footer">
    <p>&copy; 2023 Learning Path Application. All rights reserved.</p>
</div>

</body>
</html>
