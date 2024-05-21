<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
		<style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #fff;
        }
        .wrapper {
            max-width: 800px;
            margin: 0 auto;
        }
        .header-img {
            background: url('https://dashboard.escard.ge/public/header.jpeg') no-repeat center top;
            background-size: 100% auto; /* Ensures the header is not cut off */
            height: 137px;
            text-align: right;
            background-color: white;

        }
        .content {
            background-color: #fff;
            padding: 0 30px;
        }
        .footer {
            background-color: #ad3d4b;
            border-radius: 20px 20px 0 0;
            padding: 40px 10px;
            text-align: center;
            color: white;
        }
        .store-icons img {
            padding: 0 5px; /* Adjust spacing between icons */
            width: 120px; /* Increased icon size for visibility */
        }
        .contact-info {
            font-size: 11px; /* Smaller font size for contact info */
            color: white;
            text-align: right;
            padding-right: 35px;
        }
        .h1-mobile {
            font-size:2rem;
        }
        .footer h1 {
            font-size:3rem;
        }
        .social-icons img {
            width: 26px; /* Fixed width for social icons */
            filter: invert(1);
        }

        .h3 {
            font-size:24px!important;
        }
        
        .store-icons img {
            width:200px;
        }
        @media only screen and (max-width: 650px) {
            .wrapper {
                width: 100% !important;
                margin: 0 auto;
            }
            .header-img {
                height: 100px; /* Adjusted height for mobile header */
                background-size: cover; /* Change to cover to ensure full visibility */
            }
            .content, .footer {
                padding: 10px; /* Reduced padding for mobile */
            }
            
            .footer h1  {
                text-align:center;
                color:white;
                font-size: 18px!important; /* Smaller font size for headers on mobile */
            }
            .h1-mobile{
                font-size: 17px!important; /* Smaller font size for headers on mobile */
            }
            .store-icons img {
                width: 100px!important; /* Larger icons for better mobile visibility */
            }
            .social-icons img {
                margin: 0 10px; /* Adjusted margin between social icons */
            }
            .contact-info {
                font-size: 9px; /* Even smaller font size for mobile contact info */
            }
            .h3 {
            font-size:16px!important;
        }
        }
    </style>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		 <table class="wrapper" width="700" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; margin:0 auto">
			 <tr>
            <td colspan="2" class="header-img"></td>
            
            
        </tr>
<tr><td>
															<h1 style="text-align:left; font-weight:800"><?php echo esc_html( $email_heading ); ?></h1>
														</td></tr>