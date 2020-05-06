<?php

namespace BusinessProfileRender;

defined( 'ABSPATH' ) || exit;
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'class-data-storage.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-company-name.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-address.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-city.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-state.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-country.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-toll-free-number.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-contact-email.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-foursquare.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-twitter.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-instagram.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-linkedin.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-facebook.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-pinterest.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-rss.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-youtube.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-primaryimage.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-logoimage.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-worknumber.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-services.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-hoursofoperation.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-company-description.php' );
require_once( BUSINESS_PROFILE_RENDER_INCLUDE_PATH . 'profile-fields/class-company-short-description.php' );

/**
 * Class Controller
 *
 * Create and register necessary components
 */
class Controller {

	const ACTIVATION_OPTION_KEY = "business_profile_render_version";

	/**
	 * @var Controller the instance of this class
	 */
	private static $singleton = null;

	/**
	 * @var DataStorage provides the interface to the data storage
	 */
	private $storage = null;

	/**
	 * @var array|ProfileField[]
	 */
	private $profile_fields;

	/**
	 * @var bool
	 */
	private $registered;

	/**
	 * Controller constructor.
	 */
	public function __construct() {
		$this->storage        = DataStorage::instance();
		$this->registered     = false;
		$this->profile_fields = array(
			new CompanyName( $this->storage ),
			new Address( $this->storage ),
			new City( $this->storage ),
			new State( $this->storage ),
			new Country( $this->storage ),
			new TollFreeNumber( $this->storage ),
			new CompanyDescription( $this->storage ),
			new CompanyShortDescription( $this->storage ),
			new ContactEmail( $this->storage ),
			new Foursquare( $this->storage ),
			new Twitter( $this->storage ),
			new Instagram( $this->storage ),
			new LinkedIn( $this->storage ),
			new Pinterest( $this->storage ),
			new Facebook( $this->storage ),
			new Rss( $this->storage ),
			new YouTube( $this->storage ),
			new PrimaryImage( $this->storage ),
			new LogoImage( $this->storage ),
			new WorkNumber( $this->storage ),
			new Services( $this->storage ),
			new HoursOfOperation( $this->storage ),
			// TODO: add all the other business data classes and put them here
		);
	}

	/**
	 * return the instance of this class
	 *
	 * @return Controller
	 */
	public static function instance() {
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self();
		}

		return self::$singleton;
	}

	/**
	 * Run on activation - save the name/version to the settings
	 */
	public function activate() {
		update_option( static::ACTIVATION_OPTION_KEY, BUSINESS_PROFILE_RENDER_VERSION );
	}

	/**
	 * register the activation and deactivation hook with WordPress
	 */
	public function register_hooks() {
		if ( ! $this->registered ) {
			register_activation_hook( BUSINESS_PROFILE_RENDER_FILE, array( $this, 'activate' ) );
			register_deactivation_hook( BUSINESS_PROFILE_RENDER_FILE, array( $this, 'deactivate' ) );
			$this->registered = true;
		}
	}

	/**
	 * Run on deactivate - remove the name/version to the settings
	 */
	public function deactivate() {
		delete_option( static::ACTIVATION_OPTION_KEY );
	}
}
