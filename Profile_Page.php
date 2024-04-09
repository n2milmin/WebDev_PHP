<?php
session_start();

require_once ('User.php');
require_once ('Database/DatabaseManager.php');

$database = new DatabaseManager();
global $learningPaths;
global $users;
global $categories;

$user_id = $_SESSION['username'] ?? null;

$myLearningPaths = [];
$myLearningPaths = $database->getLearningPathsByUserId($_SESSION['username']);

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

$currentUserPicturePath = '';
foreach ($users as $user) {
    if ($user['username'] === $_SESSION['username']) {
        $currentUserPicturePath = $user['picture'];
        break; // Stop loop once username is found in $users array
    }
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
            flex-wrap: wrap;
            margin-left: auto;
            margin-right: auto;
            max-width: 1200px;
        }

        .main-content {
            flex: 3;
            padding: 10px;
        }

        .sidebar {
            flex: 1;
            padding: 10px;
            margin-top: 50px;
        }

        .grid-title {
            text-align: center;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
        }

        .grid-title {
            width: 100%;
        }

        .profile-picture-container {
            float: left;
            margin-left: 50px;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .header:after {
            content: "";
            display: table;
            clear: both;
        }

        @media screen and (max-width: 790px) {
            #name {
                font-size: 30px;
            }
        }

        @media screen and (max-width: 650px) {
            #name {
                font-size: 25px;
                padding-top: 100px;
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
        <a class="left-btn" href="logout.php">Logout</a>
        <a class="left-btn" href="Profile_Page.php">Profile</a>
    <?php }
    else{ ?>
        <a class="left-btn" href="Signup.php">Sign up</a>
        <a class="left-btn" href="Login.php">Login</a>
    <?php } ?>
</div>

<div class="header">
    <div class="profile-picture-container">
        <img src="assets/images/<?php if(isset($currentUserPicturePath)){ echo htmlspecialchars($currentUserPicturePath);} else { echo 'https://t3.ftcdn.net/jpg/02/16/81/40/360_F_216814074_Cubdtuf7JF3Z7AxHIbTzjNxRUndU2QN5.jpg'; }?>" alt="Profile Picture" class="profile-picture"/>
        <form action="Profile_Page.php" method="post">
            <input type="file" name="profile_picture" /><br>
            <button type="submit" name="submit">Update Profile Picture</button>
        </form>
    </div>
    <div id="name">
        <?php echo "<h2>@" . $_SESSION['username'] . "'s Profile</h2>" ?>
    </div>

</div>

<div class="row">
    <div class="main-content">
        <div class="grid-title">
            <h2 style="color: mediumseagreen">Featured Learning Paths</h2>
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
    <div class="sidebar">
        <div class="card">
            <h2 style="color: mediumseagreen">My Learning Paths</h2>
            <!-- Loop through the user's learning paths -->
            <?php foreach ($myLearningPaths as $path): ?>
                <p><a href="Learning_Path_Page.php?pathId=<?php echo htmlspecialchars($path['id']); ?>">
                        <?php echo htmlspecialchars($path['title']); ?>
                    </a></p>
            <?php endforeach; ?>
            <p><a href="Learning_Path_Page.php">Explore Learning Path Center...</a></p><br><br>
            <div class="grid-title">
                <h2><a href="Create_Learning_Path.php" style="color: mediumseagreen">Create Learning Path</a></h2>
            </div>
            <div class="grid-title">
                <h2><a href="Create_Resource.php" style="color: mediumseagreen">Add New Resource</a></h2>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2023 Learning Path Application. All rights reserved.</p>
</div>

</body>
</html>