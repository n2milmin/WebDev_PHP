<?php
session_start();
include_once('Main.php');
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
    <title>Search Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="cc.css">
</head>
<body>

<!-- Navbar -->
<div class="nav">
    <a  href="index.php">Home</a>
    <a href="Learning_Path_Page.php">Learning Paths</a>
    <a href="Categories.php">Categories</a>
    <a class="active" href="Search.php">Search</a>
    <?php if(isset($_SESSION['username'])){ ?>
        <a class="left-btn" href="#">Logout</a>
        <a class="left-btn" href="Profile_Page.php">Profile</a>
    <?php } else { ?>
        <a class="left-btn" href="Signup.php">Sign up</a>
        <a class="left-btn" href="Login.php">Login</a>
    <?php } ?>
</div>

<div class="header">
    <h1>Search Portal</h1>
</div>
<div style="text-align: center">

    <form method="post">
        <label for="search-bar"></label><br>
        <input type="text" name="search-bar" id="search-bar" placeholder="Search.." required/><br><br>
        <input type="radio" name="search-type" id="search-type-title" value="title" />
        <label for="search-type-title">Title Search</label>
        <input type="radio" name="search-type" id="search-type-description" value="description" />
        <label for="search-type-description">Description Search</label><br><br>
        <button type="submit" id="search-button">Search</button><br><br>
    </form>

    <?php
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchQuery = $_POST['search-bar'] ?? '';
        $searchType = $_POST['search-type'] ?? '';

        // Fetch the learning paths
        global $learningPaths;

        // Filter learning paths based on the search query and type
        $filteredLearningPaths = [];
        foreach ($learningPaths as $path) {
            if ($searchType === 'title' && stripos($path['title'], $searchQuery) !== false) {
                $filteredLearningPaths[] = $path;
            } elseif ($searchType === 'description' && stripos($path['description'], $searchQuery) !== false) {
                $filteredLearningPaths[] = $path;
            }
        }

        foreach ($filteredLearningPaths as $path): ?>
    <!-- Display the learning path -->
            <div>
                <h3><?php
                    if ($path['is_expert_certified'] == 1){
                        $certified = ' - ⭐ CERTIFIED ⭐';
                    } else {
                        $certified = '';
                    }
                    echo htmlspecialchars($path['title'] ?? 'No title') . $certified;?></h3>
                <p><?php echo htmlspecialchars($path['description']); ?></p>
                <!-- Use the user_id from the learning path to get the username with $usernamesById -->
                <p>Creator: @<?php
                    $creatorUserId = $path['user_id'] ?? null;
                    echo htmlspecialchars($usernamesById[$creatorUserId] ?? 'Unknown');
                    ?></p>
                <img alt="Placeholder Image" src="https://img.freepik.com/free-vector/placeholder-concept-illustration_114360-4727.jpg?size=626&ext=jpg&ga=GA1.1.1413502914.1696809600&semt=sph" style="width: 400px; height: 250px">

                <!-- Use the category_id from the learning path to get the category_name with $categoriesById -->
                <p>Category: <?php
                    $category_id = $path['category_id'] ?? null;
                    echo htmlspecialchars($categoriesById[$category_id] ?? 'Uncategorized');
                    ?></p>
                <p>Description: <?php echo htmlspecialchars($path['description'] ?? 'No description provided'); ?></p>
            </div>
    <?php endforeach; ?>
    <?php }?>


</div>

<div class="footer">
    <p>&copy; 2023 Learning Path Application. All rights reserved.</p>
</div>

</body>
</html>