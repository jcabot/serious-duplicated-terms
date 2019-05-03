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
		$tags = get_terms( array( 'taxonomy' => 'post_tag', 'hide_empty' => 0,) );
		return $tags;
	}

	private function get_categories(){
		$categories = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => 0,) );
		return $categories;
	}

	/**
	 * Comparing the two strings for similarity
	 *
	 **/
	public function compare_terms( $a, $b, $levenshtein, $max_distance){
		$similars=false;
		if( strcasecmp($a, $b) == 0 ) return true; //case insensitive comparison

		$pos1 = stripos($a, $b);
		$pos2 = stripos($b, $a);
		if ($pos1 !== false || $pos2 !== false) return true;

		if($levenshtein)
		{
			$distance = levenshtein($a, $b);
			if($distance>-1 && $distance<=$max_distance) return true;
		}
		return false;
	}

	public function similar_tags()
	{
		$options = get_option( 'duplicated-configuration' );
		if(isset($options['levenshtein'])) 	$levenshtein=true;
		else $levenshtein=false;

		$maxdistance = $options['maxDistance'];
		if($maxdistance=="") $maxdistance=0;

		$tags1=$this->get_tags();
		$tags2=$this->get_tags();

		$similar_tags=array();
		for ($i=0, $len=count($tags1); $i<$len; $i++)  // We use index-based iterators to avoid symmetric comparisons
		{
			$tag1=$tags1[$i];
			for ($j=$i+1; $j<$len; $j++)
			{
				$tag2=$tags2[$j];
				if($this->compare_terms($tag1->name, $tag2->name, $levenshtein, $maxdistance))
					array_push($similar_tags, array($tag1,$tag2));
			}
		}
		return $similar_tags;
	}

	public function similar_categories()
	{
		error_log('---- Computing similarity of categories ---');

		$options = get_option( 'duplicated-configuration' );
		if(isset($options['levenshtein'])) 	$levenshtein=true;
		else $levenshtein=false;
		$maxdistance = $options['maxDistance'];
		if($maxdistance=="") $maxdistance=0;

		$cats1=$this->get_categories();
		$cats2=$this->get_categories();

		$similar_cats=array();
		for ($i=0, $len=count($cats1); $i<$len; $i++)  // We use index-based iterators to avoid symmetric comparisons
		{
			$cat1=$cats1[$i];
			for ($j=$i+1; $j<$len; $j++)
			{
				$cat2=$cats2[$j];
				error_log(print_r($cat1 ,true));
				if($this->compare_terms($cat1->name, $cat2->name, $levenshtein, $maxdistance))
					array_push($similar_cats, array($cat1,$cat2));

			}
		}
		return $similar_cats;
	}

	public function similar_terms()
	{
		$options = get_option( 'duplicated-configuration' );
		if(isset($options['levenshtein'])) 	$levenshtein=true;
		else $levenshtein=false;
		$maxdistance = $options['maxDistance'];
		if($maxdistance=="") $maxdistance=0;

		$cats=$this->get_categories();
		$tags=$this->get_tags();

		$similar_terms=array();
		for ($i=0, $len=count($cats); $i<$len; $i++)  // We use index-based iterators to avoid symmetric comparisons
		{
			$cat=$cats[$i];
			for ($j=0, $len_tags=count($tags); $j<$len_tags; $j++)
			{
				$tag=$tags[$j];
				if($this->compare_terms($cat->name, $tag->name, $levenshtein, $maxdistance))
					array_push($similar_terms, array($cat,$tag));

			}
		}
		return $similar_terms;
	}

	public function manage_duplicates() {
		error_log('---- Managing duplicates ---');
		global $wpdb;
		foreach($_POST as $key=>$post_data) {

			//echo "You posted:" . $key . " = " . $post_data . "<br>";
			//get the term_taxonomy_id for the terms
			error_log(print_r($key ,true));
			if($key!='action' && $key!='submit') {
				$term_keep   = get_term( substr($key,4), '', OBJECT );
				$term_remove = get_term( $post_data, '', OBJECT );

				//We first remove the term_relationships of posts linked to the term to remove that are already linked with the new term to avoid errors due to duplicaton

				$objects_term_keep = get_posts( array(
					'numberposts' => - 1,
					'tax_query'   => array(
						'relation' => 'OR',
						array( 'taxonomy' => 'category','field' => 'term_id', 'terms' => $term_keep->term_id, 'include_children' => false,),
						array( 'taxonomy' => 'post_tag','field' => 'term_id', 'terms' => $term_keep->term_id, 'include_children' => false,)
					)
				) );

				$objects_term_remove = get_posts( array(
					'numberposts' => - 1,
					'tax_query'   => array(
						'relation' => 'OR',
						array(  'taxonomy' => 'category','field' => 'term_id', 'terms' => $term_remove->term_id, 'include_children' => false,),
						array( 'taxonomy' => 'post_tag','field' => 'term_id', 'terms' => $term_remove->term_id, 'include_children' => false,),
					)
				) );

				$objects_ID_keep = array_map(function($o) { return $o->ID; }, $objects_term_keep);
				$objects_ID_remove = array_map(function($o) { return $o->ID; }, $objects_term_remove);

				$intersect = array_intersect( $objects_ID_keep, $objects_ID_remove );
				foreach ( $intersect as $repeated ) {
					$wpdb->delete( $wpdb->prefix .'term_relationships', array( 'term_taxonomy_id' => $term_remove->term_taxonomy_id, 'object_id' => $repeated ), array( '%d', '%d' ) );
				}
				//We can now update at once all the rest
				$updated = $wpdb->update( $wpdb->prefix . 'term_relationships', array( 'term_taxonomy_id' => $term_keep->term_taxonomy_id ),
					array( 'term_taxonomy_id' => $term_remove->term_taxonomy_id ), array( '%d' ), array( '%d' ) );
				if ( false !== $updated ) {
					$wpdb->update( $wpdb->prefix . 'term_taxonomy', array( 'parent' => $term_keep->term_id),array( 'parent' => $term_remove->term_id ),  array( '%d' ),array( '%d' )); //processing potentially dangling children
					$wpdb->delete( $wpdb->prefix . 'term_taxonomy', array( 'term_taxonomy_id' => $term_remove->term_taxonomy_id ),array( '%d' ) );
					$wpdb->delete( $wpdb->prefix . 'termmeta', array( 'term_id' => $term_remove->term_id ), array( '%d' ) );
					$wpdb->delete( $wpdb->prefix . 'terms', array( 'term_id' => $term_remove->term_id ), array( '%d' ) );
				}
			}
		}

		wp_redirect(admin_url('admin.php?page=duplicated-analysis' ));
		exit;
    }



}
	
