<?php
session_start();
if (isset($_SESSION['login_user'])){
	unset($_SESSION['login_user']);
	header("location: ../index.php"); // Redirecting To Home Page
	
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
	  <meta name="description" content="Visual recommender for Stackoverflow database">
      <meta name="keywords" content="recommender, visual, stackoverflow, responsive, bootstrap">
      <meta name="author" content="Aritra Kumar Lahiri">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
      <title>Visual Recommender</title>
      <link rel="stylesheet" href="css/main.css">
	  <link rel="stylesheet" href="css/login.css">

	  <!-- JAVASCRIPT to clear search text when the field is clicked -->
	  <!--<script type="text/javascript" src="javascript/recScript.js" async></script>-->
	  <script type="text/javascript" src="javascript/jquery-1.11.2.min.js"></script>
	 <!-- <script type="text/javascript" src="javascript/login.js" async></script>-->
	  <!-- Latest compiled and minified CSS -->
	  <link rel="stylesheet" href="css/bootstrap.min.css">

	  <!-- Optional theme -->
	  <link rel="stylesheet" href="css/bootstrap-theme.min.css">

	  <!-- Latest compiled and minified JavaScript -->
	  <script src="javascript/bootstrap.min.js"></script>
  
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
      <a class="navbar-brand" href="index.php" style="background-color:black; color:white;">Stackoverflow Recommender</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
        <li><a href="#">About</a></li>
		<li><a href="#">Contact</a></li>
      </ul>
		
	  	<form class="form-inline navbar-form navbar-right" method="post" action="login.php">
			<button type="submit" class="btn btn-default" id="signinbut">Sign in</button>
		</form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>	
		</div>
		<?php
		//Display error message for invalid user id or password
		if (isset($_SESSION['Error_Message'])){
			echo '<div style = "left:0; right:0; color:white; font-size:140%">'.$_SESSION['Error_Message'].'</div>';
			unset($_SESSION['Error_Message']);
		}
			?>
	<div class="container">
        <div class="card card-container">
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="post" action="phpfiles/processlogin.php">
                <!--<span id="reauth-email" class="reauth-email"></span>-->
                <input type="text" id="username" name="username" class="form-control" placeholder="User ID" required autofocus>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required value="1234">
                <div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
            </form><!-- /form -->
            <a href="#" class="forgot-password">
                Forgot the password?
            </a>
        </div><!-- /card-container -->
    </div><!-- /container -->
</body>
</html>