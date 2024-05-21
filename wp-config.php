<?php
define( 'WP_CACHE', true );


//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'escard_general');

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Qwerty12345@@!' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'lyGW&8Zbl8([(23n#-8/]#v3yjgu9@3Gp|Xq8QmT#]_0N63mF2N7xmA)XZqQ6![B');
define('SECURE_AUTH_KEY', '%yC1Z1LX[EOUD0_7I-qywR_6|ivw3v)4a0NI!~[|93R0:~5|09Zy(2T23/ArRcW&');
define('LOGGED_IN_KEY', 'QJ:B|rV(e77YEjNC6T7*H2%%44!8c3Lv)8-|au]l85Fj;9hgYjg&6ae+D&j220qh');
define('NONCE_KEY', 'XYfT(|c(8g#]R9y+r*nUF#E]m@1_jH|f-&%v)i6]@9mu#8tK0bd|Gyo5-4_-5@JS');
define('AUTH_SALT', '6Tq+n24J7Q%__z4g1pJ/K(Ee(]M90r0la-&);3i]ADVE4%@@z2A5oj29PzT16(T1');
define('SECURE_AUTH_SALT', 'w1Sr6C@[BGKPekQWp|Ep7/8)1*!A[01:0X|4Pg_!V(9d8@o_gFAh(PL:4V#:x8HE');
define('LOGGED_IN_SALT', '967YDJN%2p&;26OK)(o!R[F2+MG967-r85853/J;!2z&6[3cX@X5@mRXR3e3j01%');
define('NONCE_SALT', '87y0il3%GCI9]R4|w:Q*+]dc!M2%p5s&s:9|gd3e@Gq@3kmc~t:FPf&|0a+Q_n[~');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '8hgLcB_';
define( 'WP_DEBUG', false );
define('WP_ALLOW_MULTISITE', true);
  define('WP_MEMORY_LIMIT', '1G');
define( 'DISALLOW_FILE_EDIT', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
