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
define( 'DB_NAME', 'wordpress_test_task' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '/]j/]RjJM8]3Th=}>**YU:QOi+fp>=h_wsJmqh&~18(q[|J?b<FDm)_%BS*$(m`%' );
define( 'SECURE_AUTH_KEY',  'rn*c<w0b3F:-I8}1k0?(TMp~M9(r& -!ECtC#%| q+M-m?LyXZ#kZ Pve;j)M7TI' );
define( 'LOGGED_IN_KEY',    '/NaApKF(H.kRZwFNGj~oLO}X5m<Ye0$ tH!w5(lax^sHsvJ@J?~+tPu|70?]B= 5' );
define( 'NONCE_KEY',        'qeyr.q7xughnaz ?.P3)WGD#fdmB7rv{R!94LTl~ (B^8exsV$.eA:5^xO%aq+i3' );
define( 'AUTH_SALT',        'QaXq6qwhL!Q}]C*2sDx()Xz.OYX8^<GqigCS1oomE1RIMR]/{v|K3:B:-r>#[y b' );
define( 'SECURE_AUTH_SALT', 'b,<ZW,r} gxHS8Sxv78)}g0h>t7)5DB5t;c;5]GP/p+h~=skL*}fW5odKHla_c&-' );
define( 'LOGGED_IN_SALT',   '0phR8s0U/NkLXvIb(;:g;928pVYdL(gp53BHZ+%XP2S!Mj|xlkn%SCJP6}2zNw ?' );
define( 'NONCE_SALT',       'Z6|LJ=u^4tZFaJRb4dt>qe,_9KDWm6h(s.aP *DbD1Pq!z}GV>!rNA,>r2g-^+zZ' );

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
