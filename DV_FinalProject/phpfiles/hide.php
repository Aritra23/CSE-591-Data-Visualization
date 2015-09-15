
<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message

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

$id = 0;
$title = $_POST['title'];
$query = "SELECT id, title FROM stackdb WHERE title = '".$title."' AND type = 'question' LIMIT 1";
$result = $connection->query($query);
if ($result->num_rows > 0){
	$row = $result->fetch_assoc();
	$id = $row['id'];
}else {
	//Fulltext index search if string match fails
	$query = "SELECT id, title FROM stackdb WHERE MATCH(title) AGAINST ('".$title."') AND type = 'question' LIMIT 1";
	$result = $connection->query($query);
	if ($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$id = $row['id'];
	}
}
if($id != 0 ){
	$user = strip_tags($_SESSION['login_user']);
	$query = " INSERT INTO preferences (user_id, id) VALUES(".$user.",".$id.")";
	$result = $connection->query($query);
	echo  $id.' '.$_SESSION['login_user'];
}
mysqli_close($connection); // Closing Connection


?>