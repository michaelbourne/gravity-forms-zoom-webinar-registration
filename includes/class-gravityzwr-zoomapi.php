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
 * Last Modified: Monday, March 30th 2020, 2:07:45 pm
 * Modified By: Michael Bourne
 * -----
 * Copyright (C) 2020 Michael Bourne
 */

use \Firebase\JWT\JWT;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * GravityZWR_ZOOMAPI Class
 */
class GravityZWR_ZOOMAPI extends GravityZWR_WordPressRemote {
	/**
	 * Prepare the headers for JSON request and generate a JWT.
	 */
	public function run() {
		$options = GravityZWR::get_zoom_settings_keys();
		$key     = $options['zoomapikey'];
        $secret  = $options['zoomapisecret'];
        $token   = array(
            'iss' => $key,
            'exp' => time() + 60,
        );

		$this->arguments['headers']['Authorization'] = 'Bearer ' . JWT::encode( $token, $secret );
		$this->arguments['headers']['Content-type']  = 'application/json';
		parent::run();
	}

}
