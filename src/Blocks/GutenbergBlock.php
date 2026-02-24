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
                // Handle nested arrays properly
                $processed_value = self::format_array_value($key, $value);
            } else {
                $processed_value = sanitize_text_field((string) $value);
            }

            // Store processed key-value pair
            $processed_data[$processed_key] = $processed_value;
        }

        return $processed_data;
    }

    private static function format_array_value($key, $value) {
        // Special handling for hours_of_operation
        if ($key === 'hours_of_operation') {
            return self::format_hours_of_operation($value);
        }

        // Special handling for work_number (array with single value)
        if ($key === 'work_number' && is_array($value) && count($value) === 1) {
            return sanitize_text_field($value[0]);
        }

        // Generic array handling
        $result = array();

        foreach ($value as $item_key => $item) {
            if (is_array($item)) {
                // Skip complex nested structures for generic display
                continue;
            } elseif (is_string($item)) {
                $result[] = sanitize_text_field($item);
            }
        }

        return !empty($result) ? implode(', ', $result) : __("Complex data - use shortcode for display", 'business-profile-render');
    }

    private static function format_hours_of_operation($hours) {
        if (!is_array($hours)) {
            return sanitize_text_field((string) $hours);
        }

        $result = array();

        foreach ($hours as $time_block) {
            if (!is_array($time_block)) {
                $result[] = sanitize_text_field($time_block);
                continue;
            }

            $days = isset($time_block['day_of_week']) ? $time_block['day_of_week'] : [];
            $opens = isset($time_block['opens']) ? sanitize_text_field($time_block['opens']) : '';
            $closes = isset($time_block['closes']) ? sanitize_text_field($time_block['closes']) : '';

            if (is_array($days) && !empty($days)) {
                $days_text = implode(', ', array_map(array(__CLASS__, 'translate_day_name'), $days));
            } else {
                continue;
            }

            if ($opens && $closes) {
                $entry = "{$days_text}: {$opens} - {$closes}";
                if (!empty($time_block['description'])) {
                    $entry .= " (" . self::translate_description($time_block['description']) . ")";
                }
                $result[] = $entry;
            } elseif (!empty($time_block['description'])) {
                $result[] = "{$days_text}: " . self::translate_description($time_block['description']);
            }
        }

        return !empty($result) ? implode(' | ', $result) : __("No hours available", 'business-profile-render');
    }

    private static function translate_description($description) {
        $map = [
            'Closed'        => __('Closed', 'business-profile-render'),
            'Open 24 hours' => __('Open 24 hours', 'business-profile-render'),
        ];
        return isset($map[$description]) ? sanitize_text_field($map[$description]) : sanitize_text_field($description);
    }

    private static function translate_day_name($day) {
        global $wp_locale;
        $day_index = [
            'Sunday'    => 0,
            'Monday'    => 1,
            'Tuesday'   => 2,
            'Wednesday' => 3,
            'Thursday'  => 4,
            'Friday'    => 5,
            'Saturday'  => 6,
        ];
        if (isset($day_index[$day])) {
            return sanitize_text_field($wp_locale->get_weekday($day_index[$day]));
        }
        return sanitize_text_field($day);
    }

}

GutenbergBlock::init();