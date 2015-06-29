<?php
	session_start();				//Start the session
	require 'config.php';			//Configuration settings
	$Login = new Login($Database);	//Instantiate the class needed to log in	
	
	#########################################################################################
    #																						#
	# This next block of code handles logging into the site through facebook. The code      #
	# determines the login type. Instantiates the facebook class. Checks to see if the user #
	# has logged in through facebook or not. If they have not then it redirects to the      #
	# correct URL. If they have then if checks to see if they're registed. If they aren't   #
	# it registers them. After that it adds any facebook events that they have on their     #
	# be-half and finally it redirects them to the homepage.                                #
	#  																						#
	#########################################################################################

	if ($_GET["login_type"] == 'facebook')
	{
		require 'includes/fb-php-sdk/src/facebook.php';		//Include the Facebook API
		$Facebook = new Facebook($fbConfig);				//Instantiage the facebook class
		
		$user_fb = $Facebook->getUser();	//The user of facebook
		
		if ($user_fb)	//If the user has logged in through facebok already
		{
	  		try 
	  		{
	    		$user = $Facebook->api('/me');	
	  		} 
	  		catch (FacebookApiException $e) 
	  		{
	    		error_log($e);
	    		$user_fb = null;
	  		}
		}
		else	//If the user hasn't logged in through facebook
		{
			$_SESSION["login_type"] = 'facebook';						//The user is logging in through facebook
			$login_params = array('scope' => 'email, user_photos, user_videos' );	//The facebook inforamtion to get
			$login_url = $Facebook->getLoginUrl($login_params);			//Get the facebook login link
			
			header("Location: " . $login_url);							//Redirect the user to the facebook api url		
		}
		
		//If the user is logged in 
		if (!empty($user) )
		{
			$Login->setId($user["id"]);		//Identidy the user for the methods in the login class
			
			//If this is the frist login
			if ($Login->firstFacebookLogin())	
			{
				$fql = "SELECT email FROM user WHERE uid = me()";							//Prepare the query to get the users email through facebook
				$param = array('method' => 'fql.query', 'query' => $fql, 'callback' => '');	//Definte the query parameters
				$result = $Facebook->api($param);											//Get the email
				
				//Cycle through the results (should be only 1)
				foreach ($result as $row)
				{
					$fb_email = $row["email"];	//Save he user's email
					break;						//Break out of the loop
				}	
			
				$_SESSION["first_login"] = true;						//There user's first sign in
				$Register = new Register($Database);					//Initialize the register class
				
				//Register the user
				try
				{
					$Register->setFirstName($user["first_name"]);			//Save the user's first name
					$Register->setLastName($user["last_name"]);				//Save the user's last name
					$Register->setUniqueUsername($user["username"]);		//Save the user's username
					$Register->setThumbnail('https://graph.facebook.com/' . $user["username"] . '/picture');	//Save the user's thumbnail
					$Register->setGender($user["gender"]);					//Save the user's gender
					$Register->setEmail($fb_email);							//Save the user's email
					$Register->setRegisterType($_SESSION["login_type"]);	//The type of registration
					$Register->setUserId($user["id"]);						//Save the facebook id
					
					$Register->insertUser();								//Register the user
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
				}
			}
				
			$_SESSION["in"] = true;						//The user is logged in
			header("Location: " . $url . "/index.php");	//Redirect the user ro the index page
			die();
		}
	}
	
	#########################################################################################
    #																						#
	# This next block of code handles user's who use the site to log in.                    #
	#																						#
	#########################################################################################
	
	if (isset($_POST["login"]))
	{
		$_SESSION["login_type"] = 'konetch';	//The user is using konetch to log into the site
		
		try
		{
			$_SESSION["session_key"] = $Login->signIn($_POST["email"], $_POST["password"], $salt);	//Check the user against the database
		}
		catch (Exception $e)
		{
			echo $e->getMessage();	
		}
		
		$_SESSION["in"] = true;						//A user has been logged in
		header("Location: " . $url . "/index.php");	//Redirect to the homepage
		die();										//Prevent extra page execution
	}
	
	#########################################################################################
	#																						#
	# Handles the page functions 															#
	#																						#
	#########################################################################################
	
?>

