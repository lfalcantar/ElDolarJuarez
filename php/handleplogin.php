<?php
	session_start();
	// First start a session. This should be right at the top of your login page.
	require_once("database.php");
	// Check to see if this run of the script was caused by our login submit button being clicked.
	if (isset($_POST['login-submit'])) {
		// Also check that our email address and password were passed along. If not, jump
		// down to our error message about providing both pieces of information.
		if (isset($_POST['emailaddress']) && isset($_POST['pass'])) {
			$email = $_POST['emailaddress'];
			$pass = $_POST['pass'];
			// sanitize input
 			$email = $db->escape_string($email);
 			$pass = $db->escape_string($pass);
 			$pass = sha1($pass);
			$sql = "SELECT USERNAME,PASSWORD FROM USERS WHERE USERNAME='".$email."' and PASSWORD='".$pass."'";
			$result;
			if(!$result = $db->query($sql)){
				header("location: ../loginEntrar.php");
			}else{
				$row = $result->fetch_array();
 				$count = $result->num_rows;
 				if($count == 1){
					$_SESSION['is_auth'] = true;
					error_log(print_r("user ".$row['USERNAME'], TRUE)); 
					header('location: updatedatabase.php');
				}
				else{
					header("location: ../loginEntrar.php");
				}
			}
			$result->free();
		}
		else {
			$error = "Please enter an email and password to login.";
			header("location: ../loginEntrar.php");
		}
	}else{
		header("location: ../loginEntrar.php");
	}
?>