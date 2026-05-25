<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'shopg2' );

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
define( 'AUTH_KEY',         '-t#4L?|~bI0:r,I*sfln?{axacn9WSI)/D8Y 6ue.$7UN{`jh2tIpv4c%e2c/@A*' );
define( 'SECURE_AUTH_KEY',  '5jGN@ 7qb7,C_--Z`;XT~5Cer`#rN6%H*d6rr1`ajU cufhv%b.~jduF&o4fNb&*' );
define( 'LOGGED_IN_KEY',    'b*xy1&PwUYB/G$k]bKLbJ%v]u9c+G2YvXED7(@b:1*JWo|h>y+bw!t_))}/u?@7R' );
define( 'NONCE_KEY',        'GistjfM%xlP9XiCG~hvo1ndHi^ryKdTHojQBQPhzM|~EV:0L^Lo-:^ n~DM[B$oE' );
define( 'AUTH_SALT',        '.(tZ<+bVBY[_y$2OWSWE<#&!a=FXD;C+RNXB-PO)|N^ehaZ`v$Oc|>*)OuzM8A[<' );
define( 'SECURE_AUTH_SALT', 'qqSkyFsKjC6L31x]>4eI1R|Uc_B4PLc.nB!nNybY{YtCmNP[wr1dMt(/<:)WS6qh' );
define( 'LOGGED_IN_SALT',   'Y#_H)jTvx-$}XS=[b5gy)D7`T}PxbpTeNo[J[,]cRhSo.!4gPhjL#o)&/},xxchJ' );
define( 'NONCE_SALT',       '}E*<%G?+jPBUKD=zXi17sNCLQB/3eljJ7e4(k)`x%Sf$Q!;$.q[Qac_br5CQ`Bqc' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

define( 'WP_HOME', 'http://localhost/wordpress' );
define( 'WP_SITEURL', 'http://localhost/wordpress' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
