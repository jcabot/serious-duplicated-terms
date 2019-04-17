<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/serious-duplicated-terms/
 * @since      1.0.0
 * @package    Serious_Duplicated_Terms
 * @subpackage Serious_Duplicated_Terms\admin
 */

class Serious_Duplicated_Terms_Admin_Ext extends Serious_Duplicated_Terms_Admin {

	private function get_tags(){
		$categories = get_terms( array( 'taxonomy' => 'post_tag', 'hide_empty' => 0,) );
		return $categories;
	}

	private function get_categrories(){
		$tags = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => 0,) );
		return $tags;
	}

	/**
	 * Comparing the two strings for similarity
	 *
	 **/
	public function compare_terms( $a, $b,$max_distance){
		$similars=false;
		if( strcasecmp($a, $b) == 0 ) return true; //case insensitive comparison

		$pos1 = stripos($a, $b);
		$pos2 = stripos($b, $a);
		if ($pos1 !== false || $pos2 !== false) return true;

		if(isset($max_distance))
		{
			$distance = levenshtein($a, $b);
			if($distance>-1 && $distance<=$max_distance) return true;
		}
		return false;
	}

	public function similar_tags()
	{
		$tags1=$this->get_tags();
		$tags2=$this->get_tags();

		$similar_tags=array();
		foreach( $tags1 as $tag1 )
		{
			foreach ( $tags2 as $tag2 )
			{
				if ($tag1['term_id']!=$tag2['term_id'])
				{
					if(compare_terms($tag1['name'],$tag2['name']))
					array_push($similar_tags, array($tag1,$tag2));
				}
			}
		}
		return $similar_tags;

	}


}
	
