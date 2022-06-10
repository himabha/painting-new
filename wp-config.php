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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'painting_2' );

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
define( 'AUTH_KEY',         '|/~C;muag{l>%$u[E ruyeG|N#[!n+>_[=-,-[N&ys>0wY^<@FEl@Ct)K55^#AN=' );
define( 'SECURE_AUTH_KEY',  '@wJmgdsd|=A_W`Z$z5~OSAh3G&(UyCdB!Bd4G)iSq%HT[t@eM5Z`4%2UWft%E9*x' );
define( 'LOGGED_IN_KEY',    'Rrq|wl%5#V-hLpX0o.`_PW]` Ye7ZB<j?i Ks- kY+I[8SdV<t__8M)R`y@! A{?' );
define( 'NONCE_KEY',        'rctjy> =_KGR>+KnP$X@^4c@AH{#~ih+W_|C^D2so9e}rP>Gof}QL[U;@DJ4JQ8!' );
define( 'AUTH_SALT',        ']qHP1CE^(pw|;aHPh6l9-*iCb:}w$Zhq[*kYYd5@<TK;y`h:R_j{ZVm@mBL2` Up' );
define( 'SECURE_AUTH_SALT', '|U~2OA9<U_cE;ShQ;WdA2Q#SK}&M%c>`2D8I,>-S%Rq{#}wwF|.U#zpb}1:_KVU^' );
define( 'LOGGED_IN_SALT',   't|31@ &.tf$:%}./P$.L(F,GmvQ4+K4d)Z,NGv1QNV(j?Ua{G#RvgY&VK)5uz?~^' );
define( 'NONCE_SALT',       '92>%bfa!0R1MdO=dTDVG8mc8zW*_b9ywP{G0GwBQ}f7X(+.:`I8h]SX%le5u,rv^' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
