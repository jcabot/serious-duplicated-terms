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
		echo '<i> (before taking any action, we strongly recommend you to create first a backup of your WordPress site) </i>';
		echo '</div>' . "\n";

		$similar_tags= $this->admin->similar_tags();
		echo '<div class="wrap">' . "\n";
		echo '<h2> Duplicated tags </h2> ' . "\n";
		if (!empty($similar_tags))
		{
			$admin_url=esc_url( admin_url( 'admin-post.php' ) );
			echo '<form action="'.$admin_url.'" method="post" >';
			echo "<input type='hidden' name='action' value='duplicated-tags-form' />";
			echo '<table class="form-table">';
			echo '<th width="40%"> Tag </th>';
			echo '<th width="40%"> Tag </th>';
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
		}
		else echo 'Congrats! No tags to merge';
		echo '</div>' . "\n";

		echo '<hr>' . "\n";

		echo '<div class="wrap">' . "\n";
		echo '<h2> Duplicated Categories </h2> ' . "\n";
		$similar_cats= $this->admin->similar_categories();
		if (!empty($similar_cats))
		{
			$admin_url=esc_url( admin_url( 'admin-post.php' ) );
			echo '<form action="'.$admin_url.'" method="post" >';
			echo "<input type='hidden' name='action' value='duplicated-cats-form' />";
			echo '<table class="form-table">';
			echo '<th width="40%"> Category </th>';
			echo '<th width="40%"> Category </th>';
			echo '<th width="20%"> Merge? </th>';

			foreach ( $similar_cats as $couple )
			{
				$cat1=$couple[0];
				$cat2=$couple[1];
				echo '<tr><td>' . $cat1->name . '</td><td>' . $cat2->name . '</td><td><input type="checkbox" name="' . $cat1->term_id . '" value="'. $cat2->term_id .'" /> </td></tr>';
			}
			echo '</table>';
			submit_button();
			echo '</form>';
		}
		else echo 'Congrats! No categories to merge';
		echo '</div>' . "\n";

		echo '<hr>' . "\n";

		echo '<div class="wrap">' . "\n";
		echo '<h2> Tags already defined as Categories </h2> ' . "\n";
		$similar_terms= $this->admin->similar_terms();
		if (!empty($similar_terms))
		{
			$admin_url=esc_url( admin_url( 'admin-post.php' ) );
			echo '<form action="'.$admin_url.'" method="post" >';
			echo "<input type='hidden' name='action' value='duplicated-tags-cats-form' />";
			echo '<table class="form-table">';
			echo '<th width="40%"> Category </th>';
			echo '<th width="40%"> Tag </th>';
			echo '<th width="20%"> Merge Tag? </th>';

			foreach ( $similar_terms as $couple )
			{
				$cat=$couple[0];
				$tag=$couple[1];
				echo '<tr><td>' . $cat->name . '</td><td>' . $tag->name . '</td><td><input type="checkbox" name="' . $cat->term_id . '" value="'. $tag->term_id .'" /> </td></tr>';
			}
			echo '</table>';
			submit_button();
			echo '</form>';
		}
		else echo 'Congrats! No Tags to merge';
		echo '</div>' . "\n";
	}

}
