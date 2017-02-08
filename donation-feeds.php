<?php
/*
Plugin Name: Donation Feeds
Plugin URI: https://bigkinetic.com
Description: A plugin for displaying the Donation feeds. 
Version: 1.0.0
Author: BigKinetic
Author URI: https://bigkinetic.com
License: GPLv2 or later
Requires at least: 4.7
Tested up to: 4.7.2

Text Domain: donation-feeds
Domain Path: /lang/
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2017 BigKinetic.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-donation-feeds.php' );
require_once( 'includes/class-donation-feeds-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-donation-feeds-admin-api.php' );
require_once( 'includes/lib/class-donation-feeds-post-type.php' );
require_once( 'includes/lib/class-donation-feeds-taxonomy.php' );

/**
 * Returns the main instance of Donation_Feeds to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Donation_Feeds
 */
function Donation_Feeds () {
	$instance = Donation_Feeds::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Donation_Feeds_Settings::instance( $instance );
	}

	return $instance;
}

Donation_Feeds();
