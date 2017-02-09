<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 *
 * Donation Feeds Admin Settings
 *
 * @package DonationFeeds
 * @category Settings
 * @author BigKinetic
 * @since  1.0.0
 *
 */

function donation_feeds_admin_settings($args) {
    $external_feed = 'Quicket'; // External feed for this project
    $current_year = 2015; // Forced to reset
    $year_options = array();

    for ($y = 2015; $y <= 2025; $y++) {
        $year_options[$y] = $y;             
    }

    $error_number = array();
    for ($n = 24; $n <= 100; $n++) {
        $error_number[$n] = $n;
    }

    // Donation Feed Settings: General Settings
    $settings['general'] = array(
        'title'                 => __( 'General', 'donation-feeds' ),
        'description'           => __( 'General Feed Settings.', 'donation-feeds' ),
        'fields'                => array(
            array(
                'id'            => 'current_year',
                'label'         => __( 'Current year', 'donation-feeds' ),
                'description'   => __( 'Select the current year.', 'donation-feeds' ),
                'type'          => 'select',
                'options'       => $year_options,
                'default'       => $current_year
            ),
            array(
                'id'            => 'update_current_feeds',
                'label'         => __( 'Update current feeds only', 'donation-feeds' ),
                'description'   => __( 'Check this option to update the feeds for the current year only (Recommended: this will greatly reduce the load on the server).', 'donation-feeds' ),
                'type'          => 'checkbox',
                'default'       => ''
            ),
            array(
                'id'            => 'error_log_no',
                'label'         => __( 'Error log items', 'donation-feeds' ),
                'description'   => __( 'Select the number of error log entries to display (Note: will only take effect when next cronjob runs).', 'donation-feeds' ),
                'type'          => 'select',
                'options'       => $error_number,
                'default'       => 'null'
            ),
            array(
                'id'            => 'team_default',
                'label'         => __( 'Default team member', 'donation-feeds' ),
                'description'   => __( 'Fallback, if donation not assigned to a team member', 'donation-feeds' ),
                'type'          => 'text',
                'default'       => '',
                'placeholder'   => __( '', 'donation-feeds' )
            ),
        )
    );

    // Donation Feed Settings: Notifications
    $settings['notifications'] = array(
        'title'                 => __( 'Notifications', 'donation-feeds' ),
        'description'           => __( 'Configure the email notifications.', 'donation-feeds' ),
        'fields'                => array(
            array(
                'id'            => 'error_notifications_send_to',
                'label'         => __( 'To: (Error notifications)' , 'donation-feeds' ),
                'description'   => __( 'Add a comma separated list of email addresses to receive<br>notifications if an Error or Warning message is triggered.', 'donation-feeds' ),
                'type'          => 'textarea',
                'default'       => '',
                'placeholder'   => __( '', 'donation-feeds' )
            ),
            array(
                'id'            => 'error_notifications_disable',
                'label'         => __( 'Disable error notifications', 'donation-feeds' ),
                'description'   => __( 'Check this option to disable the email error notifications.', 'donation-feeds' ),
                'type'          => 'checkbox',
                'default'       => ''
            ),
            array(
                'id'            => 'error_log_send_to',
                'label'         => __( 'To: (Error log emails)' , 'donation-feeds' ),
                'description'   => __( 'Add a comma separated list of email addresses to receive<br>error logs (when the Cronjob runs).', 'donation-feeds' ),
                'type'          => 'textarea',
                'default'       => '',
                'placeholder'   => __( '', 'donation-feeds' )
            ),
            array(
                'id'            => 'error_log_disable',
                'label'         => __( 'Disable error log emails', 'donation-feeds' ),
                'description'   => __( 'Check this option to disable the error log emails.', 'donation-feeds' ),
                'type'          => 'checkbox',
                'default'       => ''
            ),
        )
    );

    // Donation Feed Settings: External Feed Settings
    $settings['external'] = array(
        'title'                 => __( $external_feed, 'donation-feeds' ),
        'description'           => __( 'Configure the '.$external_feed.' API connections.', 'donation-feeds' ),
        'fields'                => array(
            array(
                'id'            => 'external_feed_url',
                'label'         => __( $external_feed.' Feed URL' , 'donation-feeds' ),
                'description'   => __( 'Enter the URL (excluding the ID)', 'donation-feeds' ),
                'type'          => 'textarea',
                'default'       => '',
                'placeholder'   => __( '', 'donation-feeds' )
            ),
        )
    );

    for ($q = 2015; $q <= 2025; $q++) {
        $settings['external']['fields'][] = array(
            'id'            => 'external_id_'.$q,
            'label'         => __( 'Feed ID: '.$q , 'donation-feeds' ),
            'description'   => __( 'Enter the ID number', 'donation-feeds' ),
            'type'          => 'number',
            'default'       => '',
            'placeholder'   => __( '', 'donation-feeds' )
        );            
    }

    return $settings;
}

add_filter ( 'donation_feeds_settings_fields', 'donation_feeds_admin_settings');