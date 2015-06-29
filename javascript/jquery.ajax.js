	
	//Update Calendar AJAX
	$(document).ready(function() {
	    $('#submit-plain-post').click(function () {        
	        
	        var content = $('textarea[name=content]');
	        var location = $('input[name=location]');
	        var month = $('select[name=month]');
	        var day = $('select[name=day]');
	        var year = $('input[name=year]');
	        var c_id = $('input[name=calendar_id]');
	        
	        //Validate
	        if (content.val()=='') {
				content.addClass('highlight-field');
				return false;
			} else content.removeClass('highlight-field');
			if (year.val()=='') {
				year.addClass('highlight-field');
				return false;
			} else year.removeClass('highlight-field');
	        
	        var data = 'content=' + encodeURIComponent(content.val()) + '&location=' + location.val() + '&month='
	        + month.val() + '&day='  + day.val() + '&year=' + year.val() + '&calendar_id=' + c_id.val() + '&submit=yes';
	        
	        $('.post-input').attr('disabled','true');
	        $('.a-button').attr('disabled','true');
	        $('.loading-circle').show();
	        
	        //start the ajax
	        $.ajax({
	            url: "<?php echo $url; ?>/process_form/update.php",    
	            type: "GET",        
	            data: data,        
	            cache: false,
	            success: function (html) {                
	                if (html==1) {                    
	               		setTimeout("location.reload(true);",0);
	                } 
	                else {
		                alert(html); 
		                setTimeout("location.reload(true);",0); 
	                }              
	            }        
	        });
	        return false;
	    });    
	}); 
	
	//Delete Post AJAX
	$(document).ready(function() {
	    $('.delete_post').click(function () {        
	        
	        var id = $(this).attr('id');
	        var data = 'id=' + id + '&submit=yes';
	        $(this).parent().fadeOut(900);
	    	$('.delete_post').attr('disabled','true');
	        //start the ajax
	        $.ajax({
	            url: "<?php echo $url; ?>/process_form/delete_post.php",    
	            type: "GET",        
	            data: data,        
	            cache: false,
	            success: function (html) {                
	                if (html!=1) {
		                alert(html); 
	                }              
	            }        
	        });
	        return false;
	    });    
	}); 
