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
		<script type="text/javascript">$(function() { $('a[rel*=popup]').leanModal({ closeButton: ".popup-close" });	});</script>
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/jquery.events.min.js"></script>
		<script type="text/javascript">function updateSelected(id, parentID) { document.getElementById(parentID).style.display = 'none'; <?php if(isset($_GET["m"])) echo (strlen($_GET["m"]) == 1) ? 'document.getElementById("update-month-' . $_GET["m"] . '").selected = true;' : 'document.getElementById("update-month-' . substr($_GET["m"], 1, 1) . '").selected = true;' ; ?> <?php if(isset($_GET["y"])) echo 'document.getElementById("update-year").setAttribute("value","' . $_GET["y"] . '");'; ?> document.getElementById(id).selected = true; } $(document).ready(function() { $('.delete_post').click(function () { var id = $(this).attr('id'); var data = 'id=' + id + '&submit=yes'; $(this).parent().fadeOut(900); $('.delete_post').attr('disabled','true'); $.ajax({ url: "<?php echo $url; ?>/process_form/delete_post.php", type: "GET", data: data, cache: false, success: function (html) { if (html!=1) { alert(html); } } }); return false; }); }); $(document).ready(function() { $('.fancybox').fancybox({ helpers: { title : { type : 'outside' } }, afterLoad : function() { this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? '<div style="text-align:left;margin-top:6px">' + this.title + '</div>' : ''); } }); }); $(document).ready( function() { var date= new Date(); var day = date.getDay() + 2; var month = date.getMonth() + 1; $('#post-month').val(month); $('#post-day').val(day); });
		
			$(document).ready( function () {
				$('.make_featured').click( function () {
					var id = $(this).attr('id');	
					var data = 'id=' + id + '&submit=yes';
					
					if ($(this).attr('class') == 'featured_label make_featured') {
						$(this).html('Make Featured');
						$(this).attr('class', 'make_featured');
					}
					else {
						$(this).html('Featured');
						$(this).attr('class', 'featured_label make_featured');
					}
					
					$.ajax({
						url: "<?php echo $url; ?>/process_form/make_featured.php",
						type: "GET",
						data: data,
						cache: false,
						success: function (html) {
							if (html == 'Internal Server Error') {
								alert (html);
							}
						}
					});
				});
			});		
		
		</script>
	</head>
	
	<body>
		<div id="popup"></div> <!--Needed for functionality-->
		<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=150928125069674"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
		
		<div class="page-header">
			<div class="page-header-contain">
				<div class="page-header-title">
					<a href="<?php echo $url; ?>"><img src="<?php echo $url; ?>/images/logo.png" /></a>
				</div>
				
				<div class="page-header-top_menu">
					<ul>
						<li><a href="<?php echo ($user["num_calendars"] == 0) ? $url . '/manage.php' : '#new-post-box'; ?>" rel="popup">New Post</a></li>
						<li><a href="<?php echo $url; ?>/u/<?php echo $user["username"]; ?>" style="border-right:1px #000 solid;"><?php echo $user["name"]; ?></a></li>
					</ul>
					<div class="clear"></div>
				</div>
				
				<!--Popup to make a post-->
				<div id="new-post-box" class="popup" style="width:500px;">
					<div class="popup-heading">
						New Post
						<div style="float:right;margin-right:4px;">
							<img src="<?php echo $url; ?>/images/close-window.png" class="popup-close" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="popup-contain" style="width:500px;text-align:center">
						<form action="<?php echo $url; ?>/process_form/update.php" class="post-form" method="post" id="newpost" enctype="multipart/form-data">
					 		<textarea id="new-post-text" name="content" rows="1" placeholder="What do you want to captchur?" class="post-input" style="width:420px;max-width:420px;height:70px" required></textarea>
					 		<br/>
					 		<select name="calendar_id" class="post-input" style="max-width:197px;width:197px;">
					 			<option value="null">Choose Calendar...</option>
					 			<?php
					 				$UserCalendars = new UserCalendars(date('m'), date('Y'), $Database);
					 				
					 				//Display the calendar to select from
					 				foreach ($UserCalendars->getUserCalendars($user["id"]) as $calendar)
					 				{
						 				echo '<option value="' . $calendar["id"] . '" id="calendar-id-' . $calendar["id"] . '"';	//The first part of the calendar select
		 								if ($calendar["id"] == $_GET["cid"]) echo 'selected';										//If they're viewing the calendar then select it
		 								echo '>' . $calendar["title"] . '</option>';												//Finish the statement
					 				}
					 			?>
					 		</select>
							<div id="add-date"><select name="month" id="post-month" class="post-input"><option value="1" id="update-month-1">January</option><option value="2" id="update-month-2">February</option><option value="3" id="update-month-3">March</option><option value="4" id="update-month-4">April</option><option value="5" id="update-month-5">May</option><option value="6" id="update-month-6">June</option><option value="7" id="update-month-7">July</option><option value="8" id="update-month-8">August</option><option value="9" id="update-month-9">September</option><option value="10" id="update-month-10">October</option><option value="11" id="update-month-11">November</option><option value="12" id="update-month-12">December</option></select>&nbsp;<select name="day" class="post-input" id="post-day" style="max-height:100px;"><option value="1" id="select-day-1">01</option><option value="2" id="select-day-2">02</option><option value="3" id="select-day-3">03</option><option value="4" id="select-day-4">04</option><option value="5" id="select-day-5">05</option><option value="6" id="select-day-6">06</option><option value="7" id="select-day-7">07</option><option value="8" id="select-day-8">08</option><option value="9" id="select-day-9">09</option><option value="10" id="select-day-10">10</option><option value="11" id="select-day-11">11</option><option value="12" id="select-day-12">12</option><option value="13" id="select-day-13">13</option><option value="14" id="select-day-14">14</option><option value="15" id="select-day-15">15</option><option value="16" id="select-day-16">16</option><option value="17" id="select-day-17">17</option><option value="18" id="select-day-18">18</option><option value="19" id="select-day-19">19</option><option value="20" id="select-day-20">20</option><option value="21" id="select-day-21">21</option><option value="22" id="select-day-22">22</option><option value="23" id="select-day-23">23</option><option value="24" id="select-day-24">24</option><option value="25" id="select-day-25">25</option><option value="26" id="select-day-26">26</option><option value="27" id="select-day-27">27</option><option value="28" id="select-day-28">28</option><option value="29" id="select-day-29">29</option><option value="30" id="select-day-30">30</option><option value="31" id="select-day-31">31</option></select>&nbsp;<input type="text" name="year" style="width:45px;" class="post-input" id="update-year" value="<?php echo date('Y'); ?>" /></div>
						 	<div style="display:none;" id="image-add-box">
 								<input type="file" name="image" id="image" class="post-input" style="width:300px;" />
 								<label for="image" style="font-weight:bold;">Add an Image</label>
 							</div>
					 		<div style="text-align:center;">
						 		<input type="submit" name="update" class="a-button" value="Update" onClick="javascript:toggle('post-loading-circle');" id="submit-plain-post" /> 
						 		<img src="<?php echo $url; ?>/images/ajax-loader.gif" style="display:none;" id="post-loading-circle" />
						 		<a href="javascript:toggle('image-add-box');"><img src="images/image_add.png" style="margin-bottom:-4px;"/></a>
					 		</div>
						</form>
					</div>	
				</div>
				<!--End popup to make a post-->	
				
			</div>
		</div>
		
		<!--Start body-->
		<div class="main-wrapper">
