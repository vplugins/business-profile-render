<?php
/*
Plugin Name: Business Profile Render
Description: Tool to provide utilities for displaying synchronized business profile data
Version: 1.5.0
Author: Website Pro Team
License: GPL v2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'BUSINESS_PROFILE_RENDER_OPTION', 'bpr_business_profile' );
define( 'BUSINESS_PROFILE_RENDER_DEFAULT_OPTION', 'company_name' );

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php'; 

// Include necessary files
require_once plugin_dir_path( __FILE__ ) . 'src/Admin/AdminNotice.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Deprecated/Deprecated.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Blocks/GutenbergBlock.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Shortcode/Shortcode.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Helper/Page.php';