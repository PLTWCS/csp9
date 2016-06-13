<?php
/**
 * CSP Activity 2.2.2 IntroducingPHP
 * 
 * 222account_creation.php allows users to create artist accounts
 * @copyright 2014 Project Lead The Way
 * @version 2014.5.21
 */

 // Links for navigation between the index, the artist portal and account creation
echo "Click <a href='222artist_portalA.php'>here to log in as an artist</a>.<br />";
echo "Or click <a href='222indexA.php'>here to go back to the index</a>.<br />";

/* 
This block allows our program to access the MySQL database.
Elaborated on in 2.2.3.
 */
require_once '../login.php';
$db_server = mysql_connect($host, $username, $password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($dbname)
	or die("Unable to select database: " . mysql_error());

// Only one use case, the user must have entered an account name, password, and confirmed it
// in order to create an account	
if (isset($_POST['new_user']) &&
	isset($_POST['new_pwd']) &&
	isset($_POST['conf_pwd']))
{
	// The get_post function is defined below to collect data from the POST protocol.
	$new_user = get_post('new_user');
	$new_pwd = get_post('new_pwd');
	$conf_pwd = get_post('conf_pwd');
	
	// Verify that the password matches the password entered in the confirmation field.
	if ($new_pwd == $conf_pwd)
	{
		// MySQL query retrieval examined in 2.2.3
		// returns any database row matching the desired user name
		$query = "SELECT * FROM artists WHERE username='" . $new_user . "'";
		$result = mysql_query($query, $db_server);
		$row = mysql_fetch_row($result);
		
		// Checks to make sure that there were no entries in our database for the desired user name
		if (!$row)
		{
			// This conditional block creates the new account in the database if possible
			$query = "INSERT INTO artists VALUES" .
				"('','$new_user', '$new_pwd', '', '')";
			if (!mysql_query($query, $db_server))
				echo "INSERT failed: $query<br/>" .
					mysql_error() . "<br /><br />";
			else
			{
				echo "<br />Account created successfully.";
			}
		}
		else
		{
			echo "That username is already taken.";
		}
	}
	else
	{
		echo "Password confirmation invalid.";
	}
}
// Creates the form for account creation. Specifies the POST method.
echo <<<_END
<form action ="222account_creationA.php" method="post"><pre>
User Name: <input type="text" name="new_user" />
Password: <input type="password" name="new_pwd" />
Confirm Password: <input type="password" name="conf_pwd" />	
<input type="submit" value="SUBMIT" />
</pre></form>
_END;

/** 
 * Quality of life function to reduce the amount of code needed to retrieve POST data
 * 
 * @param string $var the name of the element in the POST array to retrieve
 * @return string
 */
function get_post($var)
{
	return mysql_real_escape_string($_POST[$var]);
}
?>