<?php
/*
 * @version 2015.5.14
 */
setcookie('username', $_SERVER['PHP_AUTH_USER'], time() - 2592000, '/');
session_start();
$_SESSION['logged'] = false;
echo "Thank you for visiting.  You have been logged out.</br>";
echo "Click <a href='222indexA.php'>here to go back to the index</a>.<br />";
?>