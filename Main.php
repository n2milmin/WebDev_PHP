<?php
// Include necessary files and classes
//include ('User.php');
//include ('Learning_Path.php');
//include ('Resource.php');
//include ('Certification.php');
//include ('Search.php');
//include ('Vote.php');
include_once ('Database/DatabaseManager.php');
// include ('UI Design/index.php');

// Create an instance of the DatabaseManager
$database = new DatabaseManager();

// Call the method to fetch all datasets
$database->fetchAllDataSets();

// Assign fetched data to arrays
$users = $database->usersDataSet;
$learningPaths = $database->learningPathsDataSet;
$resources = $database->resourcesDataSet;
$categories = $database->categoriesDataSet;
$votes = $database->votesDataSet;
$expertCertifications = $database->expertCertificationsDataSet;
$resourceTypes = $database->resourceTypesDataSet;

// Include the UI Design
// include ('UI Design/index.php');