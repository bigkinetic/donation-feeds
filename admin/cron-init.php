<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 *
 * Run Cron action hook
 *
 * @package DonationFeeds
 * @category Cron
 * @author BigKinetic
 * @since  1.0.0
 * @return void
 *
 */

// On plugin activation schedule feeds
register_activation_hook( __FILE__, 'donation_feeds_cron_schedule' );

function donation_feeds_cron_schedule(){
  // Use wp_next_scheduled to check if the event is already scheduled
  $timestamp = wp_next_scheduled( 'donation_feeds_cron' );

  // If $timestamp == false schedule daily backups since it hasn't been done previously
  if( $timestamp == false ){
    // Schedule the event for right now, then to repeat daily using the hook 'donation_feeds_cron'
    wp_schedule_event( time(), 'daily', 'donation_feeds_cron' );
  }
}