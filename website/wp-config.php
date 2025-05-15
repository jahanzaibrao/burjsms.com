<?php
define( 'WP_CACHE', true ); // Boost Cache Plugin

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'handover');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', 'handoverNewDB!$OK');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'b:9$Ad]vV,d~hX=2;J+dbIOJJI59w?kzxa&^]M1shy8Kd6C(BE9W%_0*03=<qk*@');
define('SECURE_AUTH_KEY',  '7>+(AMmk8e,F*$4c&.3(;,k!$FNoy/b ,Q)D6,UMljtklwIfq%N6tDiF|e^^l)xs');
define('LOGGED_IN_KEY',    'PYznwgZPNiR4z&o$E*5pDzJx|Ch)N#mOmefXVpRy^#Y/qU^W[Xj~Qu9EJ<Uh>T4q');
define('NONCE_KEY',        '{n$}[suk=Zg)S*^)2},hw9Rbrc1Oz)G+KX<vv*l5&Aw{VUA{B/g2VBGSg]Q ]glt');
define('AUTH_SALT',        'G9R@`P U?tNwA?L.s5#5;#K!j*p{;n13?il([tKja{9;30{]Xj $H^XWS9-q)(Xk');
define('SECURE_AUTH_SALT', 'AjwaDy+Zwh#qW@E%kwQmB*yaX?RFf* <zQt$)tAyKU5GsIh9Wn-HeVqDtT2Nos`.');
define('LOGGED_IN_SALT',   'p]c0F>u]3Q654~vy$Xiej`Qh7wjL3o0#+g ]1ar_>,B6n4REzj.Ww`-Ye^a@kel*');
define('NONCE_SALT',       '5Y:FmsW9Xk8vqviUr_&|@W)P$&7e)TE8_pTV3<r!_OD:+Vu^wGXpW:h;}$a|_p0D');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
