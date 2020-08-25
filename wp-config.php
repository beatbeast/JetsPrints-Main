<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'jetsprints' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ' xUzgY/0q@TNxCslcP3`DLIgSAKe:[|$Dr/X2r+KHUnyZX3wpyB(x(6DI:cE4SGL' );
define( 'SECURE_AUTH_KEY',  '*N8 ggM#g35HlC_{(:5bk9g4RP9)cL)Y1cLr/^yCZM`9iV0DC%YnqV;9)$UYPk%.' );
define( 'LOGGED_IN_KEY',    'YlTVmDSPjYmk ;4U{H[X(+ozY0M0Ib~()eSK;%RR,LE;~r%u]=[LF0]T[dEVbQI,' );
define( 'NONCE_KEY',        '.LW7Eo]nLArxX.Y^s:*dD4H3 %*A^ue,.@5|^VP8+HCXbV ||8FBTVSm#K|d?!6[' );
define( 'AUTH_SALT',        '0xorjxgaO+V@7+}bP>-+*ZqZ*e-b(:q+-23NLm.cgGCU`j>b^Ke?CKl.dBNOv;?+' );
define( 'SECURE_AUTH_SALT', 'KnGoQ<`^gIdAW?*y?28<Ht0]H% 4xMX3iMnAmk~F)AI[gkHJX$_3IY2CWz:y.n07' );
define( 'LOGGED_IN_SALT',   '{vv*gexw6q_|$??^(r6)(=+$SpP =]VVyC)CY7|cT_zn6S+qZpK#1R2BT}]XZnQ[' );
define( 'NONCE_SALT',       'RM($ERX*i. eeu-!B:|/~}o3TldTBIgQ`Aipgd=0jGM]x/rR<=qgAyR|t`pP@sSJ' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'jp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
