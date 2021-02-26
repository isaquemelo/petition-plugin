<?php  
	
	function share_excerpt($text, $permalink){
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
		$text = share_excerpt($text, $permalink);
		
		$href_fb = "https://www.facebook.com/sharer/sharer.php?u=".urlencode($permalink)."&quote=".urlencode($text);
		$href_tt = "https://twitter.com/intent/tweet?text=".urlencode($text)."&url=".urlencode($permalink);

		$share["facebook"] = "<a href='".$href_fb."' target='_blank'>".$content['facebook']."</a>";
		$share["twitter"] = "<a href='".$href_tt."' target='_blank'>".$content['twitter']."</a>";
		
		return $share;
	}

	function count_signatures($petition_id) {
		global $wpdb;

		$results = $wpdb->get_results(
			"SELECT count(*) as qtd FROM 
			$wpdb->postmeta as pm
			JOIN $wpdb->posts AS p ON pm.post_id = p.ID
			WHERE pm.meta_key = 'petition_id' 
			AND pm.meta_value = '{$petition_id}' AND post_status = 'publish'", OBJECT);
		
		foreach ($results as $r) {
			return $r->qtd;
		}
	}
