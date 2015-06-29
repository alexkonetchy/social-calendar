<?php
	require 'config.php';									//Configuration settings	
	require $baseDir . 'includes/standard_page_load.php';	//Loads most common features (i.e. Facebook api)
	$title = 'Advanced Search';								//The page title
	
	require $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
?>
<div class="wrapper">
	<?php require $baseDir . 'includes/templates/left_menu.php'; /*Display the default left menu*/ ?>
		
	<div class="profile-wrapper" style="text-align:center;">
		<?php
			//If the search form was submitted
			if (isset($_GET["submit"])):
			
				require $baseDir . 'includes/templates/search_results.php';	//Display the search results
				
			else:
		?>
		<div class="advanced-form" style="width:550px;margin:0 auto;">
			<h1><span class="orange">Advanced</span> Search</h1>
			<br/>
			<form action="search.php" method="get">
				<input type="text" name="q" placeholder="Contains these words (seperate by commas)" class="a-input" style="width:550px;"/>
				<br/>
				<input type="text" name="tags" placeholder="These #hashtags (seperate by commas)" class="a-input" style="width:550px;" />
				<br/>
				<input type="text" name="location" placeholder="This area" class="a-input" style="width:550px;" />
				<br/>
				<input type="text" name="date" placeholder="This date (mm-dd-yyyy)" class="a-input" style="width:550px;" />
				<br/>
				<input type="submit" name="submit" class="a-button" value="Search" />
				<input type="reset" name="reset" class="a-button" value="Reset Form" />
			</form>
		</div>

		<?php endif; ?>

	</div>
	
<?php require $baseDir . 'includes/templates/page_footer.php';	/*The html for the footer of the page*/ ?>
