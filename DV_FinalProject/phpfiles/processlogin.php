
<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message

if (empty($_POST['username']) || empty($_POST['password']) || ((strip_tags($_POST['password'])!= '1234') AND (strip_tags($_POST['username'])!= '263004')) || (strip_tags($_POST['username'])== '263004' AND strip_tags($_POST['password'])!= '591')){
	$_SESSION['Error_Message']= "Invalid Username or Password!"; // Initializing Session
	header("location: http://visualrecommender.x10.bz/login.php");
}
else
{

	// Establishing Connection with Server by passing server_name, user_id and password as a parameter
	$servername = "localhost";
	$username = "aritral2";
	$password = "ra14je7sh9@G";
	$dbname = "visualre_csvdb";

	//create connection
	$connection = new mysqli($servername, $username, $password, $dbname);

	//Check connection 
	if($connection->connect_error){
		die("Connection failed" . $connection->connect_error);
	}else {
		//echo "Connection Successful.";
	}

	$username = strip_tags($_POST['username']);


	$query = "select * from stackdb where user_id =".$username;
	$result = $connection->query($query);

	if ($result->num_rows > 0) {
		$_SESSION['login_user']=$username; // Initializing Session

		header("location: http://visualrecommender.x10.bz"); // Redirecting To Other Page
	} else {
		//$error = "Username or Password is invalid";
		//echo $error;
		//Setting error message to be displayed to user
		$_SESSION['Error_Message']= "UserID does not exist!"; // Initializing Session
		header("location: http://visualrecommender.x10.bz/login.php");
	}
	mysqli_close($connection); // Closing Connection
}

?>