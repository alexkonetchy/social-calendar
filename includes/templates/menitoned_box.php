<div style="text-align:left;padding:0 10px;">
	<div class="feed-box-title">
		<a href="profile.php?id=<?php echo $status["user_id"]; ?>" style="margin:0"><img src="<?php echo $status["thumbnail"]; ?>" /></a>
		<a href="profile.php?id=<?php echo $status["user_id"]; ?>"><?php echo $status["first_name"] . ' ' . $status["last_name"]; ?></a>
		<em>mentioned you</em>
		<div class="clear"></div>
	</div>
	<div class="mentioned-text">
		<?php echo $status["content"]; ?>
	</div>
</div>
