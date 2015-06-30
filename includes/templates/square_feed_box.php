<?php

/**
 * The feedbox template. This is standard template for the feed box. 
 * The statement is usually required within a foreach loop that cycles
 * through an array fetched from the database. The template assumes that
 * the foreach loop has already been initialized and that the variable 
 * is called $status
 */
?>

<div class="square-feed-box" id="post_dp_<?php echo $status["id"]; ?>">
<?php if ($status["user_id"] == $user["id"]): ?><a href="#" class="delete_post" id="dp_<?php echo $status["id"]; ?>">x</a><?php endif; ?>
	<div class="feed-box-title">
		<a href="profile.php?id=<?php echo $status["user_id"]; ?>" style="margin:0"><img src="<?php echo $status["thumbnail"]; ?>" /></a>
		<a href="profile.php?id=<?php echo $status["user_id"]; ?>"><?php echo $status["first_name"] . ' ' . $status["last_name"]; ?></a>
		<div class="clear"></div>
	</div>

	<div class="feed-content">
		<?php 
			if (strlen($status["content"]) > 1000) $status["content"] = substr($status["content"], 0, 997) . '...';
			
			$originalContent = $status["content"];
			//Retrive the links from the status and make them clickable
			$status["content"] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank" class="no-underline">$1</a>', htmlspecialchars($status["content"]));
			
			//Retrive the usernames from the status and link them to profiles
			$status["content"] = preg_replace('/(^|\s)@([a-z0-9._]+)/i', '$1<a href="'.$url.'/u/$2" class="no-underline">@$2</a>', $status["content"]); 
			
			//Switch the type of display based on the category of the post
			switch ($status["category"])
			{
				###############################
				//If displaying an image
				case 'image':
					$dataGroupType = (isset($dataTypeFeed)) ? 'feed' : 'calendar';
					echo 	'<a class="fancybox popup-close" data-fancybox-group="'.$dataGroupType.'" title="<b><u>'.date('F jS, Y', mktime(0,0,0,$status["month"],$status["day"],$status["year"])).' / '.$status["first_name"].' ' .$status["last_name"].'</u></b> &nbsp; '.$originalContent.'" href="'.$status["image_location"].'"><img src="' . $status["image_location"] . '" style="width:100%;border-radius:5px;" title="Click to enlarge" /></a>' . nl2br($status["content"]);
					break;
					
				###############################
				//If displaying a textual post or a default post
				case 'text':
				default:
					echo nl2br($status["content"]);
			} //end switch
		?>
	</div>
	<div class="status-footer">
		<?php echo htmlspecialchars($status["location"]); /*Diplay the location*/ ?>
		@ <?php echo $status["month"] . '/' . $status["day"] . '/' . $status["year"] . ' ' . $Feed->formatTime($status["hour"], $status["minute"]);; /*datetime*/ ?>
		
		<?php if (!empty($user)): /*Only display if a user is logged in*/ ?>
			<br/>
			via <a href="calendar.php?cid=<?php echo $status["calendar_id"]; ?>" class="no-underline"><?php echo $status["title"]; ?></a>
			|
			<a href="#new-post-box" rel="popup" onClick="javascript:repost('popup<?php echo $day; ?>', '<?php echo $originalContent; ?>', '<?php echo $status["username"]; ?>');" class="no-underline">Repost</a>
		<?php endif; /*End only display if a user is logged in*/ ?>
		
		<div class="clear"></div> 
	</div>
</div>
