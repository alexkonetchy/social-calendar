	</div></div>
	<script type="text/javascript">(function() { var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true; po.src = 'https://apis.google.com/js/plusone.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s); })(); </script>
	</body>
</html>	

<?php

	/**
	 * This handles the output buffer. Since this footer
	 * should be included in everypage every page must be
	 * set up with ob_start(); 
	 */
	 
	if (isset($title)) $title = ' - ' . $title;				//Add hyphen to the title
	$html = ob_get_contents(); 								//Store the page's HTML into a string
	ob_end_clean(); 										//Wipe the buffer
	echo str_replace('Page not found!||', $title, $html);	// Replace <!--TITLE--> with $title variable contents, and print the HTML
	
?>
