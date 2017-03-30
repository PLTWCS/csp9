<?php
// This block allows our program to access the MySQL database.
// Stores your login information in PHP variables
require_once '../login.php';
// Accesses the login information to connect to the MySQL server using your credentials and database
$db_server = mysql_connect($host, $username, $password);
// This provides the error message that will appear if your credentials or database are invalid
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db("shoes")
	or die("Unable to select database: " . mysql_error());
	
// Store the query string from 2.2.3.A Step 17
$query = "SELECT * FROM requests WHERE model_id=1";
		
// Searches the database returning results that match the query
// Results come in a table stored in $requests_for_model
$requests_for_model = mysql_query($query);
// The mysql_num_rows function returns an integer representation of number of rows for the table passed as an argument
$number_of_requests = mysql_num_rows($requests_for_model);

for ($current_row = 0; $current_row < $number_of_requests; $current_row++)
{
	// walker variable (through rows in the table)
	$request = mysql_fetch_row($requests_for_model);
	// query directed to get a table containing the one row that has a store matching the store ID in the current request
	$query = "SELECT * FROM store_info WHERE store_id='" . $request[1] . "'";
	$store_requesting = mysql_query($query); // A table containing the store
	$store = mysql_fetch_row($store_requesting); // Get the only row in the table (unique store ID number guarantees this)
	echo $store[2]; // output the 3rd item in the row for that store, which is the city
	if ($current_row < $number_of_requests - 1) // Makes sure commas are only printed after results that are not the last
	{
		echo ", ";
	}
} 

?>