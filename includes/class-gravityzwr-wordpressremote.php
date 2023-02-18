<?php
/**
 * GravityZWR_WordPressRemote Class
 *
 * General wp_remote_request wrapper for API interfacing
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
 * Last Modified: Friday, February 17th 2023, 7:02:39 pm
 * Modified By: Michael Bourne
 * -----
 * Copyright (C) 2020 Michael Bourne
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

 /**
  * GravityZWR_WordPressRemote Class
  */
class GravityZWR_WordPressRemote {


	/**
	 * Request method
	 *
	 * @var string
	 */
	protected $method = '';

	/**
	 * URL where to send the request
	 *
	 * @var string
	 */
	protected $url = '';

	/**
	 * Arguments applied to the request
	 *
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * Request response
	 *
	 * @var array|WP_Error
	 */
	protected $response;

	/**
	 * Body of the response
	 *
	 * @var string
	 */
	protected $body;

	/**
	 * Headers of the response
	 *
	 * @var array
	 */
	protected $headers;

	/**
	 * Response Code from the Request
	 *
	 * @var int|string
	 */
	protected $response_code;

	/**
	 * Response Message from the Request
	 *
	 * @var string
	 */
	protected $response_message;

	/**
	 * Creating the object
	 *
	 * @param string $url Request URL.
	 * @param array  $arguments Request Arguements.
	 * @param string $method Request Method.
	 */
	public function __construct( string $url, array $arguments = array(), string $method = 'get' ) {
		$this->method              = strtoupper( $method );
		$this->url                 = $url;
		$this->arguments           = $arguments;
		$this->arguments['method'] = $this->method;
	}

	/**
	 * Running the request and setting all the attributes
	 *
	 * @return void
	 */
	public function run() {
		$this->response         = wp_remote_request( $this->url, $this->arguments );
		$this->body             = wp_remote_retrieve_body( $this->response );
		$this->headers          = wp_remote_retrieve_headers( $this->response );
		$this->response_code    = wp_remote_retrieve_response_code( $this->response );
		$this->response_message = wp_remote_retrieve_response_message( $this->response );
	}

	/**
	 * Get the body
	 *
	 * @return string
	 */
	public function get_body(): string {
		return $this->body;
	}

	/**
	 * Get the headers
	 *
	 * @return array
	 */
	public function get_headers(): array {
		return (array) $this->headers;
	}

	/**
	 * Response Code
	 *
	 * @return string
	 */
	public function get_response_code(): int {
		return (int) $this->response_code;
	}

	/**
	 * Get the response message
	 *
	 * @return string
	 */
	public function get_response_message(): string {
		return $this->response_message;
	}

	/**
	 * Get the whole response
	 *
	 * @return array|WP_Error
	 */
	public function get_response() {
		return $this->response;
	}

	/**
	 * If the request was a success
	 *
	 * @return bool
	 */
	public function is_success(): bool {
		// phpcs:ignore
		if ( '200' == (string) $this->response_code ) {
			return true;
		}
		// phpcs:ignore
		if ( '201' == (string) $this->response_code ) {
			return true;
		}
		return false;
	}
}
