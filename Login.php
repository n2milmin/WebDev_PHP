<?php
session_start();

require_once ('User.php');
require_once ('Main.php');

$database = new DatabaseManager();

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $username = $_POST["username"];
  $password = $_POST["password"];

  if($database->loginAuthentication($username, $password)){
    $_SESSION["username"]= $username;

    if(isset($_SESSION['login_error']))
      unset($_SESSION['login_error']);

    header('Location: index.php');
    exit();
  }
  else{
    $_SESSION['login_error'] = "Invalid username or password.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
<style>
body {
    display: flex;
    margin-top: 100px;
    justify-content: center;
    align-items: center;
    font-family: Arial, Helvetica, sans-serif;
}
form {
    border: 3px solid #f1f1f1;
    width: 500px;
}

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

button:hover {
  opacity: 0.8;
}

.cancel-btn {
    text-decoration: none;
    color: white;
    padding: 14px 20px;
    background-color: #f44336;
    display: block;
    text-align: center;
    border: none;
    cursor: pointer;
}

.img-container {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
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

<form action="Login.php" method="post">
  <div class="img-container">
    <h2>Login</h2>
  </div>

  <?php if (isset($_SESSION['login_error'])): ?>
    <p><?php echo $_SESSION['login_error']; ?></p>
  <?php endif; ?>

  <div class="container">
    <label for='username'><b>Username</b></label>
    <input type="text" id="username" placeholder="Enter Username" name="username" required>

    <label for='password'><b>Password</b></label>
    <input type="password" id="password" placeholder="Enter Password" name="password" required>
        
    <button type="submit">Login</button>
      <a href="index.php" class="cancel-btn">Cancel</a>
  </div>
</form>

</body>
</html>