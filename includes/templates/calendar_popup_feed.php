<?php

/**
 * The feedbox template. This is standard template for the feed box. 
 * The statement is usually required within a foreach loop that cycles
 * through an array fetched from the database. The template assumes that
 * the foreach loop has already been initialized and that the variable 
 * is called $status
 */
?>

<div class="status-popup-box" id="post_dp_<?php echo $status["id"]; ?>">
	<a href="javascript:;" class="popup-close" style="position:absolute;top:-19px;right:-19px;z-index:9999999;"><img src="<?php echo $url; ?>/images/close-window-2.png" /></a>
	<a href="<?php echo $url; ?>/u/<?php echo $status["username"]; ?>" style="margin:0;position:absolute;top:0;left:0;"><img src="<?php echo $status["thumbnail"]; ?>" style="width:50px;height:50px;border-bottom:1px #aaa solid;border-right:1px #aaa solid;" /></a>
	<div style="position:absolute;top:0;right:0;">
		<div style="float:left;padding:2px 6px;border:1px #aaa solid;border-top:0;margin-right:5px;font-size:1.4em;"><span class="orange"><?php echo date('l', mktime(0,0,0,$month,$day,$year)); ?></span> <?php echo date('F jS, Y', mktime(0,0,0,$month,$day,$year)); /*Displays the textual day of the week, textual month, day, suffix and year*/ ?></div>
		<a href="<?php echo $url; ?>/post.php?id=<?php echo $status["id"]; ?>#comment" style="float:left;display:block;padding:2px 6px;border:1px #ccc solid;border-top:0;text-decoration:none;margin-right:15px;color:#999;">Comment</a>
		<div class="clear"></div>
	</div>
	<div class="status-popup-title">
		<a href="<?php echo $url; ?>/u/<?php echo $status["username"]; ?>"><?php echo $status["first_name"] . ' ' . $status["last_name"]; ?></a>
	</div>

	<div style="position:relative;">
		<div style="position:absolute;top:-8px;right:90px;background:#fff;font-size:11px;color:#888;padding:0 5px;"><?php echo $status["location"]; ?></div>
		<a href="<?php echo $url; ?>/post.php?id=<?php echo $status["id"]; ?>"style="text-decoration:none;font-size:11px;display:block;background:#fff;padding:0 5px;position:absolute;top:-8px;right:20px;">Expand <img src="<?php echo $url; ?>/images/d00b84133c0b47df.gif" /></a>
		<div class="status-popup-content">
			<?php 
				require $baseDir . 'includes/templates/full_status_content.php';
			?>
		</div>
	</div>
</div>
