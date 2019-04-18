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
	public function compare_terms( $a, $b, $max_distance){
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
		$options = get_option( 'configuration' );
		$maxdistance = $options['maxDistance'];

		$tags1=$this->get_tags();
		$tags2=$this->get_tags();

		$similar_tags=array();
		for ($i=0, $len=count($tags1); $i<$len; $i++)  // We use index-based iterators to avoid symmetric comparisons
		{
			$tag1=$tags1[$i];
			for ($j=$i+1; $j<$len; $j++)
			{
				$tag2=$tags2[$j];
				if($this->compare_terms($tag1->name, $tag2->name, $maxdistance))
					array_push($similar_tags, array($tag1,$tag2));

			}
		}
		return $similar_tags;

	}

	public function manage_duplicates() {
		global $wpdb;
		foreach($_POST as $key=>$post_data) {

			//echo "You posted:" . $key . " = " . $post_data . "<br>";
			//get the term_taxonomy_id for the terms
			if($key!='action' && $key!='submit') {
				$term_keep   = get_term( $key, '', OBJECT );
				$term_remove = get_term( $post_data, '', OBJECT );

				//We first remove the term_relationships of posts linked to the term to remove that are already linked with the new term to avoid errors due to duplicaton

				$objects_term_keep = get_posts( array(
					'numberposts' => - 1,
					'tax_query'   => array(
						array( 'field' => 'term_id', 'terms' => $term_keep->term_id, 'include_children' => false )
					)
				) );


				$objects_term_remove = get_posts( array(
					'numberposts' => - 1,
					'tax_query'   => array(
						array( 'field' => 'term_id', 'terms' => $term_remove->term_id, 'include_children' => false )
					)
				) );

				$intersect = array_intersect( $objects_term_keep, $objects_term_remove );
				foreach ( $intersect as $repeated ) {
					$wpdb->delete( $wpdb->prefix .'term_relationships', array( 'term_taxonomy_id' => $term_remove->term_taxonomy_id ), array( '%d' ) );
				}
				//We can now update at once all the rest
				$updated = $wpdb->update( $wpdb->prefix . 'term_relationships', array( 'term_taxonomy_id' => $term_keep->term_taxonomy_id ),
					array( 'term_taxonomy_id' => $term_remove->term_taxonomy_id ), array( '%d' ), array( '%d' ) );
				if ( false !== $updated ) {  //if a post was already linked to the ToKeep term, the update could fail due to a duplication
					$wpdb->delete( $wpdb->prefix . 'term_taxonomy', array( 'term_taxonomy_id' => $term_remove->term_taxonomy_id ), array( '%d' ) ); //tags cannot have parents so we don't need to process potential dangling children
					$wpdb->delete( $wpdb->prefix . 'terms', array( 'term_id' => $term_remove->term_id ), array( '%d' ) );
					$wpdb->delete( $wpdb->prefix . 'termmeta', array( 'term_id' => $term_remove->term_id ), array( '%d' ) );
				}
			}
		}

		wp_redirect(admin_url('admin.php?page=analysis' )); //this will display the contents of the post variable so good for debugging but just change it to an empty value or some other string
		exit;
    }

	public function custom_redirect( $admin_notice, $response ) {
		wp_redirect( esc_url_raw( add_query_arg( array(
			'nds_admin_add_notice' => $admin_notice,
			'nds_response' => $response,
		),
			admin_url('admin.php?page='. $this->plugin_name )
		) ) );

	}

}
	
