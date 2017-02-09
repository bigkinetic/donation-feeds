<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Donation_Feed {

    public function __construct () {
        $this->team_default = get_option('df_team_default', 'Donation');

        $this->external_url = get_option('df_external_feed_url');     
    }

    /**
     * Merge feeds
     *
     * @param object $current_year      Current Year
     * @param object $external_id       External feed ID
     * @return array $feed              Merged and scrubbed feed 
     */

    public function merge_feeds($current_year, $external_id) {

        $internal_feed = $this->call_internal_eft_feed($current_year);

        $external_feed = $this->call_external_feed($external_id);

        // Merge the top level arrays
        $merged = array_merge($internal_feed, $external_feed);

        // Merge multiple arrays within single array with callback 
        $merged_feed = call_user_func_array("array_merge", $merged);

        // Scrub feed
        $feed = $this->scrub_feed($merged_feed);

        if ( !empty($feed) ) {
            return $feed; 
        } else {
            // LOG ERROR
            return false;
        }

    }

    /**
     * Clean feeds
     *
     * @param array $feed               Merged feed
     * @return array $feed              Scrubbed feed 
     */

    private function scrub_feed($feed) {

        $team_member_clean = '';

        foreach ( $feed as $key => $value) {

            if ( $feed[$key]['donationForRider'] == 'Please select' ) {
                $feed[$key]['donationForRider'] = $this->team_default;
            }

            if ( $feed[$key]['donationForRider'] == 'null' ) {
                $feed[$key]['donationForRider'] = $this->team_default;
            }

            // Remove donationForCharity key - no values passed to it
            unset($feed[$key]['donationForCharity']);

            // Strip out all whitespace and make lowercase - assign team_member
            $team_member_clean = preg_replace('/\s*/', '', $feed[$key]['donationForRider']);
            $feed[$key]['team_member'] = strtolower($team_member_clean);

            if ( !array_key_exists('eft', $feed[$key]) ) {
                $feed[$key]['eft'] = null;
            }
        }

        return $feed;
    }

    /**
     * Call external feed
     *
     * @param string $external_id       External feed ID
     * @return array $external_json     Array of values returned by external feed
     */

    private function call_external_feed ($external_id) {

        $url = $this->external_url.$external_id;

        $response = wp_remote_get( $url );

        // Check for remote request fail
        if( is_wp_error( $response ) ) {
            $error = $response->get_error_message();
            // LOG ERROR
            return false;
        }

        // Decode the JSON
        try {
            // Note that we decode the body's response since it's the actual JSON feed
            $external_json = json_decode($response['body'], true);
        } catch ( Exception $ex ) {
            $external_json = null;
            // LOG ERROR
        } // end try/catch

        return $external_json;

    }

    /**
     * Call internal (eft) feed
     *
     * @param string $current_year      Current year
     * @return string $internal_json    Array of values returned by internal (eft) feed
     *
     */

    private function call_internal_eft_feed ($current_year) {

        // Get the year ID based on the current year
        $year_id = $this->get_eft_year_id($current_year);

        // Call matching eft posts
        if ($year_id != '') {
            // Get the eft posts by year from REST API endpoint
            $url = site_url().'/wp-json/wp/v2/eft?eft_year='.$year_id;
            $response = wp_remote_get($url);

            // Check for remote request fail
            if( is_wp_error( $response ) ) {
                $error = $response->get_error_message();
                // LOG ERROR
                return false; // Bail early
            }

            // Decode the JSON
            try {
                // Note that we decode the body's response since it's the actual JSON feed
                $json = json_decode($response['body']);
            } catch ( Exception $ex ) {
                $json = null;
                // LOG ERROR
            } // end try/catch

        } else {
            // LOG ERROR
            return false;
        }

        $internal_json = $this->parse_internal_eft_feed($json);
        
        return $internal_json;

    }


    /**
     * Parse internal (eft) feed
     *
     * @param object $json           Raw feed
     * @return array $feed           Parsed feed 
     */

    private function parse_internal_eft_feed($json) {

        $feed = array();

        foreach ($json as $value) {

            // Set anonymous values
            $anon = $value->anonymous;

            if ($anon == 'on'){
                $anon = true;
            } else {
                $anon = false;
            }

            $feed[] = array(
                "anonymous"         => $anon,
                "comment"           => $value->content->rendered,
                "createDate"        => $value->date,
                "donationAmount"    => $value->donationAmount,
                "donationForCharity"=> null,
                "donationForRider"  => $value->donationForRider,
                "donorEmail"        => $value->donorEmail,
                "donorName"         => $value->donorName,
                "donorTwitter"      => $value->donorTwitter,
                "eft"               => true
            );
        }

        $eft_feed['InternalEFTFeed'] = $feed;

        return $eft_feed;
    }

    /**
     * Get year ID
     *
     * @param string $current_year      Current year
     * @return string $year_id          Year ID
     */

    private function get_eft_year_id($current_year) {

        // Get the eft_year taxonomy from REST API endpoint
        $url = site_url().'/wp-json/wp/v2/eft_year';
        $response = wp_remote_get($url);

        // Check for remote request fail
        if( is_wp_error( $response ) ) {
            $error = $response->get_error_message();
            // LOG ERROR
            return false;
        }

        // Decode the JSON
        try {
            // Note that we decode the body's response since it's the actual JSON feed
            $tax_year = json_decode($response['body']);
        } catch ( Exception $ex ) {
            $tax_year = null;
            // LOG ERROR
        } // end try/catch

        // Get eft_year ID by matching name to current year
        $year_id = '';

        foreach ($tax_year as $year) {
            if ($year->name == $current_year) {
                $year_id = $year->id;
            }
        }

        return $year_id;
    }


}


