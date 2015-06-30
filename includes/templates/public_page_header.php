<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="description" content="<?php echo $siteDescription; ?>" />
		<meta name="keywords" content="Social Calendar" />
		<meta name="author" content="Datehitter" />
		<!--<meta http-equiv="refresh" content="900" />-->
		<title><?php echo $siteTitle; ?>Page not found!||</title>
		<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/stylesheets/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/stylesheets/jquery.fancybox.css?v=2.1.4" media="screen" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/jquery.fancybox.min.js?v=2.1.4"></script>
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/jquery.dotdotdot.min.js"></script>
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/placeholder.min.js"></script>	
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/jquery.leanModal.min.js"></script>
		<script type="text/javascript">$(function() { $('a[rel*=popup]').leanModal({ top : 200, closeButton: ".popup-close" });	});</script>
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/jquery.events.min.js"></script>
		<script type="text/javascript">$(document).ready(function() { $('.fancybox').fancybox({ helpers: { title : { type : 'outside' } }, afterLoad : function() { this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? '<div style="text-align:left;margin-top:6px">' + this.title + '</div>' : ''); } }); });</script>
	</head>
	
	<body>
		<div id="popup"></div> <!--Needed for functionality-->
		<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=150928125069674"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
		
		<div class="page-header">
			<div class="page-header-contain">
				<div class="page-header-title">
					<a href="<?php echo $url; ?>"><img src="<?php echo $url; ?>/images/logo.png" /></a>
				</div>
			</div>
		</div>
