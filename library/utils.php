<?php  
	
	function excerpt($text, $permalink){
		$text = wp_strip_all_tags($text);

        $max_length = 280 - strlen($permalink);

        if (strlen($text) > $max_length){
            $offset = ($max_length - 3) - strlen($text);
            $text = substr($text, 0, strrpos($text, ' ', $offset)) . '...';
        }

		return $text;        
	}


	function share_links($text, $post_id, $content=[]){
        $permalink = get_permalink($post_id);
		$text = excerpt($text, $permalink);
		
		$href_fb = "https://www.facebook.com/sharer/sharer.php?u=".urlencode($permalink)."&quote=".urlencode($text);
		$href_tt = "https://twitter.com/intent/tweet?text=".urlencode($text)."&url=".urlencode($permalink);

		$share["facebook"] = "<a href='".$href_fb."' target='_blank'>".$content['facebook']."</a>";
		$share["twitter"] = "<a href='".$href_tt."' target='_blank'>".$content['twitter']."</a>";
		
		return $share;
	}
