<?php
set_time_limit(0);
$sql = new MySQLi('localhost', 'aritral2', 'ra14je7sh9@G' , 'visualre_csvdb');
$result = $sql->query("SELECT distinct(`user_id`) FROM `stackdb` WHERE user_id != 0 OR user_id !=NULL;");

$count = 1;
while($row = $result->fetch_assoc())
{
	$tagtot = '';
	$query = "SELECT DISTINCT(title) AS tit, tag FROM stackdb where user_id=".$row["user_id"];
	$result1= $sql->query($query);
	if($result1->num_rows >0){
		while($r = $result1->fetch_assoc()){
			$tagtot .= $r["tag"]." ";
		}
	}	
	$q = 'UPDATE stackdb SET user_tags ="'.$tagtot.'" WHERE user_id ='.$row["user_id"];
	$sql->query($q);	
	echo $count." ";
	$count++;
	
}
?>