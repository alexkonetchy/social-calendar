<div class="calendar-preview-box">
	<div class="calendar-preview-box-title">
		<a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $calendar["id"]; ?>" style="display:block;text-decoration:none;color:#fff;text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo $calendar["title"]; ?></a>
	</div>
	<div class="calendar-preview-box-info">
		<div class="calendar-preview-box-info-contain" style="border-right:1px #aaa dotted;">
			<?php echo $calendar["num_posts"]; ?> posts
		</div>
		<div class="calendar-preview-box-info-contain">
			<?php echo $calendar["num_followers"]; ?> followers
		</div>
		<div class="clear"></div>
	</div>
	<div class="calendar-preview-box-description" style="padding:10px;color:#777;line-height:150%;">
		<div class="dotdotdot-description" style="height:60px;border-bottom:1px #aaa dotted;">
			<?php echo $calendar["description"]; ?>
		</div>
		
		<div style="margin-top:5px;">
			<?php
				$picCount = 0;	//Picture count
				
				//Display thumbnails of the latest images on the calendar
				foreach ($UserCalendars->getCalendarImages($calendar["id"]) as $image)
				{
					if ($picCount == 0)
						echo '<a href="'.$url.'/post.php?id='.$image["id"].'"><img src="' . $image["image_location"] . '" style="border-radius:3px;max-width:100px;max-height:100px;float:left;" /></a>';
					else 
						echo '<a href="'.$url.'/post.php?id='.$image["id"].'" style="margin-right:8px;"><img src="' . $image["image_location"] . '" style="border-radius:3px;max-width:40px;max-height:40px;" /></a>';
						
						
					$picCount++;
					
					if ($picCount == 7) break;
				}
				
				if ($picCount == 0) echo '<span style="font-size:0.8em;color:#bbb;">No preview</span>';
			?>
		</div>
		
	</div>
</div>
