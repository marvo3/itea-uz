<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'itea_tashkent');

/** Имя пользователя MySQL */
define('DB_USER', 'itea_tashkent');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '7ZwZYDgO6eEn');

/** Имя сервера MySQL */
define('DB_HOST', '127.0.0.1');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

define('WP_POST_REVISIONS', 10);
/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ycZS~h8ZV?Wl{n+Na`>VbNf7jg?RPN|/+fPhZ+M=KH6_S Hs!KeJH(q2c_Qd5<%-');
define('SECURE_AUTH_KEY',  '2Y]Edmh4DONEmpxC*C3:gXdPl(P0i[7;>7]WE-t MkqIi:XjVj+a8C[[PeitujXN');
define('LOGGED_IN_KEY',    '-7qg}Bb+3fp/>I4ne2tectzb+U|C=G;6jAwN@*F~_hq+}^<H;$I(|;h}Z7bC9,gb');
define('NONCE_KEY',        '[[W e!#Ed`zjmXI8~G-rA|G!u?50*wNtE*l85_5Hm8Sp0`e*^y9e~4eSe>{)xPvq');
define('AUTH_SALT',        'r<(/F^-qEm8}v&f#x[x. 1k`ei9|Ct3:.</zwGr>AaAv{MK(3afugQrCgZ^V.|^d');
define('SECURE_AUTH_SALT', '%I!T#ygM%b]f[x?dKaF&CeW/|^B&qAgp%VT8qPGQGtqx>%wN)Yxy.+*riN4|NHEF');
define('LOGGED_IN_SALT',   '=y]<L*EGTk_Q1=5=kucMMPr^33RYgxW=Zd.V#!kog-xwZ,+iIJ;`/%-[Y41h/Z*4');
define('NONCE_SALT',       'K94RMZbKvJY}PNum2Ah$O5t;VYI(7}+L^5u;-BeNC.mibX0NbJE@?TDgpI&l&->v');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');

/**
if ( is_admin() ) {
add_filter( 'filesystem_method', create_function('$a', 'return "direct";' ) );
define( 'FS_CHMOD_DIR', 0751 );
}
*/