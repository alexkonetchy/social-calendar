<?php
	#####################################################
	/**
	 * The page settings
	 */
	require 'config.php';										//Configuration settings
	require $baseDir . 'includes/standard_page_load.php';		//Loads most common features (i.e. Facebook api)
	
	######################################################
	/**
	 * Starts the html of the page
	 */
	require $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
?>

	<div class="wrapper">
		<?php require $baseDir . 'includes/templates/left_menu.php'; /*Display the default left menu*/ ?>
		
		<div class="content-wrapper" style="text-align:left;">
			<div class="top-heading">Invite your friends</div>
			<div style="padding:0 5px;text-align:center;">
				<p style="line-height:160%;color:#333;">Konetch is still working out the kinks in beta, but we know that it's way more fun to use it with friends. If you know someone who might enjoy the site you can invite them by clicking the buttons below, or copy the link and send it another way that's more convenient.</p>
				<p style="font-size:0.8em;color:#888;font-style:italic;">(The link is good for one day)</p>
				<div style="width:70px;margin:0 auto;margin-bottom:10px;">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.konetch.com/login.php?token=<?php echo md5(date('Y-d-m-d-m-d') . 'GG43dsfgf'); ?>" data-text="I invited you to use @konetch. This link will give you access" data-count="none">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>
				<div class="fb-send" data-href="http://www.konetch.com/login.php?token=<?php echo md5(date('Y-d-m-d-m-d') . 'GG43dsfgf'); ?>" style="width:100px;margin:0 auto;padding:0;"></div>
				<br/>
				<br/>
				<span style="font-size:0.85em;color:#888;">http://www.konetch.com/login.php?token=<?php echo md5(date('Y-d-m-d-m-d') . 'GG43dsfgf'); ?></span>
			</div>
		</div>
		</div class="clear"></div>
<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page	
?>
