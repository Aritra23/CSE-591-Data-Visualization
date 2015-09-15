<?php
set_time_limit(0);
$sql = new MySQLi('localhost', 'aritral2', 'ra14je7sh9@G' , 'visualre_csvdb');
$result = $sql->query("SELECT distinct(`user_id`) FROM `stackdb` WHERE user_id != 0 OR user_id !=NULL ORDER BY `user_id` ASC LIMIT 200");

echo '<h1 style="right=0;left=0">List of first 200 User IDs.</h1> <div style="font-size:120%; display:inline;"><u>Password</u> is <b>1234. </b></div><div><a href="http://visualrecommender.x10.bz">Back to Recommender</a></div></br><hr/>';
$count = 0;
echo '<div style="font-size:120%; right:0; left:0;">';
while($row = $result->fetch_assoc())
{
	$count++;
	echo ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row["user_id"];
	if($count >15){
		echo '</div>';
		echo '</br>';
		echo '<div style="font-size:120%; right:0; left:0;">';
		$count = 0;
	}
}
echo '</div>'
?>