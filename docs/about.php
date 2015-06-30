<?php
	ob_start();
	session_start();
	require_once '../config.php';										//Configuration settings	
	require_once $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
	
	$title = 'About Us';	//The page title
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
			<div class="about-wrapper">
				<span class="large-header" style="padding-left:12px;">About Us</span>
				<div style="border-top:1px #ddd solid;margin:5px 0"></div>
			</div>
		</div>
	</div>

<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
