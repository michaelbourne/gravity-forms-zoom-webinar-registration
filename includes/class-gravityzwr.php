<?php
/**
 * GravityZWR Class.
 *
 * Main plugin class to add an add-on to Gravity Forms.
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
 * Last Modified: Monday, March 30th 2020, 2:07:39 pm
 * Modified By: Michael Bourne
 * -----
 * Copyright (C) 2020 Michael Bourne
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

GFForms::include_feed_addon_framework();

/**
 * GravityZWR Class is an extension of the Gravity Forms Add-On Class.
 *
 * @uses GFAddOn::log_error()
 * @uses GFAddOn::log_debug()
 * @uses GFAddOn::get_plugin_settings()
 * @uses GFCache::delete()
 */
class GravityZWR extends GFFeedAddOn {

	/**
	 * Plugin Version
	 *
	 * @var string $_version
	 */
	protected $_version = GRAVITYZWR_VERSION;

	/**
	 * Minimum required version of Gravity Forms
	 *
	 * @var string $_min_gravityforms_version
	 */
	protected $_min_gravityforms_version = '2.2';

	/**
	 * Plugin Slug
	 *
	 * @var string $_slug
	 */
	protected $_slug = 'gravity-forms-zoom-webinar-registration';

	/**
	 * Plugin Path
	 *
	 * @var string $_path
	 */
	protected $_path = 'gravity-forms-zoom-webinar-registration/gravity-forms-zoom-webinar-registration.php';

	/**
	 * Plugin Full Path
	 *
	 * @var [type]
	 */
	protected $_full_path = __FILE__;

	/**
	 * Title of Add-On
	 *
	 * @var string $_title
	 */
	protected $_title = 'Gravity Forms Zoom Webinar Registration';

	/**
	 * Short Title of Add-On
	 *
	 * @var string $_short_title
	 */
	protected $_short_title = 'Zoom Webinar';

	/**
	 * Defines if Add-On should use Gravity Forms servers for update data.
	 *
	 * @var    bool
	 */
	protected $_enable_rg_autoupgrade = false;

	/**
	 * Defines the capability needed to access the Add-On settings page.
	 *
	 * @var    string $_capabilities_settings_page The capability needed to access the Add-On settings page.
	 */
	protected $_capabilities_settings_page = 'gravityforms_zoomwr';

	/**
	 * Defines the capability needed to access the Add-On form settings page.
	 *
	 * @var    string $_capabilities_form_settings The capability needed to access the Add-On form settings page.
	 */
	protected $_capabilities_form_settings = 'gravityforms_zoomwr';

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'gravityforms_zoomwr_uninstall';

	/**
	 * Defines the capabilities needed for the Post Creation Add-On
	 *
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = array( 'gravityforms_zoomwr', 'gravityforms_zoomwr_uninstall' );

	/**
	 * Core singleton class
	 *
	 * @var self - pattern realization
	 */
	private static $_instance;

