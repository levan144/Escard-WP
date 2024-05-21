<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     WooCommerce TBC Credit Card Payment Gateway
 * @author      Plug and Pay Ltd. https://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       2.0.1
 * @license     https://plugandpay.ge/eula
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeAreDe\TbcPay\TbcPayProcessor;

/**
 * TBC (UFC) credit card payment gateway class.
 */
class PlugandPay_WC_TBC_UFC_Gateway extends WC_Payment_Gateway {

	/**
	 * Whether or not logging is enabled.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public static $log_enabled = false;

	/**
	 * Logger instance.
	 *
	 * @since 1.0.0
	 * @var WC_Logger
	 */
	public static $log = false;

	/**
	 * Supported currencies.
	 * alpha => iso4217.
	 *
	 * @since 2.3.0
	 * @var array
	 */
	public $supported_currencies = [
		'USD' => '840',
		'GEL' => '981',
		'EUR' => '978',
	];

	/**
	 * Supported languages.
	 * locale => api lang.
	 *
	 * @since 2.3.0
	 * @var array
	 */
	public $supported_languages = [
		'ka_GE' => 'GE',
		'en_US' => 'EN',
	];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id                 = 'tbc_credit_card_gateway';
		$this->has_fields         = false;
		$this->method_title       = __( 'TBC (UFC)', 'tbc-gateway' );
		$this->method_description = __( 'Accept Visa/Mastercard payments in your WooCommerce shop using TBC (UFC) gateway.', 'tbc-gateway' );
		$this->supports           = [
			'products',
			'refunds',
		];

		// Load the settings.
		$this->form_fields = include 'settings/ufc-gateway.php';
		$this->init_settings();

		// Define user set variables.
		$this->title                   = $this->get_option( 'title' );
		$this->description             = $this->get_option( 'description' );
		$this->order_button_text       = $this->get_option( 'order_button_text' );
		$this->debug                   = 'yes' === $this->get_option( 'debug', 'no' );
		self::$log_enabled             = $this->debug;
		$this->ertguli_points          = 'yes' === $this->get_option( 'ertguli_points', 'no' );
		$this->ok_slug                 = $this->get_option( 'ok_slug' );
		$this->fail_slug               = $this->get_option( 'fail_slug' );
		$this->payment_form_language   = $this->get_option( 'payment_form_language' );
		$this->close_business_day_cron = $this->get_option( 'close_business_day_cron' );
		$this->payment_action          = $this->get_option( 'payment_action' );
		$this->save_card               = 'yes' === $this->get_option( 'save_card', 'no' );
		$this->save_card_label         = $this->get_option( 'save_card_label' );
		$this->merchant_accounts       = get_option(
			'woocommerce_' . $this->id . '_merchant_accounts',
			[
				[
					'merchant_currency'  => $this->get_option( 'merchant_currency' ),
					'merchant_cert_path' => $this->get_option( 'merchant_cert_path' ),
					'merchant_cert_pass' => $this->get_option( 'merchant_cert_pass' ),
				],
			]
		);

