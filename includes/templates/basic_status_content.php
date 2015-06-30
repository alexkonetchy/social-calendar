<?php
	//The original unedited content
	$originalContent = $status["content"]; 	
	
	//Retrieves all of the links and lets people click them 
	//Securely protects content from XSS
	$status["content"] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank" class="no-underline">$1</a>', htmlspecialchars($status["content"]));
			
	//Retrive the usernames from the status and link them to profiles
	$status["content"] = preg_replace('/(^|\s)@([a-z0-9._]+)/i', '$1<a href="'.$url.'/u/$2" class="no-underline">@$2</a>', $status["content"]);
	
	 
	echo nl2br($status["content"]);	//Echo the content
	
	//Determine if there should be a link to display the image or a youtube video
	switch ($status["category"])
	{
		###############################
		//If displaying an image
		case 'image':
			echo '<a href="'.$url.'/post.php?id='.$status["id"].'" style="text-decoration:none;position:absolute;bottom:-6px;right:55px;display:block;background:#fff;padding:0 5px;font-size:10px;">View photo</a>';
			break;
			
		###############################
		//If displaying a textual post or anything else
		case 'text':
		default:
			preg_match_all('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', $status["content"], $media);	//Get's all of the links from the content
			
			$media = parse_url($media[1][1]);	//Seperates the first link into it's elements
			
			//Checks to see if the link is a youtube video
			if ($media["host"] == 'www.youtube.com' || $media["host"] == 'youtube.com' && $media["path"] == '/watch')
				echo '<a href="'.$url.'/post.php?id='.$status["id"].'" style="text-decoration:none;position:absolute;bottom:-6px;right:55px;display:block;background:#fff;padding:0 5px;font-size:10px;">View media</a>';
			
			if ($media["host"] == 'www.vine.co' || $media["host"] == 'vine.co' && !empty($media["path"]))
				echo '<a href="'.$url.'/post.php?id='.$status["id"].'" style="text-decoration:none;position:absolute;bottom:-6px;right:55px;display:block;background:#fff;padding:0 5px;font-size:10px;">View media</a>';
	} //end switch
?>
