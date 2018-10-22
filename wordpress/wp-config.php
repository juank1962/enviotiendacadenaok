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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Tt}uwOw~y2W9_@O8w&,8&fS$u`kl/Jje|w~QEzV{/mVk?PTK*gX<^5?+9yewC @n');
define('SECURE_AUTH_KEY',  '(26WMaLeFlD+iv_#gTFR^PJ:`tF|*`/x@1*!rMp}SqyD/G|Mx]0=OB&ER<{Qb0c%');
define('LOGGED_IN_KEY',    'etQApoM!2fGS@zQQVJPPlX6Zv{IQcxFm-iq8:j5ZYex5TXE 494)0h@3Q:}wsvF4');
define('NONCE_KEY',        '7y7OykNkj2`m-t3lyc]3k;MSYfA$I]lsqV(n._}*Th@Oj<l&?;6|BAi>T9FWkGUs');
define('AUTH_SALT',        '$p8*;Y>W1/m#Vs4W=}x?}rbB*},E24[|BEjL5)NUMci+q0>-Iof~w0wn?n[Mi9pi');
define('SECURE_AUTH_SALT', '_sP4QoBmxJ2U@:,m?}4abx!v<l}djw!UbdE^(si$cCg 4J,2djlH%qI0#sRmG3+o');
define('LOGGED_IN_SALT',   'Jm~.fc;N}i/]~E/ JBL(tbVn|_{|{7S~{3hJY^WWuBXyXQBOl(@s<ktXy<y?u7`r');
define('NONCE_SALT',       'Fmmyyx_?3n0>*=EbFx;G<J P1e9631^Pp,wSxf;RHkFT2Lj. gv.XqVNw^+;mq>D');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
