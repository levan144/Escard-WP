<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     WooCommerce TBC Credit Card Payment Gateway
 * @author      Plug and Pay Ltd. https://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       2.0.8
 * @license     https://plugandpay.ge/eula
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TBC plugin factory class.
 */
class PlugandPay_WC_TBC_Credit_Card_Plugin_Factory {

	/**
	 * __FILE__ from the root plugin file.
	 *
	 * @since 2.0.8
	 * @var string
	 */
	public $file;

	/**
	 * The current version of the plugin.
	 *
	 * @since 2.0.8
	 * @var string
	 */
	public $version;

	/**
	 * Extras / misc.
	 *
	 * @since 2.0.8
	 * @var \PlugandPay_WC_TBC_Credit_Card_Extras
	 */
	public $extras;

	/**
	 * Background tasks.
	 *
	 * @since 4.1.0
	 * @var \PlugandPay_WC_TBC_Credit_Card_Background_Tasks
	 */
	public $background_tasks;

	/**
	 * Order Actions.
	 *
	 * @since 4.0.0
	 * @var \PlugandPay_WC_TBC_Checkout_Order_Actions
	 */
	public $order_actions;

	/**
	 * Holds a single instance of this class.
	 *
	 * @since 2.0.8
	 * @var \PlugandPay_WC_TBC_Credit_Card_Plugin_Factory|null
	 */
	protected static $_instance = null;

	/**
	 * Returns a single instance of this class.
	 *
	 * @since 2.0.8
	 * @param string $file Must be __FILE__ from the root plugin file.
	 * @param string $software_version Current software version of this plugin.
	 * @return \PlugandPay_WC_TBC_Credit_Card_Plugin_Factory|null
	 */
	public static function instance( $file, $software_version ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $software_version );
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 2.0.8
	 * @param string $file Must be __FILE__ from the root plugin file.
	 * @param string $software_version Current software version of this plugin.
	 */
	public function __construct( $file, $software_version ) {
		$this->file    = $file;
		$this->version = $software_version;

		$this->init_dependencies();

		add_action( 'woocommerce_api_is_plugandpay', [ $this, 'list_installed_plugins' ] );
		add_filter( 'plugandpay_installed_plugins', [ $this, 'add_to_installed_plugins' ] );
		add_filter( 'woocommerce_payment_gateways', [ $this, 'register_payment_gateway' ] );
		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	/**
	 * Create the list of installed (Plug and Pay) plugins.
	 *
	 * @since 2.0.8
	 */
	public function list_installed_plugins() {
		$array = apply_filters( 'plugandpay_installed_plugins', [] );
		header( 'Content-Type: application/json' );
		echo json_encode( $array, JSON_PRETTY_PRINT );
		exit;
	}

	/**
	 * Add this plugin to the list of installed (Plug and Pay) plugins.
	 *
	 * @since 2.0.8
	 * @param array $list Plugandpay installed plugins list.
	 * @return array
	 */
	public function add_to_installed_plugins( $list ) {
		$list['tbc_credit_card_gateway'] = $this->whoami();
		return $list;
	}

	/**
	 * Diagnostic information about this plugin.
	 *
	 * @since 2.0.8
	 * @return array
	 */
	public function whoami() {
		return [
			'version' => $this->version,
		];
	}

	/**
	 * Init plugin dependencies.
	 *
	 * @since 2.0.8
	 */
	public function init_dependencies() {

		/**
		 * Plug and Pay api integration.
		 *
		 * @since 2.0.8
		 * @param string $file             Must be __FILE__ from the root plugin file, or theme functions file.
		 * @param string $software_title   Must be exactly the same as the Software Title in the product.
		 * @param string $software_version This product's current software version.
		 * @param string $plugin_or_theme  'plugin' or 'theme'.
		 * @param string $api_url          The URL to the site that is running the API Manager.
		 */
		PlugandPay_WC_TBC_Credit_Card_API_Menu::instance( $this->file, 'WooCommerce TBC Credit Card Payment Gateway', $this->version, 'plugin', 'https://plugandpay.ge/' );

		/**
		 * Extras / misc.
		 *
		 * @since 2.0.8
		 * @param string $file Must be __FILE__ from the root plugin file.
		 */
		$this->extras = new PlugandPay_WC_TBC_Credit_Card_Extras( $this->file );

		/**
		 * Background tasks.
		 *
		 * @since 4.1.0
		 * @param string $file Must be __FILE__ from the root plugin file.
		 */
		$this->background_tasks = new PlugandPay_WC_TBC_Credit_Card_Background_Tasks( $this->file );

		/**
		 * Order Actions.
		 *
		 * @since 4.0.0
		 * @param string $software_version Current software version of this plugin.
		 */
		$this->order_actions = new PlugandPay_WC_TBC_Checkout_Order_Actions( $this->version );

	}

	/**
	 * Register the payment gateway.
	 *
	 * @since 2.0.8
	 * @param array $gateways Payment gateways.
	 */
	public function register_payment_gateway( $gateways ) {
		$gateways[] = 'PlugandPay_WC_TBC_UFC_Gateway';
		$gateways[] = new PlugandPay_WC_TBC_Checkout_Gateway( $this->version );
		return $gateways;
	}

	/**
	 * Load textdomain.
	 *
	 * @since 2.0.8
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'tbc-gateway', false, dirname( plugin_basename( $this->file ) ) . '/languages' );
	}

}

