<?php
/**
 * GravityZWR_ZOOMAPI Class.
 *
 * Extension class to interface Zoom with our API wrapper with proper headers
 *
 * @package   Gravity Forms Zoom Webinar Registration
 * @author    Michael Bourne
 * @license   GPL3
 * @link      https://5forests.com
 * @since     1.0.0
 *
 * Created Date: Friday March 25th 2020
 * Author: Michael Bourne
 * -----
 * Last Modified: Friday, February 17th 2023, 7:56:22 pm
 * Modified By: Michael Bourne
 * -----
 * Copyright (C) 2020 Michael Bourne
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * GravityZWR_ZOOMAPI Class
 */
class GravityZWR_ZOOMAPI extends GravityZWR_WordPressRemote {
	/**
	 * Prepare the headers for JSON request.
	 */
	public function run() {

		// Get bearer token from transients.
		$token = get_transient( 'gravityzwr_zoom_token' );

		// If transient not set or expired, send a POST request to the Zoom API to get a new token.
		if ( false === $token ) {

			$options = GravityZWR::get_zoom_settings_keys();

			// Set variables from contants if set, or efault to options array if not.
			$account = defined( 'GRAVITYZWR_ACCOUNT_ID' ) ? GRAVITYZWR_ACCOUNT_ID : $options['zoomaccountid'];
			$client  = defined( 'GRAVITYZWR_CLIENT_ID' ) ? GRAVITYZWR_CLIENT_ID : $options['zoomclientid'];
			$secret  = defined( 'GRAVITYZWR_CLIENT_SECRET' ) ? GRAVITYZWR_CLIENT_SECRET : $options['zoomclientsecret'];

			$token = wp_remote_post(
				'https://zoom.us/oauth/token?grant_type=account_credentials&account_id=' . $account,
				array(
					'headers' => array(
						'Host'          => 'zoom.us',
						'Authorization' => 'Basic ' . base64_encode( $client . ':' . $secret ), // phpcs:ignore
						'Content-type'  => 'application/x-www-form-urlencoded',
					),
				)
			);

			// If we get a WP Error, log it and return.
			if ( is_wp_error( $token ) ) {
				$gfaddon = new GravityZWR();
				$gfaddon->log_error( 'WP Error getting Zoom OAuth token: ' . $token->get_error_message() );
				return;
			}

			// If we get a 200 response, set the transient and get the token.
			if ( 200 === wp_remote_retrieve_response_code( $token ) ) {
				$token   = json_decode( wp_remote_retrieve_body( $token ) );
				$expires = (int) $token->expires_in ?? HOUR_IN_SECONDS;
				set_transient( 'gravityzwr_zoom_token', $token->access_token, $expires );
				$token = $token->access_token;
			} else {
				// If we don't get a 200 response, log the error and return.
				$gfaddon = new GravityZWR();
				$gfaddon->log_error( 'Zoom API Error getting Zoom token: ' . wp_remote_retrieve_response_message( $token ) );
				return;
			}
		}

		$this->arguments['headers']['Authorization'] = 'Bearer ' . $token;
		$this->arguments['headers']['Content-type']  = 'application/json';
		parent::run();
	}

}
