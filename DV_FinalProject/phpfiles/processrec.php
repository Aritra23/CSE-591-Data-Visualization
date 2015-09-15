<?php
session_start();
?>
<?php
$servername = "localhost";
$username = "aritral2";
$password = "ra14je7sh9@G";
$dbname = "visualre_csvdb";

//create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check connection 
if($conn->connect_error){
	die("Connection failed" . $conn->connect_error);
}else {
	//echo "Connection Successful.";
}

function recommendation($conn, $strUser, $tagID, $tfq , $answer){
		$sql1 = '';
		$sql2 = '';
		$sql3 = '';
		$sql4 = '';
		$rowfetch = '';
		$usertags = '';
		$viewName = 'a'.uniqid(); //Prevent override when multiple users accessing database
		$_SESSION["query"] = $tfq;

	$colors_first = array("#FFFFFF", "#E6E6E6", "#CCCCCC", "#B2B2B2", "#999999", "#808080", "#666666", "#4C4C4C", "#333333",  "#1A1A1A",  "#000000");
	
	$colors_second = array("#FFFFFF", "#66C2FF", "#33ADFF", "#0099FF", "#007ACC",  "#0099CC", "#005C99", "#003D66", "#002E4C", "#001F33", "#000000");
	
	$colors_third = array("#FFFFFF", "#E6FFE6", "#CCFFCC", "#B2FFB2", "#80FF80",  "#00FF00", "#00CC00", "#009900", "#006600", "#004C00", "#000000");
		
		//Fetch user tags for personal recommendation
		if(isset($_SESSION['login_user'])){
			$sql0 = 'SELECT user_tags from stackdb where user_id='.$_SESSION['login_user'];
			$res = $conn->query($sql0);
			if($res->num_rows > 0){
				$row = $res->fetch_row();
				$usertags = $row[0];
			}
		}
		
		$join = '';
		$condition = '';
		//Hide effect for personal recommendation
		if(($tagID == 'max-rec2' OR $tagID == 'max-rec4')  AND isset($_SESSION['login_user'])){
				$condition = ' AND id NOT IN (SELECT DISTINCT(id) FROM preferences WHERE user_id = '.$_SESSION['login_user'].')';
		}
		
		if($strUser == 'Trusted'){
			$sql1 = "CREATE OR REPLACE VIEW ".$viewName." AS SELECT * FROM stackdb WHERE MATCH(title, tag) AGAINST ('".$tfq."') AND type = 'question'".$answer.$condition." LIMIT 10;";
			$sql2 = "SELECT * FROM ".$viewName." ORDER BY reputation DESC;";
			$sql3 = "SELECT MAX(reputation) FROM ".$viewName;
			$sql4 = "SELECT MIN(reputation) FROM ".$viewName;
			$rowfetch = 'reputation';
		} else if ($strUser == 'Rated') {
			$sql1 = "CREATE OR REPLACE VIEW ".$viewName." AS SELECT * FROM stackdb WHERE MATCH(title, tag) AGAINST ('".$tfq."') AND type = 'question'".$answer.$condition." LIMIT 10;";
			$sql2 = "SELECT * FROM ".$viewName." ORDER BY vote DESC;";
			$sql3 = "SELECT MAX(vote) FROM ".$viewName;
			$sql4 = "SELECT MIN(vote) FROM ".$viewName;
			$rowfetch = 'vote';
		} else if ($strUser == 'Popular') {
			$sql1 = "CREATE OR REPLACE VIEW ".$viewName." AS SELECT * FROM stackdb WHERE MATCH(title, tag) AGAINST ('".$tfq."') AND type = 'question'".$answer.$condition." LIMIT 10;";
			$sql2 = "SELECT * FROM ".$viewName." ORDER BY ans_count DESC;";
			$sql3 = "SELECT MAX(ans_count) FROM ".$viewName;
			$sql4 = "SELECT MIN(ans_count) FROM ".$viewName;
			$rowfetch = 'ans_count';
		} else if ($strUser == 'Verbatim') {
			$sql1 = "CREATE OR REPLACE VIEW ".$viewName." AS SELECT *, MATCH(title, tag) AGAINST ('".$tfq."') AS score FROM stackdb WHERE MATCH(title, tag) AGAINST ('".$tfq."') AND type = 'question'".$answer.$condition." HAVING score > 0 LIMIT 10;";
			$sql2 = "SELECT * FROM ".$viewName;
			$sql3 = "SELECT MAX(score) FROM ".$viewName;
			$sql4 = "SELECT MIN(score) FROM ".$viewName;
			$rowfetch = 'score';
		}
		$result = $conn->query($sql1);
		$result = $conn->query($sql2);
		
		//Compute Max Value
		$max_result = $conn->query($sql3);
		if($max_result->num_rows > 0){
			$row = $max_result->fetch_row();
			$max = $row[0];
		} else
			$max = 0;


		//Compute Min Value
		$min_result = $conn->query($sql4);
		if($min_result->num_rows > 0){
			$row = $min_result->fetch_row();
			$min = $row[0];
		}else 
			$min = 0;
		$max_min = $max - $min;
		
		//To avoid divide by zero error
		if($max_min == 0){
			$max_min++;
		}
	
		//Compute User interest
		$score;
		$con = 0;
		$max_user_score = 0;
		$min_user_score = 0;
		$max_min_user_score = 0;
		if(($tagID == 'max-rec2' OR $tagID == 'max-rec4')  AND isset($_SESSION['login_user']) AND $usertags != '' AND $usertags != 'undefined'){
			$result1 = $conn->query($sql2);
			if ($result1->num_rows > 0) {
				$tag_title = '';
				// output data of each row for preview
				while($row = $result1->fetch_assoc()) {
					$tag_title = ''.$row['title'].' '.$row['tag'];
					similar_text( $tag_title,$usertags, $score[$row['title']]);
					$score[$row['title']] = ($score[$row['title']]+ ($row[$rowfetch]-$min)*10/$max_min)/2;
					$con++;
				}	
				$max_user_score = ceil(max($score));
				$min_user_score = ceil(min($score));
				$max_min_user_score = $max_user_score - $min_user_score;
				if($max_min_user_score <=0){
					$max_min_user_score = 1;
				}
				arsort($score);
			}
			
		}

		$finalresult = '<div style="margin: 0 !important; overflow:scroll; white-space: nowrap; line-Height:90%; text-align: left; font-size: 14px; padding-right: 5px; padding-left:5px; padding-bottom:2px">';  
		$preview = '';
		$sq = '';
		$count = 0;
		

		$hideSign = '<svg height="20" width="30" style="margin-left:15px;padding-top:3px">
		<circle cx="15" cy="9" r="5" stroke="black" stroke-width="1" fill="rgb(70,70,70)" />
		<circle cx="15" cy="9" r="2" stroke="grey" stroke-width="1" fill="grey" />
		<path d="M 0 10 q 15 -12 30 0" stroke="rgb(70,70,70)" stroke-width="1" fill="none" />
		<path d="M 0 10 q 15  12 30 0" stroke="rgb(70,70,70)" stroke-width="1" fill="none" />
		<line x1="27" y1="3" x2="0" y2="20" style="stroke:#FF4D4D;stroke-width:2" />
		Sorry, your browser does not support inline SVG.
		</svg>';


		if ($result->num_rows > 0) {
			// output data of each row for preview
			while($row = $result->fetch_assoc()) {
				$sq = '';
				$arrTag = '';
				$arrScore = '';
				if(($tagID == 'max-rec2')  AND isset($_SESSION['login_user']) AND $usertags != '' AND $usertags != 'undefined'){
					$arrTag = key($score);
					$arrScore = current($score);
					$colorIndex = ceil(($arrScore - $min_user_score)*10/$max_min_user_score);
					if ($colorIndex<0){$colorIndex = 0;}
					$sq .= '<svg width="20" height="20" style="padding-top:5px;margin-right:10px"><rect width="20" height="20" style="fill:'.$colors_second[$colorIndex].';stroke-width:none;stroke:rgb(0,0,0)"/></svg>';
				} else if (($tagID == 'max-rec4')  AND isset($_SESSION['login_user']) AND $usertags != '' AND $usertags != 'undefined'){
					$arrTag = key($score);
					$arrScore = current($score);
					$colorIndex = ceil(($arrScore - $min_user_score)*10/$max_min_user_score);
					if ($colorIndex<0){$colorIndex = 0;}
					$sq .= '<svg width="20" height="20" style="padding-top:5px;margin-right:10px"><rect width="20" height="20" style="fill:'.$colors_third[$colorIndex].';stroke-width:none;stroke:rgb(0,0,0)"/></svg>';	
				} else if($tagID == 'max-rec1' OR $tagID == 'max-rec2'){
					$sq .= '<svg width="20" height="20" style="padding-top:5px;margin-right:10px"><rect width="20" height="20" style="fill:'.$colors_second[ceil(($row[$rowfetch]-$min)*10/$max_min)].';stroke-width:none;stroke:rgb(0,0,0)"/></svg>';
				} else if ($tagID == 'max-rec3' OR $tagID == 'max-rec4'){
					$sq .= '<svg width="20" height="20" style="padding-top:5px;margin-right:10px"><rect width="20" height="20" style="fill:'.$colors_third[ceil(($row[$rowfetch]-$min)*10/$max_min)].';stroke-width:none;stroke:rgb(0,0,0)"/></svg>';
				}
				
			if(($tagID == 'max-rec1') OR ($tagID == 'max-rec3')  OR !isset($_SESSION['login_user']) OR $usertags == '' OR $usertags == 'undefined'){
				$preview .= $sq.'<div style="text-align:left; white-space: nowrap; display:inline;">'. $row["title"].'<a href="../QueAns.php?query='.$row["title"].'"> Link </a></div><br/>';  
			} else {
				$ajax = "Hide('".$arrTag."', '".$tagID."'); return false;";
				$preview .= $sq.'<div style="text-align:left; white-space: nowrap; display:inline;">'. $arrTag.'<a href="../QueAns.php?query='.$arrTag.'"> Link </a><div style="display:inline; cursor:pointer" onclick ="'.$ajax.'" >'.$hideSign.'</div></div><br/>'; 
				if (!next($score)){
					break;
				}
			}
			//ajaxrequestHide("'.$arrTag.'", "'.$tagID.'); return false;"
			$count++;
			}
			if(isset($_SESSION['login_user']) AND (($tagID == 'max-rec2') OR ($tagID == 'max-rec4'))){
				$count ++;
			}

			while($count < 10){
				$preview .= '<svg width="20" height="20" style="padding-top:5px;margin-right:10px"><rect width="20" height="20" style="fill:'.$colors_second[0].';stroke-width:none;stroke:rgb(0,0,0)"/></svg><div style="text-align:left; white-space: nowrap; display:inline;">  Empty result</div></br>';	
				$count++;
			}

	
			$finalresult .= $preview.'</div>';
	
			echo $finalresult;
		} else {
			echo "0 results";
		}
		
		//Delete the created view
		$queryViewDelete = 'DROP VIEW '.$viewName;
		$conn->query($queryViewDelete);
		
		
}

if (isset($_POST['tagID']) && isset($_POST['tfq']) && isset($_POST['strUser'])  && isset($_POST['answer'])){
	$tagID = strip_tags($_POST['tagID']);
	$tfq = strip_tags($_POST['tfq']);
	$strUser = strip_tags($_POST['strUser']);
	$answer = strip_tags($_POST['answer']);
	
	recommendation($conn, $strUser, $tagID, $tfq , $answer);

}
//Close connection
$conn->close();
?>