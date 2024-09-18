<?php
namespace BusinessProfileRender\Admin;

/**
 * Class AdminNotice
 */
class AdminNotice {
    public static function init() {
        add_action('admin_init', array(__CLASS__, 'check_business_profile_option'));
        add_action('wp_ajax_generate_business_profile_data', array(__CLASS__, 'handle_generate_business_profile_data'));
    }

    // Method to check if bpr_business_profile option exists
    public static function check_business_profile_option() {
        // Check if we are on the admin side
        if (is_admin()) {
            // Check if the bpr_business_profile option exists
            if (!get_option('bpr_business_profile')) {
                // If option doesn't exist, display notice
                add_action('admin_notices', array(__CLASS__, 'display_contact_notice'));
            }
        }
    }

    public static function generate_business_profile_data() {
        // Example logic to generate business profile data from website settings
        $site_name = get_option('blogname');
        $site_description = get_option('blogdescription');
        $admin_email = get_option('admin_email');

        $generated_data = array(
            'name' => $site_name,
            'description' => $site_description,
            'email' => $admin_email,
            // Add other relevant settings as needed
        );

        update_option('bpr_business_profile', $generated_data);
    }

    // Method to display notice if option doesn't exist
    public static function display_contact_notice() {
        ?>
        <div class="notice notice-error">
            <p><strong>Business Profile Data Missing:</strong> It looks like your Business Profile Data is missing after the recent migration. If you need assistance, please contact the Website Pro Team.</p>
            <p><button id="generate-business-profile-data" class="button button-primary">Generate Data</button></p>
        </div>
        <script type="text/javascript">
        document.getElementById('generate-business-profile-data').addEventListener('click', function() {
            // Make an AJAX request to generate the data
            jQuery.post(ajaxurl, {action: 'generate_business_profile_data'}, function(response) {
                location.reload();
            });
        });
        </script>
        <?php
    }

    public static function handle_generate_business_profile_data() {
        self::generate_business_profile_data();
        wp_send_json_success();
    }
}

AdminNotice::init();
