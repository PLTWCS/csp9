<?php
// Demonstrate a single SQL call from PHP
    
// Accesses the login information to connect to the MySQL server using your credentials and database
require_once '../login.php';
$connection = mysql_connect($host, $username, $password);




// Perform a simple query to make sure it's working
$query = "SELECT * FROM artists";
$result = mysqli_query($connection, $query);

//Print the result. The variable row iterates through the results. 
//A row's columns are accessed like a Python key-value dictionary using a PHP "associative array"
while ($row = mysqli_fetch_assoc($result)) {
    echo "The ID is: " . $row['artistID'] . " and the Username is: " . $row['username'];
}

?>