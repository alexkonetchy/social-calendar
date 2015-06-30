<?php
	ob_start();
	session_start();
	require '../config.php';	//Configuration settings	
	require $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
	
	$title = 'Contact Us';	//The page title
?>

	<div class="wrapper">
		<div class="left-sidebar">
			<div class="left-menu">
				<?php require $baseDir . 'includes/templates/docs_menu.php'; /*Display the default left menu*/ ?>
				<br/>
			</div>
			<?php require $baseDir . 'includes/templates/footer_box.php'; /*Displays the footer box*/ ?>
		</div>
		
		<div class="profile-wrapper">
			<div class="contact-wrapper">
				<h1>Contact Us</h1>
				
				<p>We love talking to people and will answer or address pretty much anything. Use the form below and we'll get back to you as soon as possible. Thanks!</p>
				
				<?php
					if (isset($_POST["send_contact"]))
					{
						//Check for errors
						if (empty($_POST["first"]) || empty($_POST["last"]) || empty($_POST["email"]) || empty($_POST["message"]))
						{
							echo 'Oops. All the fields are required.';
						}
						else if (strlen($_POST["first"]) > 50)
						{
							echo 'Oops. Your first name must be under 50 characters';
						}
						else if (strlen($_POST["last"]) > 50)
						{
							echo 'Oops. Your last name must be under 50 characters';
						}
						else if (strlen($_POST["email"]) > 100)
						{
							echo 'Oops. Your email must be under 100 characters';
						}
						else if (strlen($_POST["message"]) > 2000)
						{
							echo 'Your message must be under 2000 characters';
						}
						else if (strlen($_POST["first"]) > 50)
						{
							echo 'Your first name must be under 50 characters';
						}
						else if (strtolower($_POST["test"]) != 'pneumonoultramicroscopicsilicovolcanoconiosis')
						{
							echo 'Oops. Your answer to the question was incorrect';
						}
						else if (!empty($_POST["category"]))
						{
							die('Sorry there was an unexpected error');
						}
						else
						{
							$email 		= 'alex-konetchy@hotmail.com';
							$subject	= 'Datehitter Contact';
							$body		= '	<html>
												<body>
												<p>The following message was sent by:</p>
												' . $_POST["first"] . ' ' . $_POST["last"] . '<br/>' . $_POST["email"] . '
												<p>Their message was as follows:</p>
												<p>' . $_POST["message"] . '</p>
												</body>
											</html>';				
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers .= 'From: Datehitter <datehitter@datehitter.com>' . "\r\n";
				
							mail($email, $subject, $body, $headers);
							
							echo '<b>Your email was sent successfully. Thank you!</b>';
						}
					}
					else
					{
				?>
					<form action="#" method="post">
						<div style="font-weight:bold;">Name <span style="color:red;font-size:0.8em;">*</span></div>
						<input type="text" name="first" style="width:214px;border:1px #999 solid;" class="post-input" placeholder="First" required /> 
						<input type="text" name="last" style="width:214px;border:1px #999 solid;" class="post-input" placeholder="Last" required />
						<br/>
						<div style="display:none"><input type="text" name="category" class="post-input" style="width:450px;border:1px #999 solid;" placeholder="Category" value="" /></div>
						<div style="font-weight:bold;">Email <span style="color:red;font-size:0.8em;">*</span></div>
						<input type="email" name="email" style="width:450px;border:1px #999 solid;" class="post-input" placeholder="Email" required /> 
						<br/>
						<div style="font-weight:bold;">Message <span style="color:red;font-size:0.8em;">*</span></div>
						<textarea name="message" style="width:450px;max-width:450px;border:1px #999 solid;height:100px;" class="post-input" placeholder="2000 characters" required></textarea>
						<br/>
						<div style="font-weight:bold;">Longest english word? <span style="color:red;font-size:0.8em;">*</span> 
						<a href="http://en.wikipedia.org/wiki/Pneumonoultramicroscopicsilicovolcanoconiosis" target="_blank">Hint</a> 
						<span style="font-size:0.85em;font-weight:normal;"><em>(Opens in new tab)</span>
						</div>
						<input type="text" name="test" style="width:450px;border:1px #999 solid;" class="post-input" required /> 
						<br/>
						<input type="submit" name="send_contact" value="Send Message" class="a-button" />
					</form>
				<?php
					} //end contact form processer
				?>
			</div>
		</div>
	</div>

<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