<!DOCTYPE html>
<html>
	<head>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/placeholder.min.js"></script>
		<title><?php echo $siteTitle; ?> - Login</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/stylesheets/style.css" />
		<style type="text/css">
			.underlink {
				color:#fff;
				font-size:0.8em;
				text-decoration:none;
			}
			.underlink:hover {
				text-decoration:underline;
			}
			.facebook-like {
				margin-top:10px;
			}
		</style>
		<script type="text/javascript">
			function slide(chosen) {
	    		$('.slider').each(function(index) {
	          		if ($(this).attr("id") == chosen) {
	               		$(this).slideToggle(800);
	          		}
	          		else {
	               		$(this).slideUp(600);
	          		}
	     		});
			}
			function slideForm(chosen) {
	    		$('.slide').each(function(index) {
	          		if ($(this).attr("id") == chosen) {
	               		$(this).slideToggle(800);
	          		}
	          		else {
	               		$(this).slideUp(600);
	          		}
	     		});
			}	
			function toggle(chosen) {
	    		$('#'+chosen).slideToggle(200);
			}
		</script>
	</head>
	
	<body style="background:#020822 url('images/login_bg.jpg') center no-repeat fixed; -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=150928125069674";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		
		<div class="login-wrapper" id="login" style="text-align:center;">
			<?php
				#########################################################################################
			    #																						#
				# This next block of code registers a new user through konetch. The code requires a     #
				# processing page that processes the code and inserts the new user into the database.   #
				#																						#
				#########################################################################################
				
				if (isset($_POST["register"]))	//If the register form was submitted
					require $baseDir . 'process_form/register.php';
			?>

			<div style="margin-bottom:10px;text-align:center;color:#fff;">
				<h1 style="margin:0;margin-bottom:5px;padding:0;font-family:Segoe Print,Papyrus;color:#fff;font-weight:bold;"><span class="orange">c</span>aptchur your life</h1>
			</div>
			
			<div style="display:none;margin-bottom:7px;" id="loading">
				<img src="images/bar-loader.gif">
			</div>
			<a href="<?php echo $url; ?>/login.php?login_type=facebook" onClick="document.getElementById('loading').style.display='block';"><img src="<?php echo $url; ?>/images/facebook-login.jpg" /></a>
			<br/>
				
				<div style="margin-top:7px;">
					<h4 style="margin:0;margin-bottom:5px;padding:0;font-family:Segoe Print,Papyrus;color:#fff;font-weight:bold;">Don't have a Facebook?<br/><a href="javascript:slide('register');" style="text-decoration:none;color:orange;">Register</a> <span style="font-size:11px;">-or-</span> <a href="javascript:slide('sign-in');" style="text-decoration:none;color:orange;">Sign in</a></h4>
				</div>
				<div style="margin-top:20px;display:none;" id="register" class="slider">
					<form action="login.php" method="post">
						<input type="text" name="first_name" placeholder="First Name" class="a-input" style="width:200px;" />
						<input type="text" name="last_name" placeholder="Last Name" class="a-input" style="width:200px;" />
						<br/>
						<input type="text" name="username" placeholder="Username" class="a-input" style="width:430px;text-align:center;" />
						<br/>
						<input type="email" name="email" placeholder="Email" value="<?php echo $_GET["access_email"]; ?>" class="a-input" style="width:430px;text-align:center;" />
						<br/>
						<input type="password" name="password" placeholder="Password" class="a-input" style="width:430px;text-align:center;" />
						<br/>
						<input type="submit" name="register" value="Sign Up" class="a-button" />
					</form>
				</div>
				<div style="margin-top:10px;display:none;" id="sign-in" class="slider">
					<form action="login.php" method="post">
						<input type="email" name="email" placeholder="Email" value="<?php echo $_GET["access_email"]; ?>" class="a-input" style="width:250px;" />
						<br/>
						<input type="password" name="password" placeholder="Password" class="a-input" style="width:250px;" />
						<br/>
						<input type="submit" name="login" value="Sign In" class="a-button" />
					</form>
				</div>
		</div>
		<div class="facebook-like">
			<div class="fb-like" data-href="https://www.facebook.com/pages/Konetch/627486493933317" data-send="false" data-layout="button_count" data-width="255" data-show-faces="true"></div>
		</div>
	</body>
</html>
		
