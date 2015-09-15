<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
	  <meta name="description" content="Visual recommender for Stackoverflow database">
      <meta name="keywords" content="recommender, visual, stackoverflow, responsive, bootstrap">
      <meta name="author" content="Rajesh Surana">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
	  <link rel="apple-touch-icon" sizes="57x57" href="images/apple-icon-57x57.png">
	  <link rel="apple-touch-icon" sizes="60x60" href="images/apple-icon-60x60.png">
	  <link rel="apple-touch-icon" sizes="72x72" href="images/apple-icon-72x72.png">
	  <link rel="apple-touch-icon" sizes="76x76" href="images/apple-icon-76x76.png">
	  <link rel="apple-touch-icon" sizes="114x114" href="images/apple-icon-114x114.png">
	  <link rel="apple-touch-icon" sizes="120x120" href="images/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="images/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="images/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="images/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
	<link rel="manifest" href="images/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="images/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
      <title>Visual Recommender</title>
      <link rel="stylesheet" href="css/main.css">
	  
	  <script type="text/javascript" src="javascript/jquery-1.11.2.min.js"></script>
	  <!-- Latest compiled and minified CSS -->
	  <link rel="stylesheet" href="css/bootstrap.min.css">

	  <!-- Optional theme -->
	  <link rel="stylesheet" href="css/bootstrap-theme.min.css">

	  <!-- Latest compiled and minified JavaScript -->
	  <script src="javascript/bootstrap.min.js"></script>
	  <link href="css/bootstrap-toggle.min.css" rel="stylesheet">
	 <script src="javascript/bootstrap-toggle.min.js"></script>
	  <script type="text/javascript" src="javascript/recScript.js"></script>
  
  </head>
<body>
	<div class="grid" align="center">
		<div class="row-header">
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
      <a class="navbar-brand" href="http://visualrecommender.x10.bz" style="background-color:black; color:white;">Stackoverflow Recommender</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a data-toggle="collapse" data-target=".in" href="http://visualrecommender.x10.bz">Home <span class="sr-only">(current)</span></a></li>
        <li><a data-toggle="collapse" data-target=".in" href="#">About</a></li>
		<li><a data-toggle="collapse" data-target=".in" href="#">Contact</a></li>
      </ul>
      <form class="navbar-form navbar-left" role="search" method="post" action="phpfiles/processrec.php" onsubmit="ajaxrequestAll('phpfiles/processrec.php'); return false;">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="<?php if(!(isset($_SESSION['query']) AND $_SESSION['query']!='')) {echo "Search";}?>"
		  id="tfq" value="<?php if(isset($_SESSION['query'])){ echo $_SESSION['query']; }  ?>">
        </div>
        <button data-toggle="collapse" data-target=".in" type="submit" class="btn btn-default">Submit</button>
		<label>
			<input type="checkbox" data-toggle="toggle" data-on="On: Answered " data-off="Off: Answered" id="anscheck" data-onstyle="default" data-width="130" checked onchange="ajaxrequestAll('phpfiles/processrec.php')">
		</label>
      </form>
	  	<form class="form-inline navbar-form navbar-right" method="post" action="login.php">
			<button type="submit" class="btn btn-default" id="signinbut">
			<?php
			if (isset($_SESSION['login_user']))
				echo "Sign out";
			else
				echo "Sign in";
		?>
			</button>
		</form>
		<p class="navbar-text navbar-right" id="signin">
		<?php
			if (isset($_SESSION['login_user']))
				echo "{$_SESSION['login_user']}";
			else
				echo "Not logged in";
		?>
		</p>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
		
		</div>
		<div class="row1">
			<div class="col-1 vertical-box">
				<!--<select class="form-control" id="attr-row1">
					<option value="Rated" selected data-toggle="tooltip" data-placement="right" title="Tooltip on right">Rated</option>
					<option value="Trusted">Trusted</option>
				</select>-->
				<button type="button" onclick="changeButtonVal(1)" id="myButton1" class="btn btn-primary" autocomplete="off" value="Trusted" data-toggle="tooltip" data-placement="bottom" html="true" data-original-title="Highly Reputed User">Trusted</button>
			</div>
			<div class="col-3" id="max-rec1"><div id="background">Preview 1</div></div>
			<div class="col-3" id="max-rec2"><div id="background">Preview 2</div></div>
		</div>
		<div class="row1">
			<div class="col-1 vertical-box">
				<!--<select class="form-control" id="attr-row2">
					<option value="Discussed">Popular</option>
					<option value="Verbatim" selected>Verbatim</option>
				</select>-->
				<button type="button" onclick="changeButtonVal(2)" id="myButton2" class="btn btn-success" autocomplete="off" value="Verbatim" data-toggle="tooltip" data-placement="bottom" html="true" data-original-title="Maximal String Match">Verbatim</button>
			</div>
			<div class="col-3" id="max-rec3" ><div id="background">Preview 3</div></div>
			<div class="col-3" id="max-rec4" ><div id="background">Preview 4</div></div>
		</div>
		<div class="row1">
			<div class="caption-bottom">Public</div>
			<div class="caption-bottom">Personal</div>
		</div>
		<div class="row"><div style="font-style: italic;">* <u>Darker</u> the <b>SQUARE</b> color higher the result recommended</div></div>
</body>
</html>
