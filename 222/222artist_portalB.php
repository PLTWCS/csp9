<?php
/**
 * CSP Activity 2.2.2 IntroducingPHP
 * 
 * 222artist_portalB.php allows users with artist accounts to upload images and modify data 
 * @copyright 2014 Project Lead The Way
 * 
 */

 /* 
This block allows our program to access the MySQL database.
Elaborated on in 2.2.3.
 */
require_once '../login.php';
$db_server = mysql_connect($host, $username, $password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($dbname)
	or die("Unable to select database: " . mysql_error());
	
	
// This function allows this page to use a Session to store data in cookies for the user
session_start();


// $_SERVER is an array of data to be used by the server
// The 'PHP_AUTH_USER' and 'PHP_AUTH_PW' were recorded when the user entered their 
//  artist account credentials upon HTTP authentication
if (isset($_SERVER['PHP_AUTH_USER']) &&
	isset($_SERVER['PHP_AUTH_PW']) && 
	$_SESSION['logged'] )  //Check the flag variable to see if user has been logged in
{
	// Verify that the user name and password combination is correct
	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];
	
	// Creates a cookie named 'username' and sets its expiry time to be one day in the future.
	// 60 seconds times 60 minutes times 24 hours plus the current time
	setcookie('username', $username, time()+60*60*24,'/');	

	// Uses a MySQL query gathering data to use in the following conditional
	$query = "SELECT * FROM artists WHERE username='$username'";
	$result = mysql_query($query);
	
	// No user with that name exists in the database
	if (!$result) die ("Database access failed: " . mysql_error());
	// A user with that name does exist in the database
	elseif (mysql_num_rows($result))
	{
		$row = mysql_fetch_row($result); 
		// verifies that the password matches the user name's password. 
		// The second element in the array returned by the query in the line above represents the password.
		if ($password == $row[2])
		{
			// The user has entered a non-empty string for their first name in the form.
			if (isset($_POST['firstname']) && get_post('firstname') != '')
			{
				// Attempt to update the first name of the artist
				$query = "UPDATE artists SET " . 
					"firstname='" . get_post('firstname') . 
					"' WHERE username='" . $row[1] . "'";
				if (!mysql_query($query, $db_server))
					echo "UPDATE failed: $query<br />" .
					mysql_error() . "<br /><br />";
			}
			
			// The user has entered a non-empty string for their last name in the form.			
			if (isset($_POST['lastname']) && get_post('lastname') != '')
			{
				// Attempt to update the last name of the artist
				$query = "UPDATE artists SET " . 
					"lastname='" . get_post('lastname') . 
					"' WHERE username='" . $row[1] . "'";
				if (!mysql_query($query, $db_server))
					echo "UPDATE failed: $query<br />" .
					mysql_error() . "<br /><br />";		
			}
			
			// Setting various session data for use in the other pages of this site and/or another
			// running of this PHP program.
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			$_SESSION['firstname'] = $row[3];
			$_SESSION['lastname'] = $row[4];
			
			// Links for navigation to the other pages in the site.
			echo "Click <a href='222indexB.php'>here to go back to the index</a>.<br />";
			echo "Click <a href='222logoutB.php'>here to Log Out</a>...<br />";			
			echo "$row[3] $row[4] : Hi $row[3],
				you are now logged in as '$row[1]'";
			// Creates form fields to change artist info using the POST method.
			echo '<form action="222artist_portalB.php" method="post" enctype="multipart/form-data"><pre>';
			echo 'First Name <input type="text" name="firstname" />	' . $row[3];
			echo '<br />Last Name <input type="text" name="lastname" />	' . $row[4];
			echo '<br /><input type="submit" value="CHANGE INFO" />'; 
			// Allows user to select a file to upload and upload it.
			echo '<br />Select Image to Upload: <input type="file" name="filename" />' .
				'<input type="submit" value="UPLOAD IMAGE" /></pre></form>';
			
			// The user is uploading a file		
			if ($_FILES)
			{
				// The user has not previously uploaded files
				// Determined by the absence of a folder with their user name.
				if (!file_exists("/home/ubuntu/workspace/" . $username . "/"))
				{
					// Create directory named with the user's user name and makes it accessible.
					// See www.php.net/mkdir for more information about the arguments.
					mkdir("/home/ubuntu/workspace/" . $username . "/", 0777, true);
				}
				// $_FILES is a 2D array storing information about uploaded files.
				$name = $_FILES['filename']['name'];
				// The name of the file is non-empty
				if ($name != '')
				{
					// Moves the file identified in argument 0 to the location specified in argument 1.
					move_uploaded_file($_FILES['filename']['tmp_name'], "/home/ubuntu/workspace/" . $username . "/" . $name);
					
					// Strips the extension from the filename and stores the result to help create a thumbnail name.
					$no_extension_name = pathinfo($name);
					// Creates a string that will represent the path to and name of the thumbnail for the image.
					$thumbname = $username . "/" . $no_extension_name['filename'] . "thumb.jpg";
					
					// Output to show the user the file they uploaded.
					$domain = $_SERVER['SERVER_NAME'];
					echo "Uploaded image '$username/$name'<br /><img src='http://$domain/$thumbname' />";
					
					// Find the artistID associated with the username
					$query = "SELECT * FROM artists WHERE username='" . $username ."'";
					$result = mysql_query($query, $db_server);
					if (mysql_num_rows($result)){
						$row = mysql_fetch_row($result);
						$userID = $row[0];
						// Query to find if the image name already exists in the database.
						$query = "SELECT * FROM images WHERE filename='" . $name . 
							"' AND userID='" . $userID . "'";
						$result = mysql_query($query, $db_server);
						// The image name was not found in the database
						if (!$result)
						{
							// Add information about the image to the database
							$query = "INSERT INTO images(imageID, artistID, filename, thumbname) VALUES('','" . 
								$userID . "', '" . $name . "', '" . $no_extension_name['filename'] . "thumb.jpg')";
							// The update of the database fails.
							if (!mysql_query($query, $db_server)){
								echo "UPDATE failed: $query<br />" .
								mysql_error() . "<br /><br />";	
							}
							// Run the python script to create a thumbnail image of the uploaded image.
							popen('python thumbs.py ' . $username . ' ' . $name, 'w'); 
						}
					}
				}
			}
		}
		else 
		$_SESSION['logged'] = false;
		die("");//Invalid username/password combination
		
	}
	else{ 
	$_SESSION['logged'] = false;
	die("Invalid username/password combination");
	}
}
else
{
	// Function call sends raw HTTP headers.
	header('WWW-Authenticate: Basic realm="Restricted to Artists"');
	header('HTTP/1.0 401 Unauthorized');
	//raise the flag
	$_SESSION['logged'] = true;
	die ("Please enter your username and password");
}

echo "</body></html>";

// MySQL function to close the database when we're done with it.
mysql_close($db_server);

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
