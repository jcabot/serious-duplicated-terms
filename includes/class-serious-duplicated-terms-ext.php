<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/plugins/serious-duplicated-terms/
 * @since      1.0.0
 * @package    Serious_Duplicated_Terms
 * @subpackage Serious_Duplicated_Terms\includes
 */

class Serious_Duplicated_Terms_Ext extends Serious_Duplicated_Terms {


	
	protected function define_additional_admin_hooks($plugin_admin){
		$this->loader->add_action( 'admin_post_duplicated-tags-form', $plugin_admin, 'manage_duplicates' ); //hooks to intercept the form submission
		$this->loader->add_action( 'admin_post_duplicated-cats-form', $plugin_admin, 'manage_duplicates' );
		$this->loader->add_action( 'admin_post_duplicated-tags-cats-form', $plugin_admin, 'manage_duplicates' );
	}
	

}

