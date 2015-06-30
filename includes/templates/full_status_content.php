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
			echo '<br/><div style="margin-top:10px;"><img src="'.$status["image_location"].'" style="max-width:100%;" /></div>';
			break;
			
		###############################
		//If displaying a textual post or anything else
		case 'text':
		default:
			preg_match_all('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', $status["content"], $media);	//Get's all of the links from the content
			
			$media = parse_url($media[1][1]);	//Seperates the first link into it's elements
			
			//Checks to see if the link is a youtube video
			if ($media["host"] == 'www.youtube.com' || $media["host"] == 'youtube.com' && $media["path"] == '/watch')
			{
				//Display the youtube video
				echo '<br/><div class="embed-container" style="margin-top:10px;"><iframe src="https://www.youtube.com/embed/'.substr($media["query"], 2, -4).'" frameborder="0" allowfullscreen></iframe></div>';
			}
			
			if ($media["host"] == 'www.vine.co' || $media["host"] == 'vine.co' && !empty($media["path"]))
			{
				//Display the vine video
				echo '<br/><div class="embed" style="margin-top:10px;"><iframe src="https://vine.co'.$media["path"].'/embed/simple" width="600" height="600" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script></div>';
			}
	} //end switch
?>
