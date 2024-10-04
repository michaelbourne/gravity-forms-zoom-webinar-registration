<?php
/**
 * Register attendees in your Zoom Webinar through a Gravity Form
 *
 * @package   Gravity Forms Zoom Webinar Registration
 * @author    Michael Bourne
 * @license   GPL3
 * @link      https://5forests.com
 * @since     1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Gravity Forms Zoom Webinar Registration
 * Description: Register attendees in your Zoom Webinar through a Gravity Form
 * Version: 1.3.0
 * Author: Michael Bourne
 * Author URI: https://5forests.com
 * Requires at least: 5.4
 * Tested up to: 6.6.2
 * Stable tag: 1.3.0
 * Requires PHP: 8.0
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain: gravity-zwr
 * Domain Path: /languages
 *
 * Created Date: Friday March 25th 2020
 * Author: Michael Bourne
 * -----
 * Last Modified: Friday, October 4th 2024, 2:04:31 pm
 * Modified By: Michael Bourne
 * -----
 * Copyright (C) 2020-2024 Michael Bourne
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

defined( 'GRAVITYZWR_ROOT' ) || define( 'GRAVITYZWR_ROOT', plugin_dir_path( __FILE__ ) );
defined( 'GRAVITYZWR_URI' ) || define( 'GRAVITYZWR_URI', plugin_dir_url( __FILE__ ) );
defined( 'GRAVITYZWR_VERSION' ) || define( 'GRAVITYZWR_VERSION', '1.3.0' );
defined( 'GRAVITYZWR_ZOOMAPIURL' ) || define( 'GRAVITYZWR_ZOOMAPIURL', 'https://api.zoom.us/v2' );

add_action( 'gform_loaded', array( 'GravityZWR_Bootstrap', 'load' ), 5 );

/**
 * GravityZWR_Bootstrap Class
 */
class GravityZWR_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
            return;
        }

        // Load API Helper classes.
        require_once GRAVITYZWR_ROOT . 'includes/class-gravityzwr-wordpressremote.php';
        require_once GRAVITYZWR_ROOT . 'includes/class-gravityzwr-zoomapi.php';

        // Load main plugin class.
        require_once GRAVITYZWR_ROOT . 'includes/class-gravityzwr.php';

        GFAddOn::register( 'GravityZWR' );
    }

}
