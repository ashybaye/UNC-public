<?php
/**
 * Plugin Name: UNC Gutenberg Blocks
 * Plugin URI: https://visionpointmarketing.com
 * Description: unc-blocks — is a Gutenberg plugin created via create-guten-block.
 * Author: VisionPoint
 * Author URI: https://visionpointmarketing.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