		if ( $this->save_card ) {
			$this->supports = array_merge(
				$this->supports,
				[
					'tokenization',
					'subscriptions',
					'subscription_cancellation',
					'subscription_suspension',
					'subscription_reactivation',
					'subscription_amount_changes',
					'subscription_date_changes',
					'multiple_subscriptions',
				]
			);
		}

		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
		add_action( 'woocommerce_admin_order_data_after_order_details', [ $this, 'order_details' ] );
		add_action( 'woocommerce_api_redirect_to_payment_form', [ $this, 'redirect_to_payment_form' ] );
		add_action( 'woocommerce_api_' . $this->ok_slug, [ $this, 'return_from_payment_form_ok' ] );
		add_action( 'woocommerce_api_' . $this->fail_slug, [ $this, 'return_from_payment_form_fail' ] );
		add_action( 'woocommerce_api_close_business_day', [ $this, 'close_business_day' ] );
		add_action( 'woocommerce_api_check_trans_status', [ $this, 'check_trans_status' ] );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'save_merchant_accounts' ] );
		add_action( 'woocommerce_order_status_on-hold_to_processing', [ $this, 'capture_authorized_payment' ] );
		add_action( 'woocommerce_order_status_on-hold_to_completed', [ $this, 'capture_authorized_payment' ] );
		add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, [ $this, 'process_subscription_payment' ], 10, 2 );
	}

	/**
	 * Logging method.
	 *
	 * @since 1.0.0
	 * @param string $message Log message.
	 * @param string $level Optional. Default 'info'. Possible values:
	 *                      emergency|alert|critical|error|warning|notice|info|debug.
	 */
	public static function log( $message, $level = 'info' ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->log( $level, $message, [ 'source' => 'tbc_credit_card_gateway' ] );
		}
	}

	/**
	 * Display notices in admin dashboard
	 *
	 * Check if required parameters: cert_path and cert_pass are set.
	 * Display errors notice if they are missing,
	 * both of these parameters are required for correct functioning of the plugin.
	 * Check happens only when plugin is enabled not to clutter admin interface.
	 *
	 * @return null|void
	 */
	public function admin_notices() {
		if ( 'no' === $this->enabled ) {
			return;
		}

		if ( ! $this->merchant_accounts ) {
			/* translators: %s: plugin settings page url  */
			echo '<div class="error"><p>' . wp_kses_data( sprintf( __( 'WooCommerce TBC Credit Card Payment Gateway (UFC): please add at least one merchant account <a href="%s">here</a>.', 'tbc-gateway' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . $this->id ) ) ) . '</p></div>';
		}

		if ( $this->merchant_accounts ) {
			$is_empty = false;
			foreach ( $this->merchant_accounts as $account ) {
				if ( empty( $account['merchant_currency'] ) || empty( $account['merchant_cert_path'] ) || empty( $account['merchant_cert_pass'] ) ) {
					$is_empty = true;
				}
			}
			if ( $is_empty ) {
				/* translators: %s: plugin settings page url  */
				echo '<div class="error"><p>' . wp_kses_data( sprintf( __( 'WooCommerce TBC Credit Card Payment Gateway (UFC): all merchant account fields are required! please fix it <a href="%s">here</a>.', 'tbc-gateway' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . $this->id ) ) ) . '</p></div>';
			}
		}
	}

	/**
	 * Output the gateway settings screen.
	 *
	 * @since 2.1.0
	 * @return void
	 */
	public function admin_options() {
		echo '<h2>' . esc_html( $this->get_method_title() );
		wc_back_link( __( 'Return to payments', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
		echo '</h2>';
		echo wp_kses_post( wpautop( $this->get_method_description() ) );
		WC_Settings_API::admin_options();
		?>
		<div style="border: 1px dotted #ccc; padding-left: 15px;">
			<p><strong><?php esc_html_e( 'Autogenerated pages', 'tbc-gateway' ); ?></strong></p>
			<p><?php esc_html_e( 'These pages are automatically created by plugin!', 'tbc-gateway' ); ?></p>
			<ul>
				<li><?php echo esc_url( sprintf( '%s/wc-api/%s', get_bloginfo( 'url' ), $this->ok_slug ) ); ?></li>
				<li><?php echo esc_url( sprintf( '%s/wc-api/%s', get_bloginfo( 'url' ), $this->fail_slug ) ); ?></li>
			</ul>
			<p style="color:orange;"><?php echo is_ssl() ? '' : esc_html__( 'https (SSL) is recommended! Please install a certificate.', 'tbc-gateway' ); ?></p>
			<hr/>
			<p><strong><?php esc_html_e( 'Shop IP', 'tbc-gateway' ); ?></strong>: <?php echo esc_html( $this->what_is_my_ip() ); ?> <i style="color:#b1b1b1;"><?php esc_html_e( 'Result is cached for an hour', 'tbc-gateway' ); ?>.</i></p>
		</div>
		<?php
	}

	/**
	 * Is this gateway available?
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_available() {
		return parent::is_available() && $this->is_activated();
	}

	/**
	 * Is this plugin activated?
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_activated() {
		return get_option( 'woocommerce_tbc_credit_card_payment_gateway_activated' ) === 'Activated';
	}

	/**
	 * Convert currency alphabetic code to a numeric.
	 * e.g. USD -> 840.
	 *
	 * @since 2.3.0
	 * @param string $alphabetic Alphabetic currency code.
	 * @return string
	 */
	public function iso4217_alpha_to_num( $alphabetic ) {
		return $this->supported_currencies[ $alphabetic ] ?? '';
	}

	/**
	 * Convert locale to API lang designation.
	 * e.g. ka_GE -> GE.
	 *
	 * @since 2.3.0
	 * @param string $locale Locale such as en_US.
	 * @return string
	 */
	public function locale_to_lang( $locale ) {
		return $this->supported_languages[ $locale ] ?? 'EN';
	}

	/**
	 * Process a refund if supported.
	 *
	 * @since 2.0.0
	 * @param  int    $order_id Order ID.
	 * @param  float  $amount Refund amount.
	 * @param  string $reason Refund reason.
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || ! $order->get_transaction_id() ) {
			$this->log( 'Refund not possible, order or transaction id missing.', 'error' );
			return new WP_Error( 'error', __( 'Refund failed.', 'tbc-gateway' ) );
		}

		$account = $order->get_meta( '_merchant_account' );
		$tbc     = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $order->get_customer_ip_address() );

		$this->log(
			sprintf(
				'Start refund, Order id: %s - amount: %s - reason: %s',
				$order->get_id(),
				$amount,
				$reason ?: 'none given'
			),
			'info'
		);

		$refund = $tbc->refund_transaction( $order->get_transaction_id(), $amount * 100 );

		/* translators: %s: Refund transaction id */
		$order->add_order_note( sprintf( __( 'Refund attempt transaction id: %s', 'tbc-gateway' ), $refund['REFUND_TRANS_ID'] ?? '' ) );

		if ( 'OK' !== $refund['RESULT'] ) {

			$this->log(
				sprintf(
					'Refund failed, transaction_id: %s, merchant: %s, response: %s',
					$order->get_transaction_id(),
					$account['merchant_currency'],
					wp_json_encode( $refund, JSON_PRETTY_PRINT )
				),
				'error'
			);

			return new WP_Error( 'error', __( 'Refund failed.', 'tbc-gateway' ) );
		}

		$this->log( 'Success ~ refund done.', 'info' );
		return true;
	}

	/**
	 * Process the payment and redirect client.
	 *
	 * @since 1.0.0
	 * @param  int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order       = wc_get_order( $order_id );
		$account     = $this->get_merchant_account_by_currency( $order->get_currency() );
		$redirect_to = 'redirect_to_payment_form';

		if ( ! $account ) {
			return;
		}

		$tbc              = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $order->get_customer_ip_address() );
		$tbc->amount      = $order->get_total() * 100;
		$tbc->currency    = $this->iso4217_alpha_to_num( $account['merchant_currency'] );
		$tbc->description = 'Order id: ' . $order->get_id();
		$tbc->language    = $this->payment_form_language ?: $this->locale_to_lang( get_locale() );
		$tbc->biller      = 'Order id: ' . $order->get_id();

		if ( $this->ertguli_points ) {
			$tbc->charge_ertguli_points = (bool) $_POST[ "wc-$this->id-pay-with-ertguli" ] ?? false; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		$this->log(
			sprintf(
				'Order id: %s - amount: %s %s, language: %s',
				$order->get_id(),
				$tbc->amount / 100,
				$tbc->currency,
				$tbc->language
			),
			'info'
		);

		$new_payment_method = (bool) $_POST[ "wc-$this->id-new-payment-method" ] ?? false; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$payment_token      = $_POST[ "wc-$this->id-payment-token" ] ?? false; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( true === $new_payment_method || 'new' === $payment_token ) {

			$this->log( 'Capture and save...', 'info' );
			$this->payment_action = 'capture_and_save';

			$raw_token = $this->generate_token();
			$start     = $tbc->capture_and_save( $raw_token, '12' . date( 'y', strtotime( '+5 years' ) ) );

			$order->add_meta_data( '_save_card_raw_token', $raw_token, true );

		} elseif ( $payment_token && 'new' !== $payment_token ) {

			$this->log( 'Capture a saved card...', 'info' );
			$this->payment_action = 'capture';
			$redirect_to          = $this->ok_slug;

			$token_id = wc_clean( $payment_token );
			$token    = WC_Payment_Tokens::get( $token_id );

			if ( $token->get_user_id() !== get_current_user_id() ) {
				$this->log( 'Token user ID does not match the current user... bail out of payment processing.', 'error' );
				return;
			}

			$start = $tbc->capture_saved( $token->get_token() );

			if ( isset( $start['RESULT'] ) && 'OK' === $start['RESULT'] ) {
				$this->log( 'Success ~ saved card charged!', 'info' );
			} else {
				$this->log( 'Charging a saved card failed', 'error' );
			}
		} else {

			switch ( $this->payment_action ) {
				case 'capture':
					$start = $tbc->sms_start_transaction();
					break;

				case 'authorize':
					$start = $tbc->dms_start_authorization();
					break;
			}
		}

		$trans_id = $start['TRANSACTION_ID'] ?? null;

		$order->set_transaction_id( $trans_id );
		$order->add_meta_data( '_merchant_account', $account, true );
		$order->add_meta_data( '_payment_action', $this->payment_action, true );
		$order->save();

		if ( isset( $start['error'] ) || ! $trans_id ) {

			$error_msg = $start['error'] ?? 'no connection to TBC.';
			$this->log(
				sprintf(
					'Order id: %s - Error msg: %s',
					$order->get_id(),
					$error_msg
				),
				'error'
			);

			return;
		}

		$this->log(
			sprintf(
				'Success ~ Order id: %s -> transaction id: %s obtained successfully, payment action: %s, redirecting...',
				$order->get_id(),
				$trans_id,
				$this->payment_action
			),
			'info'
		);

		return [
			'result'   => 'success',
			'redirect' => sprintf( '%s/wc-api/%s?trans_id=%s', get_bloginfo( 'url' ), $redirect_to, rawurlencode( $trans_id ) ),
		];
	}

	/**
	 * Process the subscription payment.
	 *
	 * @since 2.5.0
	 * @param float    $amount Amount to charge.
	 * @param WC_Order $order Renewal order.
	 * @return bool
	 */
	public function process_subscription_payment( $amount, $order ) {
		$this->log( 'Process subscription, order id: ' . $order->get_id(), 'info' );

		$tokens = WC_Payment_Tokens::get_customer_tokens( $order->get_customer_id(), $this->id );

		if ( ! $tokens ) {
			$order->add_order_note( __( 'No tokens found for this customer.', 'tbc-gateway' ) );
			return false;
		}

		$default_token = null;

		foreach ( $tokens as $token ) {
			if ( $token->get_is_default() ) {
				$default_token = $token;
			}
		}

		if ( ! $default_token ) {
			$order->add_order_note( __( 'No default token found for this customer.', 'tbc-gateway' ) );
			return false;
		}

		// TODO: Maybe add account to token itself while saving it.
		// So we have 100% guaranteed account associated with saved token.
		// Still I need to pay attention to diff currencies and possible different merchant accounts in settings.
		$account = $this->get_merchant_account_by_currency( $order->get_currency() );

		$tbc              = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $order->get_customer_ip_address() );
		$tbc->amount      = $amount * 100;
		$tbc->currency    = $this->iso4217_alpha_to_num( $account['merchant_currency'] );
		$tbc->description = 'Order id: ' . $order->get_id();
		$tbc->biller      = 'Order id: ' . $order->get_id();

		$this->log( 'Capture a saved card...', 'info' );

		$start    = $tbc->capture_saved( $default_token->get_token() );
		$trans_id = $start['TRANSACTION_ID'] ?? null;

		$order->set_transaction_id( $trans_id );
		$order->add_meta_data( '_merchant_account', $account, true );
		$order->add_meta_data( '_payment_action', 'capture', true );

		if ( isset( $start['RESULT'] ) && 'OK' === $start['RESULT'] ) {
			$order->payment_complete();
			$message = __( 'TBC capture saved card complete!', 'tbc-gateway' );
			$order->add_order_note( $message );
			$order->add_meta_data( '_tbc_status', 'captured', true );
		} else {
			$message = __( 'TBC capture saved card failed!', 'tbc-gateway' );
			$order->update_status( 'failed', $message );
			$this->log( 'Charging a saved card failed Response: ' . wp_json_encode( $start ), 'error' );
		}

		$order->save();

		$this->log( sprintf( ' %s transaction id: %s, order id: %s', $message, $trans_id, $order->get_id() ) );
	}

	/**
	 * OK endpoint.
	 * Landing page for customers returning from TBC after payment.
	 * Here we verify that transaction was indeed successful
	 * and update order status accordingly.
	 *
	 * @since 1.0.0
	 */
	public function return_from_payment_form_ok() {

		$this->log( 'Icomming from TBC', 'notice' );

		try {
			if ( ! isset( $_REQUEST['trans_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->log( 'TBC did not return trans_id in $_REQUEST on OK page', 'critical' );
				throw new Exception( __( 'Something went wrong verifying transaction, please contact website administration.', 'tbc-gateway' ) );
			}

			$trans_id = rawurldecode( $_REQUEST['trans_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order_id = $this->get_order_id_by_transaction_id( $trans_id );

			if ( ! $order_id ) {
				$this->log( 'Could not find order id associated with transaction id: ' . $trans_id, 'critical' );
				throw new Exception( __( 'Something went wrong verifying transaction, please contact website administration.', 'tbc-gateway' ) );
			}

			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				$this->log( 'Could not find order associated with order id: ' . $order_id, 'critical' );
				throw new Exception( __( 'Something went wrong verifying transaction, please contact website administration.', 'tbc-gateway' ) );
			}

			$account = $order->get_meta( '_merchant_account' );

			if ( ! $account ) {
				$this->log( 'Merchant account not found! order id: ' . $order_id, 'critical' );
				throw new Exception( __( 'Something went wrong verifying transaction, please contact website administration.', 'tbc-gateway' ) );
			}
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
			wp_safe_redirect( wc_get_page_permalink( 'checkout' ) );
			exit;
		}

		try {
			$this->log( sprintf( 'Checking transaction status for order id: %s, merchant: %s', $order_id, $account['merchant_currency'] ) );

			$tbc      = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $order->get_customer_ip_address() );
			$response = $tbc->get_transaction_result( $trans_id );

			if ( ! isset( $response['RESULT'] ) || 'OK' !== $response['RESULT'] ) {
				$this->log( 'TBC did not return OK: ' . json_encode( $response ), 'error' );
				throw new Exception( 'Charging card failed, error code from TBC: ' . $response['RESULT_CODE'] ?? 'no code' );
			}
		} catch ( Exception $e ) {
			$order->update_status( 'failed', $e->getMessage() );
			wc_add_notice( __( 'We couldn\'t charge the card. Please try again.', 'tbc-gateway' ), 'error' );
			wp_safe_redirect( wc_get_page_permalink( 'checkout' ) );
			exit;
		}

		$complete_message = $this->set_order_status( $order, $response );

		$this->log( sprintf( ' %s transaction id: %s, order id: %s', $complete_message, $trans_id, $order_id ) );

		wp_safe_redirect( $this->get_return_url( $order ) );
		exit;
	}

	/**
	 *
	 */
	public function capture_authorized_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( $this->id === $order->get_payment_method() && 'authorized' === get_post_meta( $order->get_id(), '_tbc_status', true ) && $order->get_transaction_id() ) {

			$account = get_post_meta( $order->get_id(), '_merchant_account', true ); // TODO check if exists
			$Tbc     = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $_SERVER['REMOTE_ADDR'] );

			$Tbc->amount   = $order->get_total()  * 100;
			$Tbc->currency = $this->iso4217_alpha_to_num( $account['merchant_currency'] );

			try {
				$capture = $Tbc->dms_make_transaction( $order->get_transaction_id() );

				$this->log( sprintf( __( 'Info ~ trying to capture authorized payment, order id: %s, merchant: %s, transaction id: %s', 'tbc-gateway' ), $order->get_id(), $account['merchant_currency'], $order->get_transaction_id() ) );
				if ( ! isset($capture['RESULT']) || $capture['RESULT'] != 'OK' ) {
					$this->log( sprintf( __( 'Error ~ could not capture authorized payment, Tbc did not return OK: %s', 'tbc-gateway' ), json_encode( $capture ) ) );
					throw new Exception( __( 'We could not capture previously authorized (blocked) payment, logs should contain more information about this failure.', 'tbc-gateway' ) );
				}
			} catch ( Exception $e ) {
				// Add private note to order details
				$order_note = $e->getMessage();
				$order->update_status( 'failed', $order_note );
				// NOTE: we are not releasing stock needs to be done manually, write it in docs
				return;
			}

			$order->add_order_note( 'Tbc capture complete!' );
			$this->log( sprintf( __( 'Success ~ authorized payment captured, transaction id: %s, order id: %s', 'tbc-gateway' ), $order->get_transaction_id(), $order->get_id() ) );

			update_post_meta( $order->get_id(), '_tbc_status', 'captured' );

		}
	}

	/**
	 * Set order status for each payment action.
	 *
	 * @since 4.1.0
	 * @param WC_Order $order Order object.
	 * @param array    $response TBC response array.
	 * @return string
	 */
	public function set_order_status( $order, $response ) {

		switch ( $order->get_meta( '_payment_action' ) ) {
			case 'capture_and_save':
				$token = new WC_Payment_Token_CC();
				$token->set_token( $order->get_meta( '_save_card_raw_token' ) );
				$token->set_gateway_id( $this->id );
				$token->set_user_id( $order->get_customer_id() );
				$token->set_last4( substr( $response['CARD_NUMBER'], -4 ) );
				$token->set_expiry_month( substr( $response['RECC_PMNT_EXPIRY'], 0, 2 ) );
				$token->set_expiry_year( '20' . substr( $response['RECC_PMNT_EXPIRY'], 2, 4 ) );
				$token->set_card_type( substr( $response['CARD_NUMBER'], 0, 1 ) === '4' ? 'visa' : 'mastercard' );

				if ( $token->save() ) {
					$card_save_status = __( 'Card saved!', 'tbc-gateway' );
					$this->log( $card_save_status, 'info' );
				} else {
					$card_save_status = __( 'Card not saved!', 'tbc-gateway' );
					$this->log( $card_save_status, 'error' );
				}

				$order->payment_complete();
				$complete_message = __( 'TBC capture complete! ', 'tbc-gateway' ) . $card_save_status;
				$order->add_order_note( $complete_message );
				$order->add_meta_data( '_tbc_status', 'captured_and_saved', true );
				break;

			case 'capture':
				$order->payment_complete();
				$complete_message = __( 'TBC capture complete!', 'tbc-gateway' );
				$order->add_order_note( $complete_message );
				$order->add_meta_data( '_tbc_status', 'captured', true );
				break;

			case 'authorize':
				$complete_message = __( 'TBC authorizaion complete!', 'tbc-gateway' );
				$order->update_status( 'on-hold', $complete_message . __( ' Change payment status to processing or complete to capture funds.', 'tbc-gateway' ) );
				$order->add_meta_data( '_tbc_status', 'authorized', true );
				break;
		}

		$order->save();

		return $complete_message;
	}

	/**
	 * FAIL endpoint.
	 * Landing page for customers returning from TBC after technical failure.
	 *
	 * @since 1.0.0
	 */
	public function return_from_payment_form_fail() {
		$error = __( 'Technical failure in ECOMM system', 'tbc-gateway' );
		$this->log( $error, 'error' );
		wp_die( esc_html( $error ) );
	}

	/**
	 * Redirect user to TBC payment form.
	 *
	 * @since 1.0.0
	 */
	public function redirect_to_payment_form() {
		?>

		<html>
			<head>
				<title>TBC</title>
				<script type="text/javascript" language="javascript">
					function redirect() {
						document.returnform.submit();
					}
				</script>
			</head>

			<body onLoad="javascript:redirect()">
				<form name="returnform" action="https://ecommerce.ufc.ge/ecomm2/ClientHandler" method="POST">
					<input type="hidden" name="trans_id" value="<?php echo rawurldecode( $_GET['trans_id'] ); // phpcs:ignore WordPress.Security ?>">

					<noscript>
						<center>
							<?php esc_html_e( 'Please click the submit button below.', 'tbc-gateway' ); ?><br>
							<input type="submit" name="submit" value="Submit">
						</center>
					</noscript>
				</form>
			</body>
		</html>

		<?php
		exit;
	}

	/**
	 * Add gateway extras to (edit) order page.
	 *
	 * @since 2.0.7
	 * @param WC_Order $order Order object.
	 */
	public function order_details( $order ) {
		if ( $this->id === $order->get_payment_method() ) {
			?>

			<p class="form-field form-field-wide">
				<label><?php esc_html_e( 'TBC status', 'tbc-gateway' ); ?></label>
				<?php
					echo sprintf(
						'<a href="%s/wc-api/check_trans_status?order_id=%d&nonce=%s" target="_blank" class="button">%s</a>',
						esc_url( get_bloginfo( 'url' ) ),
						esc_attr( $order->get_id() ),
						esc_attr( wp_create_nonce( 'tbc_check_trans_status-' . $order->get_id() ) ),
						esc_html( __( 'Check', 'tbc-gateway' ) )
					);
				?>
			</p>

			<?php
		}
	}

	/**
	 * Check transaction status endpoint.
	 *
	 * @since 2.0.7
	 */
	public function check_trans_status() {
		if ( ! wp_verify_nonce( $_GET['nonce'], 'tbc_check_trans_status-' . $_GET['order_id'] ) || ! current_user_can( 'manage_woocommerce' ) ) {
			$this->log( 'Unauthorized access to route_check_status! 401', 'critical' );
			status_header( 401 );
			exit;
		}

		$order = wc_get_order( $_GET['order_id'] );

		if ( ! $order ) {
			$this->log( 'Order not found! 404', 'critical' );
			status_header( 404 );
			exit;
		}

		$account = $order->get_meta( '_merchant_account' );

		if ( ! $account ) {
			$this->log( 'Merchant account not found! 404', 'critical' );
			status_header( 404 );
			exit;
		}

		$tbc    = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $_SERVER['REMOTE_ADDR'] );
		$result = $tbc->get_transaction_result( $order->get_transaction_id() );
		$json   = wp_json_encode( $result, JSON_PRETTY_PRINT );

		echo sprintf( '<pre>%s</pre>', esc_html( $json ) );

		exit;
	}

	/**
	 * Close business day endpoint.
	 *
	 * @since 1.0.0
	 */
	public function close_business_day() {
		if ( 'server_cron' === $this->close_business_day_cron ) {

			foreach ( $this->merchant_accounts as $account ) {

				$tbc       = new TbcPayProcessor( $account['merchant_cert_path'], $account['merchant_cert_pass'], $_SERVER['REMOTE_ADDR'] );
				$close_day = $tbc->close_day();

				if ( 'OK' === $close_day['RESULT'] ) {
					$this->log( 'Business day closed via server_cron.', 'info' );
				} else {
					$this->log( sprintf( 'Close business day via server_cron failed: %s', json_encode( $close_day ) ), 'error' );
				}
			}
		}

		exit;
	}

	/**
	 * Get order id by transaction id.
	 *
	 * @since 1.0.0
	 * @param string $trans_id Transaction id.
	 * @return int|null
	 */
	public function get_order_id_by_transaction_id( $trans_id ) {
		global $wpdb;

		$meta = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT * FROM $wpdb->postmeta
				 WHERE meta_key = '_transaction_id'
				   AND meta_value = %s
				 LIMIT 1
				",
				$trans_id
			)
		);

		if ( ! empty( $meta ) && is_array( $meta ) && isset( $meta[0] ) ) {
			$meta = $meta[0];
		}

		if ( is_object( $meta ) ) {
			return $meta->post_id;
		}

		return null;
	}

	/**
	 * Get merchant account for currency.
	 *
	 * @since 2.0.0
	 * @param string $currency Alphabetic currency code.
	 * @return array|null
	 */
	public function get_merchant_account_by_currency( $currency ) {
		$currency_key = array_search( $currency, array_column( $this->merchant_accounts, 'merchant_currency' ), true );

		if ( false !== $currency_key && null !== $currency_key ) {
			$account = $this->merchant_accounts[ $currency_key ];
			$this->log( sprintf( 'Using merchant account %s', $account['merchant_currency'] ), 'info' );

			return $account;
		}

		$this->log( sprintf( 'No merchant account found!' ), 'error' );

		return null;
	}

	/**
	 * Generate merchant accounts html.
	 *
	 * @since 2.0.0
	 */
	public function generate_merchant_accounts_html() {

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php esc_html_e( 'Merchant Accounts', 'tbc-gateway' ); ?></th>
			<td class="forminp" id="merchant_accounts">
				<table class="widefat wc_input_table sortable" cellspacing="0">
					<thead>
						<tr>
							<th class="sort">&nbsp;</th>
							<th><?php esc_html_e( 'Certificate currency', 'tbc-gateway' ); ?><span class="woocommerce-help-tip" style="float:none;" data-tip="<?php esc_html_e( 'iso4217 Alpha3 currency code, e.g. EUR, GEL, USD', 'tbc-gateway' ); ?>"></span></th>
							<th><?php esc_html_e( 'Certificate path', 'tbc-gateway' ); ?><span class="woocommerce-help-tip" style="float:none;" data-tip="<?php esc_html_e( 'Absolute path to certificate in .pem format.', 'tbc-gateway' ); ?>"></span></th>
							<th><?php esc_html_e( 'Certificate passphrase', 'tbc-gateway' ); ?><span class="woocommerce-help-tip" style="float:none;" data-tip="<?php esc_html_e( 'Passphrase provided by TBC together with certificate.', 'tbc-gateway' ); ?>"></span></th>
						</tr>
					</thead>
					<tbody class="accounts">
						<?php
						$i = -1;
						if ( $this->merchant_accounts ) {
							foreach ( $this->merchant_accounts as $account ) {
								$i++;

								echo '<tr class="account">
									<td class="sort"></td>
									<td><input type="text" value="' . esc_attr( $account['merchant_currency'] ) . '" name="tbc_merchant_currencies[' . esc_attr( $i ) . ']" /></td>
									<td><input type="text" value="' . esc_attr( $account['merchant_cert_path'] ) . '" name="tbc_merchant_cert_paths[' . esc_attr( $i ) . ']" /></td>
									<td><input type="text" value="' . esc_attr( $account['merchant_cert_pass'] ) . '" name="tbc_merchant_cert_passphrases[' . esc_attr( $i ) . ']" /></td>
								</tr>';
							}
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="7"><a href="#" class="add button"><?php esc_html_e( '+ Add account', 'tbc-gateway' ); ?></a> <a href="#" class="remove_rows button"><?php esc_html_e( 'Remove selected account(s)', 'tbc-gateway' ); ?></a></th>
						</tr>
					</tfoot>
				</table>
				<script type="text/javascript">
					jQuery(function() {
						jQuery('#merchant_accounts').on( 'click', 'a.add', function(){

							var size = jQuery('#merchant_accounts').find('tbody .account').size();

							jQuery('<tr class="account">\
									<td class="sort"></td>\
									<td><input type="text" name="tbc_merchant_currencies[' + size + ']" /></td>\
									<td><input type="text" name="tbc_merchant_cert_paths[' + size + ']" /></td>\
									<td><input type="text" name="tbc_merchant_cert_passphrases[' + size + ']" /></td>\
								</tr>').appendTo('#merchant_accounts table tbody');

							return false;
						});
					});
				</script>
			</td>
		</tr>
		<?php
		return ob_get_clean();

	}

	/**
	 * Save merchant accounts table.
	 *
	 * @since 2.0.0
	 */
	public function save_merchant_accounts() {

		$accounts = [];

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verification already handled in WC_Admin_Settings::save()
		if ( isset(
			$_POST['tbc_merchant_currencies'],
			$_POST['tbc_merchant_cert_paths'],
			$_POST['tbc_merchant_cert_passphrases']
		) ) {

			$currencies  = wc_clean( wp_unslash( $_POST['tbc_merchant_currencies'] ) );
			$paths       = wc_clean( wp_unslash( $_POST['tbc_merchant_cert_paths'] ) );
			$passphrases = wc_clean( wp_unslash( $_POST['tbc_merchant_cert_passphrases'] ) );
			// phpcs:enable

			foreach ( $currencies as $i => $currency ) {
				$accounts[] = [
					'merchant_currency'  => strtoupper( $currencies[ $i ] ),
					'merchant_cert_path' => $paths[ $i ],
					'merchant_cert_pass' => $passphrases[ $i ],
				];
			}
		}

		update_option( 'woocommerce_' . $this->id . '_merchant_accounts', $accounts );
	}

	/**
	 * Determine my real ip.
	 *
	 * @since 2.1.0
	 * @return string
	 */
	public function what_is_my_ip() {
		$ip = get_transient( 'woocommerce_' . $this->id . '_external_shop_ip' );
		if ( false === $ip ) {
			$resp = wp_remote_get( 'http://ipecho.net/plain' );
			$ip   = wp_remote_retrieve_body( $resp );
			set_transient( 'woocommerce_' . $this->id . '_external_shop_ip', $ip, HOUR_IN_SECONDS );
		}
		return $ip;
	}

	/**
	 * Builds payment fields area - including tokenization fields
	 * for logged in users.
	 *
	 * @since 2.3.0
	 */
	public function payment_fields() {
		$description = $this->get_description();
		if ( $description ) {
			echo wpautop( wptexturize( $description ) ); // @codingStandardsIgnoreLine.
		}

		if ( $this->ertguli_points && is_checkout() ) {
			$this->pay_with_ertguli_points_checkbox();
		}

		if ( $this->supports( 'tokenization' ) && is_checkout() ) {
			$this->tokenization_script();
			$this->saved_payment_methods();
			$this->save_payment_method_checkbox();
		}
	}

	/**
	 * Outputs a checkbox for saving a new payment method to the database.
	 *
	 * @since 3.0.0
	 */
	public function save_payment_method_checkbox() {
		$html = sprintf(
			'<p class="form-row woocommerce-SavedPaymentMethods-saveNew">
				<input id="wc-%1$s-new-payment-method" name="wc-%1$s-new-payment-method" type="checkbox" value="true" style="width:auto;" checked />
				<label for="wc-%1$s-new-payment-method" style="display:inline;">%2$s</label>
			</p>',
			esc_attr( $this->id ),
			$this->save_card_label
		);

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Outputs a checkbox for paying with ertguli points.
	 *
	 * @since 2.3.0
	 */
	public function pay_with_ertguli_points_checkbox() {
		$html = sprintf(
			'<p class="form-row woocommerce-payWithErtguli-checkbox">
				<input id="wc-%1$s-pay-with-ertguli" name="wc-%1$s-pay-with-ertguli" type="checkbox" value="true" style="width:auto;" />
				<label for="wc-%1$s-pay-with-ertguli" style="display:inline;">%2$s</label>
			</p>',
			esc_attr( $this->id ),
			esc_html__( 'Pay with Ertguli Points', 'tbc-gateway' )
		);

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate token for saving a card.
	 *
	 * @since 2.4.0
	 * @return string
	 */
	protected function generate_token() {
		return wp_generate_password( 16, false, false );
	}

}

