<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
      <title>Visual Recommender</title>
      <link rel="stylesheet" href="css/main.css">

	  <!-- JAVASCRIPT to clear search text when the field is clicked -->
	  <script type="text/javascript" src="javascript/recScript.js" async></script>
	  <script type="text/javascript" src="javascript/jquery-1.11.2.min.js"></script>

	  <!-- Latest compiled and minified CSS -->
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	  <!-- Optional theme -->
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

	  <!-- Latest compiled and minified JavaScript -->
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  
  </head>
<body>
	<div class="grid" align="center">
		<div class="row-header-small">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.html" style="background-color:black; color:white;">Stackoverflow Recommender</a>
			</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.html">Home <span class="sr-only">(current)</span></a></li>
				<li><a href="#">About</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-1" id="question">Question: </div>
				<div class="col-md-11" id="question">
					<?php
						echo "Hello world";
					?>
				</div>
			</div>
			<div id="answers">
				<?php
				set_time_limit(0);
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
					
					$sql = 'SELECT title FROM stackdb where type LIKE "question"';
					
					$result = $conn->query($sql);
					
					$con = 1;
					if ($result->num_rows > 0) {
					// output data of each row for preview
						while($row = $result->fetch_assoc()) {
							$sql = 'SELECT COUNT(`title`) FROM stackdb WHERE title ="'.$row["title"].'" AND type NOT LIKE "question"';
							$count = $conn->query($sql);
							if($count->num_rows > 0){
								$row1 = $count->fetch_row();
							}
							$sql = 'UPDATE stackdb SET ans_count = '.$row1[0].' WHERE title = "'.$row['title'].'"';
							$conn->query($sql);
							echo $con." ";
							$con++;
						}
					
					}
				?>
			</div>
		</div>
</body>
</html>