	/**
	 * Get an instance of this class.
	 *
	 * @return GravityZWR
	 */
	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new GravityZWR();
		}

		return self::$_instance;
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {
		parent::init();

		$this->add_delayed_payment_support(
			array(
				'option_label' => esc_html__( 'Register contact to Zoom Webinar only when payment is received.', 'gravity-zwr' ),
			)
		);

		$plugin_settings = GFCache::get( 'zwr_plugin_settings' );
		if ( empty( $plugin_settings ) ) {
			$plugin_settings = $this->get_plugin_settings();
			GFCache::set( 'zwr_plugin_settings', $plugin_settings );
		}
	}

	/**
	 * Remove unneeded settings.
	 */
	public function uninstall() {

		parent::uninstall();

		GFCache::delete( 'zwr_plugin_settings' );
	}

	/**
	 * Creates a custom page for this add-on.
	 */
	public function plugin_page() {
		echo 'This page appears in the Forms menu';
	}

	/**
	 * Prevent feeds being listed or created if an api key isn't valid.
	 *
	 * @return bool
	 */
	public function can_create_feed() {

		// Get the plugin settings.
		$settings = $this->get_plugin_settings();

		// Access a specific setting e.g. an api key
		$key = rgar( $settings, 'zoomapikey' );
		$sec = rgar( $settings, 'zoomapisecret' );

		if ( empty( $key ) || empty( $sec ) ) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * Configures which columns should be displayed on the feed list page.
	 *
	 * @return array
	 */
	public function feed_list_columns() {

		return array(
			'feedName'      => esc_html__( 'Feed Name', 'gravity-zwr' ),
			'zoomWebinarID' => esc_html__( 'Meeting ID', 'gravity-zwr' ),
		);

	}

	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {
		return array(
			array(
				'title'       => esc_html__( 'Zoom Webinar Settings', 'gravity-zwr' ),
				'description' => '<p>' .
					sprintf(
						esc_html__( 'Zoom Webinars make it easy to host your virtual events. Use Gravity Forms to create new attendees in your webinars, without the need of 3rd party services. You will need to %1$screate your own JWT private app%2$s to make this add-on work.', 'gravity-zwr' ),
						'<a href="https://marketplace.zoom.us/docs/guides/getting-started/app-types/create-jwt-app" target="_blank">', '</a>'
					)
					. '</p>',
				'fields'      => array(
					array(
						'name'              => 'zoomapikey',
						'tooltip'           => esc_html__( 'This is the JWT app API key from your private app', 'gravity-zwr' ),
						'label'             => esc_html__( 'Zoom API Key', 'gravity-zwr' ),
						'type'              => 'text',
						'class'             => 'medium',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
					array(
						'name'              => 'zoomapisecret',
						'tooltip'           => esc_html__( 'This is the JWT app secret key from your private app', 'gravity-zwr' ),
						'label'             => esc_html__( 'Zoom API Secret', 'gravity-zwr' ),
						'type'              => 'text',
						'class'             => 'medium',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
					),
				),
			),
		);
	}

	/**
	 * Configures the settings which should be rendered on the Form Settings > Zoom Webinar tab.
	 *
	 * @return array
	 */
	public function feed_settings_fields() {
		return array(
			array(
				'title'  => esc_html__( 'Zoom Webinar Settings', 'gravity-zwr' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'Meeting Type', 'gravity-zwr' ),
						'type'    => 'select',
						'name'    => 'meetingtype',
						'tooltip' => esc_html__( 'While created for Webinars, this feed will also work for normal meetings', 'gravity-zwr' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Webinar', 'gravity-zwr' ),
								'value' => 'webinars',
							),
							array(
								'label' => esc_html__( 'Meeting', 'gravity-zwr' ),
								'value' => 'meetings',
							),
						),
					),
					array(
						'name'     => 'feedName',
						'label'    => esc_html__( 'Name', 'gravity-zwr' ),
						'type'     => 'text',
						'required' => true,
						'class'    => 'medium',
						'tooltip'  => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Name', 'gravity-zwr' ),
							esc_html__( 'Enter a feed name to uniquely identify this setup.', 'gravity-zwr' )
						),
					),
					array(
						'name'     => 'zoomWebinarID',
						'label'    => esc_html__( 'Webinar ID', 'gravity-zwr' ),
						'type'     => 'text',
						'required' => true,
						'tooltip'  => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Webinar ID', 'gravity-zwr' ),
							esc_html__( 'Add the Webinar ID. You will find this in your Zoom.us webinar setup.', 'gravity-zwr' )
						),
					),
				),
			),
			array(
				'title'  => esc_html__( 'Registration Fields', 'gravity-zwr' ),
				'fields' => array(
					array(
						'name'      => 'mappedFields',
						'label'     => esc_html__( 'Match fields', 'gravity-zwr' ),
						'type'      => 'field_map',
						'field_map' => $this->merge_vars_field_map(),
						'tooltip'   => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Map Fields', 'gravity-zwr' ),
							esc_html__( 'Setup the Zoom Webinar Registration fields by selecting the appropriate form field from the list.', 'gravity-zwr' )
						),
					),
				),
			),
			array(
				'title'  => 'Feed Conditions',
				'fields' => array(
					array(
						'name'           => 'condition',
						'label'          => esc_html__( 'Condition', 'gravity-zwr' ),
						'type'           => 'feed_condition',
						'checkbox_label' => esc_html__( 'Enable Condition', 'gravity-zwr' ),
						'instructions'   => esc_html__( 'Process this feed if', 'gravity-zwr' ),
					),
				),
			),
		);
	}

	/**
	 * Return an array of Zoom Webinar list fields which can be mapped to the Form fields/entry meta.
	 *
	 * @return array
	 */
	public function merge_vars_field_map() {

		// Initialize field map array.
		$field_map = array();

		// Get merge fields.
		$merge_fields = $this->get_list_merge_fields();

		// If merge fields exist, add to field map.
		if ( ! empty( $merge_fields ) && is_array( $merge_fields ) ) {

			// Loop through merge fields.
			foreach ( $merge_fields as $field => $config ) {

				// Define required field type.
				$field_type = null;

				switch ( strtolower( $config['type'] ) ) {
					case 'name':
						$field_type = array( 'name', 'text' );
						break;

					case 'email':
						$field_type = array( 'email' );
						break;

					case 'phone':
						$field_type = array( 'phone', 'text' );
						break;

					case 'select':
						$field_type = array( 'select', 'radio' );
						break;

					case 'text':
						$field_type = array( 'textarea' );
						break;

					case 'address':
						$field_type = array( 'address', 'text' );
						break;

					default:
						$field_type = array( 'text', 'hidden' );
						break;
				}

				// Add to field map.
				$field_map[ $field ] = array(
					'name'       => $field,
					'label'      => $config['name'],
					'required'   => $config['required'],
					'field_type' => $field_type,
					'tooltip'	 => $config['description'],
				);

			}
		}

		return $field_map;
	}

	// # FEED PROCESSING -----------------------------------------------------------------------------------------------

	/**
	 * Process the feed: register user with Zoom Webinar.
	 *
	 * @param array $feed  The feed object to be processed.
	 * @param array $entry The entry object currently being processed.
	 * @param array $form  The form object currently being processed.
	 *
	 * @return array
	 */
	public function process_feed( $feed, $entry, $form ) {

		// Log that we are processing feed.
		$this->log_debug( __METHOD__ . '(): Processing feed.' );

		$webinar = preg_replace( '/[^0-9]/', '', $feed['meta']['zoomWebinarID'] );

		if ( empty( $webinar ) ) {
			$this->add_feed_error( esc_html__( 'Aborted: Empty Webinar ID', 'gravity-zwr' ), $feed, $entry, $form );
			return $entry;
		}

		$meetingtype = in_array( $feed['meta']['meetingtype'], [ 'webinars', 'meetings' ], true ) ? $feed['meta']['meetingtype'] : 'webinars';

		$settings = $this->get_plugin_settings();

		if ( empty( $settings ) ) {
			$this->add_feed_error( esc_html__( 'Aborted: Empty Plugin Settings', 'gravity-zwr' ), $feed, $entry, $form );
			return $entry;
		}

		// Retrieve the name => value pairs for all fields mapped in the 'mappedFields' field map.
		$field_map = $this->get_field_map_fields( $feed, 'mappedFields' );

		// Get mapped email address.
		$email = $this->get_field_value( $form, $entry, $field_map['email'] );

		// If email address is invalid, log error and return.
		if ( GFCommon::is_invalid_or_empty_email( $email ) ) {
			$this->add_feed_error( esc_html__( 'A valid Email address must be provided.', 'gravity-zwr' ), $feed, $entry, $form );
			return $entry;
		}

		// Loop through the fields from the field map setting building an array of values to be passed to the third-party service.
		$merge_vars = array();
		foreach ( $field_map as $name => $field_id ) {

			// If no field is mapped, skip it.
			if ( rgblank( $field_id ) ) {
				continue;
			}

			// Get field value.
			$field_value = $this->get_field_value( $form, $entry, $field_id );

			// If field value is empty, skip it.
			if ( empty( $field_value ) ) {
				continue;
			}

			// Get the field value for the specified field id
			$merge_vars[ $name ] = $field_value;

		}

		if ( empty( $merge_vars ) ) {
			$this->add_feed_error( esc_html__( 'Aborted: Empty merge fields', 'gravity-zwr' ), $feed, $entry, $form );
			return $entry;
		}

		$remote_request = new GravityZWR_ZOOMAPI( GRAVITYZWR_ZOOMAPIURL . '/' . $meetingtype . '/' . $webinar . '/registrants', array( 'body' => wp_json_encode( $merge_vars ) ), 'post' );
		$remote_request->run();

		if ( ! $remote_request->is_success() ) {
			// Log that registration failed.
			$this->add_feed_error( esc_html__( 'Zoom API error when attempting registration: ' . print_r( $remote_request->get_response(), true ), 'gravity-zwr' ), $feed, $entry, $form ); // phpcs:ignore
			return false;
		} else {
			// Log that the registrant was added.
			$this->log_debug( __METHOD__ . '(): Registrant successfull: ' . print_r( $remote_request->get_body(), true ) ); // phpcs:ignore
		}

		return $entry;

	}


	// # HELPERS -------------------------------------------------------------------------------------------------------

	/**
	 * The feedback callback for the settings fields.
	 *
	 * @param string $value The setting value.
	 *
	 * @return bool
	 */
	public function is_valid_setting( $value ) {
		return strlen( $value ) > 10;
	}

	/**
	 * Retrieve the Zoom settings keys.
	 *
	 * @return array
	 */
	public static function get_zoom_settings_keys() {

		$plugin_settings = GFCache::get( 'zwr_plugin_settings' );
		return $plugin_settings;
	}

	/**
	 * Get Zoom Webinar registration merge fields for list.
	 *
	 * @return array
	 */
	public function get_list_merge_fields() {

		$fields = array(
			'first_name' 			   => array(
					'type' 		  => 'name',
					'name'		  => 'First Name',
					'description' => 'Registrant\'s First Name.',
					'required'	  => true,
				),
			'last_name' 			   => array(
					'type' 		  => 'name',
					'name'		  => 'Last Name',
					'description' => 'Registrant\'s Last Name.',
					'required'	  => true,
				),
			'email' 				   => array(
					'type' 		  => 'email',
					'name'		  => 'Email',
					'description' => 'Registrant\'s Email.',
					'required'	  => true,
				),
			'address' 				   => array(
					'type' 		  => 'address',
					'name'		  => 'Address',
					'description' => 'Registrant\'s address.',
					'required'	  => false,
				),
			'city' 					   => array(
					'type' 		  => 'address',
					'name'		  => 'City',
					'description' => 'Registrant\'s city.',
					'required'	  => false,
				),
			'country' 				   => array(
					'type' 		  => 'address',
					'name'		  => 'Country',
					'description' => 'Registrant\'s country.',
					'required'	  => false,
				),
			'zip'					   => array(
					'type' 		  => 'address',
					'name'		  => 'ZIP',
					'description' => 'Registrant\'s Zip/Postal Code.',
					'required'	  => false,
				),
			'state' 				   => array(
					'type' 		  => 'address',
					'name'		  => 'State',
					'description' => 'Registrant\'s State/Province.',
					'required'	  => false,
				),
			'phone' 				   => array(
					'type' 		  => 'phone',
					'name'		  => 'Phone',
					'description' => 'Registrant\'s Phone number.',
					'required'	  => false,
				),
			'industry' 				   => array(
					'type' 		  => 'string',
					'name'		  => 'Industry',
					'description' => 'Registrant\'s Industry.',
					'required'	  => false,
				),
			'org' 					   => array(
					'type' 		  => 'string',
					'name'		  => 'Organization',
					'description' => 'Registrant\'s Organization.',
					'required'	  => false,
				),
			'job_title' 			   => array(
					'type' 		  => 'string',
					'name'		  => 'Job Title',
					'description' => 'Registrant\'s job title.',
					'required'	  => false,
				),
			'purchasing_time_frame'    => array(
					'type' 		  => 'select',
					'name'		  => 'Purchase Time Frame',
					'description' => 'This field can be included to gauge interest of webinar attendees towards buying your product or service.<br>Purchasing Time Frame:<br>`Within a month`<br>`1-3 months`<br>`4-6 months`<br>`More than 6 months`<br>`No timeframe`',
					'required'	  => false,
				),
			'role_in_purchase_process' => array(
					'type' 	  	  => 'select',
					'name'		  => 'Role in Purchase',
					'description' => 'Role in Purchase Process:<br>`Decision Maker`<br>`Evaluator/Recommender`<br>`Influencer`<br>`Not involved` ',
					'required'	  => false,
				),
			'no_of_employees'		   => array(
					'type' 		  => 'select',
					'name'		  => 'Number of Employees',
					'description' => 'Number of Employees:<br>`1-20`<br>`21-50`<br>`51-100`<br>`101-500`<br>`500-1,000`<br>`1,001-5,000`<br>`5,001-10,000`<br>`More than 10,000`',
					'required'	  => false,
				),
			'comments' 				   => array(
					'type' 		  => 'text',
					'name'		  => 'Comments',
					'description' => 'A field that allows registrants to provide any questions or comments that they might have.',
					'required'	  => false,
				),
		);

		return $fields;

	}

}