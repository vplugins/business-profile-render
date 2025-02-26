<?php
namespace BusinessProfileRender\Blocks;

/**
 * Class GutenbergBlock
 */
class GutenbergBlock {
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_block' ) );
    }

    public static function register_block() {

        // Fetch JSON data
        $business_profile_data = get_option('bpr_business_profile');

        // Check if $business_profile_data is not empty
        if (!empty($business_profile_data)) {
            // Register the block script
            wp_register_script(
                'business-profile-render-gutenberg-block',
                plugin_dir_url(__FILE__) . '../../build/gutenberg-block.js',
                array('wp-blocks', 'wp-element', 'wp-components'),
                filemtime(plugin_dir_path(__FILE__) . '../../build/gutenberg-block.js'),
                true
            );

            // Localize the block script with preprocessed data
            wp_localize_script(
                'business-profile-render-gutenberg-block',
                'businessProfileData',
                self::preprocess_business_profile_data($business_profile_data)
            );

            // Register the block type
            register_block_type('business-profile-render/bpr-block', array(
                'editor_script' => 'business-profile-render-gutenberg-block'
            ));
        }


    }

    // Function to preprocess business profile data
    public static function preprocess_business_profile_data($data) {
        $processed_data = array();

        foreach ($data as $key => $value) {
            // Capitalize each word in the key
            $processed_key = ucwords(str_replace('_', ' ', $key));

            // Handle different value types properly
            if ($value === '' || $value === null) {
                $processed_value = __("No data available", 'business-profile-render');
            } elseif (is_array($value)) {
                $processed_value = implode(', ', array_map('sanitize_text_field', $value));
            } else {
                $processed_value = sanitize_text_field((string) $value);
            }

            // Store processed key-value pair
            $processed_data[$processed_key] = $processed_value;
        }

        return $processed_data;
    }
        
}

GutenbergBlock::init();