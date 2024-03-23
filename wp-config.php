<?php
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
define( 'DB_NAME', 'wpresources' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '&7c/mt@X83m A+U,]V9fK+ri!r@Fvc+v[T5VciOoCVE#^HQ!f2TSMQ}cDLsPjO7#' );
define( 'SECURE_AUTH_KEY',  ']N%zANG_ltClnPb hv4`C7?9$BVVP^$3YD0H?`}Ia#/[5IElvnNQN VRl_wCu3sU' );
define( 'LOGGED_IN_KEY',    'lhWzH}//_;FhY/[zOz,lv!=&<)Tl?SuK!;;J6=q0VTiU#d9]@=bMtnDA&;5:h^63' );
define( 'NONCE_KEY',        'dZW.Q*rDz2kpI^d;5yt=s?JESZ<s^O?i{B/u|Kn*eY%QGct?CYLco-16+3y&/33#' );
define( 'AUTH_SALT',        '/Kb;ib1-X|C@,0m#CK6}~ywgBzS4?g]MC,pg51]L}y`=#?>Q@mq=3 eZ(v)t9>$?' );
define( 'SECURE_AUTH_SALT', '{r:FnlS1s7~_HWD^y6?Zs+}w/dCeN+rx+;at.W3hjkjM;RPeEx1%HKU1^=5=6$k}' );
define( 'LOGGED_IN_SALT',   ']$]xO?RA)<Fl7 L}aC?r6rGk!u2wX+:yU.4cj3ewp@id+8SD-@@KtNo5Jyf#2P|J' );
define( 'NONCE_SALT',       'g69Y/iRpOh.y9j=)MrriK(-WLo[R3QNi!t_dc}_+3m7J ]FDYP<dy! w&j%UOhGb' );

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
