<?php
/**
 * @version    $Id$
 * @package    IG_Sample_Plugin
 * @author     InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 InnoThemes.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 * Technical Support:  Feedback - http://www.innothemes.com/contact-us/get-support.html
 */
/**
 * Installer
 */
class IG_Uniform_Installer {

	public function on_activate_function() {
		global $wpdb;
		$wpdb->hide_errors();
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty( $wpdb->collate ) ) $collate .= " COLLATE $wpdb->collate";
		}
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$uniformTables = "
          CREATE TABLE {$wpdb->prefix}ig_uniform_fields (
            field_id int(11) NOT NULL AUTO_INCREMENT,
            form_id int(11) NOT NULL,
            field_type varchar(45) NOT NULL,
            field_identifier varchar(255) NOT NULL,
            field_title varchar(255) DEFAULT NULL,
            field_instructions text,
            field_position varchar(50) NOT NULL,
            field_ordering int(10) unsigned NOT NULL DEFAULT '0',
            field_settings text,
            PRIMARY KEY (field_id)
          ) $collate;

          CREATE TABLE {$wpdb->prefix}ig_uniform_form_pages (
            page_id int(11) NOT NULL AUTO_INCREMENT,
            page_title varchar(255) NOT NULL,
            form_id int(11) NOT NULL,
            page_content longtext NOT NULL,
            page_template longtext NOT NULL,
            page_container longtext NOT NULL,
            PRIMARY KEY (page_id)
          )$collate;

          CREATE TABLE {$wpdb->prefix}ig_uniform_submission_data (
            submission_data_id int(11) NOT NULL AUTO_INCREMENT,
            submission_id int(11) NOT NULL,
            form_id int(11) NOT NULL,
            field_id int(11) NOT NULL,
            field_type varchar(45) NOT NULL,
            submission_data_value longtext NOT NULL,
            PRIMARY KEY (submission_data_id),
            KEY submission_data_id (submission_data_id),
            KEY submission_id (submission_id),
            KEY form_id (form_id),
            KEY field_id (field_id)
          )$collate;
          ";
		dbDelta( $uniformTables );
	}

	public function on_uninstaller_function() {

	}
}
