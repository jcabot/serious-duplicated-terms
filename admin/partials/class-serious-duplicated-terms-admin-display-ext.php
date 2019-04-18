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

		$similar_tags= $this->admin->similar_tags();
		if (!empty($similar_tags))
		{
			$admin_url=esc_url( admin_url( 'admin-post.php' ) );
			echo '<div class="wrap">' . "\n";
			echo '<form action="'.$admin_url.'" method="post" >';
			echo "<input type='hidden' name='action' value='duplicated-tags-form' />";
			echo "<table>";
			echo '<th width="40%"> Tag A </th>';
			echo '<th width="40%"> Tag B </th>';
			echo '<th width="20%"> Merge? </th>';

			foreach ( $similar_tags as $couple )
			{
				$tag1=$couple[0];
				$tag2=$couple[1];
				echo '<tr><td>' . $tag1->name . '</td><td>' . $tag2->name . '</td><td><input type="checkbox" name="' . $tag1->term_id . '" value="'. $tag2->term_id .'" /> </td></tr>';
			}
			echo '</table>';
			submit_button();
			echo '</form>';
			echo '</div>' . "\n";
		}
		else echo 'Congrats! No tags to merge';
	}





}
