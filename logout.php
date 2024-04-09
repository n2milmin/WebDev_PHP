<?php

session_start();
unset($_SESSION['username']);

header('Location: Learning_Path_Page.php');
exit();
