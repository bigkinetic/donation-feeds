<?php


/**
 *
 * Cron sequence
 *
 * @package DonationFeeds
 * @category Cron
 * @author BigKinetic
 * @since  1.0.0
 * @return void
 *
 */

// Hook the function donation_feeds_cron_sequence() into the action donation_feeds_cron
add_action( 'donation_feeds_cron', 'donation_feeds_cron_sequence' );

function donation_feeds_cron_sequence(){


	$feed_year_start = 2015;
	$feed_year_current = get_option('df_current_year', 2017);
	$update_current = get_option('df_update_current_feeds', true);
	$external_feed_url = get_option('df_external_feed_url', '');

//$response = Donation_Feed()->call_external_feed($external_feed_url,21128);

/*
	$term_id = '';

	add_action( 'wp_loaded', 'get_eft_year_terms');

    function get_eft_year_terms(){
        $terms = get_terms([
            'taxonomy' => 'eft_year',
            'hide_empty' => false,
        ]);
    }

    foreach($terms as $term) {

    }

*/




// Create a new object
$obj = new Donation_Feed();
 
// Get the value of $prop1
//$test = $obj->call_external_feed($external_feed_url,21128);


// $test = $obj->call_internal_eft_feed('2017');

$test = $obj->merge_feeds('2017',21128);


//$total_temp = array_sum( array_column($test,'donationAmount') );


//$test = $obj($external_feed_url,21128);


$message = "Results: " . print_r( $test, true );




// $message = "Results: " . $test;

$date = date_create();

	$to = 'test@bigkinetic.com';
	$subject = 'Cron Test';
//	$body = 'Time Stamp:'.date_format($date, 'U = Y-m-d H:i:s').' '.$message;

	$body = $message;
	$headers = array('Content-Type: text/html; charset=UTF-8');
	 
	wp_mail( $to, $subject, $body, $headers );







}