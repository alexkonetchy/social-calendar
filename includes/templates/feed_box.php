<?php

/**
 * The feedbox template. This is standard template for the feed box. 
 * The statement is usually required within a foreach loop that cycles
 * through an array fetched from the database. The template assumes that
 * the foreach loop has already been initialized and that the variable 
 * is called $status
 */
?>

<div class="timeline-box" id="post_dp_<?php echo $status["id"]; ?>">
	<a href="profile.php?id=<?php echo $status["user_id"]; ?>" style="position:absolute;top:0;left:0;"><img src="<?php echo $status["thumbnail"]; ?>" style="width:25px;"/></a>
	<div class="timeline-title">
		<a href="<?php echo $url; ?>/u/<?php echo $status["username"]; ?>"><?php echo $status["first_name"] . ' ' . $status["last_name"]; ?></a>
	</div>
	<div class="timeline-content">
		<div style="position:absolute;top:-8px;right:10px;display:block;background:#fff;padding:0 5px;font-size:10px;"><?php echo $status["location"]; ?></div>
		<?php 
			require $baseDir . 'includes/templates/basic_status_content.php';
		?>
		<a href="<?php echo $url; ?>/post.php?id=<?php echo $status["id"]; ?>" style="position:absolute;bottom:-6px;right:10px;display:block;background:#fff;padding:0 5px;font-size:10px;text-decoration:none;">More</a>
	</div>
	<div class="timeline-box-footer">
		<a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $_GET["cid"]; ?>&m=<?php echo $status["month"]; ?>&y=<?php echo $status["year"]; ?>" style="color:#888;text-decoration:none;"><?php echo $status["month"] . '/' . $status["day"] . '/' . $status["year"]; /*datetime*/ ?></a>
	</div>
</div>
