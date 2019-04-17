<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to manage the admin-facing aspects of the plugin.
 *
 *
 * @link       https://wordpress.org/plugins/serious-duplicated-terms/
 * @since      1.0.0
 * @package    Serious_Duplicated_Terms
 * @subpackage Serious_Duplicated_Terms\admin
 */
 		
class Serious_Duplicated_Terms_Admin_Display_Ext extends Serious_Duplicated_Terms_Admin_Display{

	/**
	 *  Rendering functions for the admin menu options
	 */
	public function	analysis_duplicated_terms() {
		echo '<div class="wrap">' . "\n";
		echo '<h1>' . 'Analysis Duplicated Terms'. '</h1>' . "\n";
		echo '</div>' . "\n";

		$similar_tags= $this->admin.similar_tags();
		if (!empty($similar_tags))
		{
			foreach ( $similar_tags as $couple )
			{
				echo $couple[1]['name'] . ' similar to ' . $couple[2]['name'];
			}
		}
	}				
}
