<?php require 'config.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $siteTitle; ?> - Thank you!</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/stylesheets/style.css" />
		<style type="text/css">
			.facebook-like {
				margin-top:10px;
			}
		</style>
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
		
		
	<?php
		$position = rand(0,1);
		$position = ($position == 1) ? 'right' : 'left';
	?>
	<div style="position:absolute;top:<?php echo rand(10,90); ?>%;<?php echo $position; ?>:<?php echo rand(2,13); ?>%;z-index:-9999;">
		<img src="<?php echo $url; ?>/images/logo.png" />
	</div>
		<div class="login-wrapper">
		<?php
			if (!empty($_GET["email"]))
			{
				$Statement = $Database->prepare("UPDATE standby SET unsubscribed = 1 WHERE email = ?");
				$Statement->execute(array($_GET["email"]));
				
				echo '<div style="text-align:center;color:#fff;"><p>You\'ve been removed from our regular email list.</p><p>We\'ll still give you a heads up when the site first goes public though!</p><p>Thanks!</p></div>';
			}
			else if (isset($_POST["submit"]))
			{
				$Statement = $Database->prepare("SELECT id FROM standby WHERE email = ?");
				$Statement->execute(array($_POST["email"]));
				$count = $Statement->rowCount();
				
				//Check for errors
				if (strlen($_POST["name"]) > 100)
				{
					die('Your name is too long');
				}
				else if (strlen($_POST["name"]) < 3)
				{
					die('Your name is too short');
				}
				else if (strlen($_POST["email"]) > 100)
				{
					die('Your email is too long');
				}
				else if (strlen($_POST["email"]) < 5)
				{
					die('Your email is too short');
				}
				else if ($count != 0)
				{
					die('That email has already been reserved');
				}
				else if (strlen($_POST["twitter"]) > 100)
				{
					die('Your twitter is too long');
				}
				else if (strlen($_POST["username"]) > 0)
				{
					die('Thank you for your submission');
				}
				else if (strlen($_POST["last_name"]) > 0)
				{
					die('Thank you for your submission');
				}
				else 
				{
					$Statement = $Database->prepare("INSERT INTO standby (name, email, twitter) VALUES (?, ?, ?)");
					$Statement->execute(array($_POST["name"], $_POST["email"], $_POST["twitter"]));
					
					/**
					 * Send an email to the user
					 */
					 
					$email 		= $_POST["email"];
					$subject	= "Thanks for signing up!";
					$body		= '
								<html>
									<body>
										<div style="width:600px;border:1px #777 solid;background:#fff;border-radius:5px;text-align:center;">
											<div style="text-align:center;background:#062327;padding-bottom:10px;border-radius:4px;border-bottom-left-radius:0;border-bottom-right-radius:0;">
												<img src="http://www.konetch.com/images/logo-dark.png" style="width:300px;;"/>
											</div>
											<div style="padding:10px;color:#333;border-radius:5px;font-family:arial;">
												<p style="font-size:1.1em;font-weight:bold;">Thank you for signing up!</p>
												<p>We may send you a few emails every now and then just to check up on how you\'re doing and maybe release some updates about the site.</p>
												<p>Let us know if you don\'t want these emails by clicking the button below.</p>
												<a href="http://www.konetch.com/signup.php?email='.$_POST["email"].'" style="padding:4px 15px;color:#fff;background:#062327;font-family:segoe print bold;font-size:15px;text-decoration:none;width:160px;margin:0 auto;border-radius:5px;border:1px #000 solid;">Don\'t Fill Me In</a>
												<br/><br/>
											</div>
										</div>
									</body>
									</html>';				
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Konetch <no-reply@konetch.com>' . "\r\n";
					
					mail($email, $subject, $body, $headers);
					
					echo '<div style="text-align:center;color:#fff;"><p>Thank you for signing up! We\'re still in private beta mode, but we promise you\'ll be the first to know when we get to the next step.</p><p>We look forward to hearing more from you!</p></div>';
				}
			}
		?>
	</div>
	<div class="facebook-like">
		<div class="fb-like" data-href="https://www.facebook.com/pages/Datehitter/627486493933317" data-send="false" data-layout="button_count" data-width="255" data-show-faces="true"></div>
	</div>
	</body>
</html>
		
