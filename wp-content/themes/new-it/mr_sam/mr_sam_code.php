<?php
function get_itea_home_url() {
    return pll_home_url(get_locale());
}

function hideLangSwitchAndSetCorrectLang() {
    add_action('wp_head', function() {
        echo '<style type="text/css">#header .container .lang {display: none !important;}</style>';
    });

    if ($_COOKIE['pll_language'] == 'uk') {
        add_filter('locale', function($lang) {
            return 'uk';
        });
        setcookie('pll_language', 'uk', time()+9999999, "/");
    }
}



// Убираем визуальную вкладку редактора на страницах "контактов"
add_action('admin_head', function() {
    if (get_current_screen()->id === 'page' && in_array(get_the_ID(), [25, 7938, 11097]))
    {
        echo '<style type="text/css">.wp-switch-editor.switch-tmce {display:none !important;}</style>';
        add_filter('wp_default_editor', create_function('', 'return "html";'));
    }
});



// START category_custom_fields
// добавляет вызов функции при инициализации административного раздела
add_action('admin_init', 'category_custom_fields', 1, 0);
function category_custom_fields() {

    if (!empty($_GET) && !empty($_GET['tag_ID'])) {
        $check = get_category( $_GET['tag_ID'] )->parent;
        if($check == 22 || $check == 219) {
            add_action('category_edit_form_fields', 'category_custom_fields_form');
        }
    }
    if(array_key_exists('cat_meta', $_POST)) {
        add_action('edited_category', 'category_custom_fields_save');
    }
}

function category_custom_fields_form($tag) {
    $t_id = $tag->term_id;
    $cat_meta = get_option('category_'.$t_id);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_meta[cat_text]">После прохождения Roadmap Вы сможете:</label></th>
        <td>
            <textarea name="cat_meta[cat_text]" id="cat_meta[cat_text]" rows="10" cols="50" class="large-text"><?php
                echo $cat_meta['cat_text'] ? $cat_meta['cat_text'] : '';
                ?></textarea>
            <p class="description">Пример: &nbsp;  &nbsp; &lt;ul&gt; &lt;li&gt;Пункт 1&lt;/li&gt;  &nbsp; . . . &nbsp;  &lt;li&gt;Пункт N&lt;/li&gt; &lt;/ul&gt;</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_meta[courses]">Курсы, которые предлагаются потом:</label></th>
        <td>
            <textarea name="cat_meta[courses]" id="cat_meta[courses]" rows="1" cols="50" class="large-text"><?php
                echo $cat_meta['courses'] ? $cat_meta['courses'] : '';
                ?></textarea>
            <p class="description">Пример: &nbsp;  &nbsp; 1, 85, 29, 3</p><br>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_meta[di_full]">Скидка на Roadmap "Полный пакет":</label></th>
        <td>
            <textarea name="cat_meta[di_full]" id="cat_meta[di_full]" rows="1" cols="50" class="large-text"><?php
                echo $cat_meta['di_full'] ? $cat_meta['di_full'] : '';
                ?></textarea>
            <p class="description">Пример: &nbsp;  &nbsp; 30</p><br>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_meta[di_part]">Скидка на Roadmap "Подобрать курсы":</label></th>
        <td>
            <textarea name="cat_meta[di_part]" id="cat_meta[di_part]" rows="1" cols="50" class="large-text"><?php
                echo $cat_meta['di_part'] ? $cat_meta['di_part'] : '';
                ?></textarea>
            <p class="description">Пример: &nbsp;  &nbsp; 15</p><br>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_meta[for_di_part]">Необходимое количество курсов для скидки на Roadmap "Подобрать курсы":</label></th>
        <td>
            <textarea name="cat_meta[for_di_part]" id="cat_meta[for_di_part]" rows="1" cols="50" class="large-text"><?php
                echo $cat_meta['for_di_part'] ? $cat_meta['for_di_part'] : '';
                ?></textarea>
            <p class="description">Пример: &nbsp;  &nbsp; 3</p><br>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_meta[active_tab]">Активная вкладка на Roadmap:</label></th>
        <td>
            <p style="width:250px;float:left;">
                <input type="radio" name="cat_meta[active_tab]" value="full" id="radio1" <?php echo $cat_meta['active_tab'] == 'full' ? 'checked' : ''; ?>>
                &nbsp; <label for="radio1">"Полный пакет"</label>
            </p>
            <p>
                <input type="radio" name="cat_meta[active_tab]" value="choice" id="radio2" <?php echo $cat_meta['active_tab'] == 'choice' ? 'checked' : ''; ?>>
                &nbsp; <label for="radio2">"Подобрать курсы"</label>
            </p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_meta[roadmap_type]">Внешний вид страницы Roadmap:</label></th>
        <td>
            <p style="width:250px;float:left;">
                <input type="radio" name="cat_meta[roadmap_type]" value="standard" id="radio3" <?php echo $cat_meta['roadmap_type'] == 'standard' ? 'checked' : ''; ?>>
                &nbsp; <label for="radio3">"Стандартный"</label>
            </p>
            <p>
                <input type="radio" name="cat_meta[roadmap_type]" value="minimized" id="radio4" <?php echo $cat_meta['roadmap_type'] == 'minimized' ? 'checked' : ''; ?>>
                &nbsp; <label for="radio4">"Минимизированный"</label>
            </p>
        </td>
    </tr>
    <?php
}

function category_custom_fields_save($term_id) {
    if (isset($_POST['cat_meta'])) {
        $t_id = $term_id;
        $cat_meta = get_option('category_'.$t_id);
        $cat_keys = array_keys($_POST['cat_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['cat_meta'][$key])) {
                $cat_meta[$key] = $_POST['cat_meta'][$key];
            }
        }
        //save the option array
        update_option('category_'.$t_id, $cat_meta);
    }
}
// END category_custom_fields


/*
 * data:   25.04.16
 *
 * Функция идентична функцие с файла js/roadmap_v*.js
 *
 * При необходимости изменить логику, для корректности вывода информации, необходимо изменить функцию и на PHP и на JS
 *
 */
function nicePrice($price)
{
    $price = (int)$price;
    $price = (string)$price;
    if (strlen($price) > 2) {
        $price = substr($price, 0, -1);
        switch (substr($price, -1, 1)) {
            case '1':
            case '2':
                $price = substr($price, 0, -1) . '00';
                break;
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
                $price = substr($price, 0, -1) . '50';
                break;
            case '8':
            case '9':
                $price = (string)((int)(substr($price, 0, -1)) + 1) . '00';
                break;
            default:
                $price .= '0';
        }
    }
    return (int)$price;
}
/* END  nicePrice */
function priceThousend($price)
{
  $price = number_format($price, 0, '', ' ');
  return $price;
}

//// START cron
//if ( ! wp_next_scheduled('cron_daily_exchange_rates') ) {
//    wp_schedule_event( time(), 'daily', 'cron_daily_exchange_rates' );
//}
////wp_clear_scheduled_hook('cron_daily_exchange_rates');

//function gns_cron_daily() {
//    global $wpdb;
//    $table_name = $wpdb->get_blog_prefix() . 'exchange_rates';
//    $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
//
//    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//    $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
//                id int(1) NOT NULL AUTO_INCREMENT,
//                usd float NOT NULL DEFAULT '1',
//                eur float NOT NULL DEFAULT '1',
//                rur float NOT NULL DEFAULT '1',
//                PRIMARY KEY (id)
//    ) {$charset_collate};";
//
//    dbDelta( $sql );
//
//    $xml = simplexml_load_file("https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=11");
//    $usd = (float) ($xml->xpath('//exchangerate[@ccy="USD"]')[0]['sale']);
//    $eur = (float) ($xml->xpath('//exchangerate[@ccy="EUR"]')[0]['sale']);
//    $rur = (float) ($xml->xpath('//exchangerate[@ccy="RUR"]')[0]['sale']);
//
//    if( $wpdb->get_var('SELECT * FROM '.$table_name) ){
//        $wpdb->update( $table_name, array('usd' => $usd, 'eur' => $eur, 'rur' => $rur), array('id' => 1) );
//    } else {
//        $wpdb->insert( $table_name, array('usd' => $usd, 'eur' => $eur, 'rur' => $rur), array('%f', '%f', '%f') );
//    }
//}
//add_action('cron_daily_exchange_rates', 'gns_cron_daily', 10, 0);
//// END cron


function getListRoadmaps() {
    $lang = (get_locale() == 'ru_RU');
    $categories = ( $lang ? get_categories('child_of=22') : get_categories('child_of=219') );
    $numCats = count($categories);
    $courses_order = array_fill(0, ($numCats-1), 0);
    foreach ($categories as $key ) {
        switch ($key->cat_ID) {
            case '16': // Тестирование
            case '259':
                $courses_order[0] = $key;
                break;
            case '15': // Frontend development
            case '241':
                $courses_order[1] = $key;
                break;
            case '4': // JS development
            case '245':
                $courses_order[2] = $key;
                break;
            case '18': // UI/UX Design
            case '251':
                $courses_order[3] = $key;
                break;
            case '5': // PHP
            case '247':
                $courses_order[4] = $key;
                break;
//            case '14': // Программирование под IOS
//            case '257':
//                $courses_order[5] = $key;
//                break;
//            case '13': // Программирование под Android
//            case '255':
//                $courses_order[6] = $key;
//                break;
            case '7': // Java programming
            case '243':
                $courses_order[7] = $key;
                break;
            //case '81': // Робототехника
            //case '221':
                //$courses_order[] = $key;
                //break;
            case '12': // Python
            case '249':
                $courses_order[8] = $key;
                break;
           case '985': // Data Science/Machine Learning
           case '987':
               $courses_order[9] = $key;
               break;
            case '8': // C#
            case '237':
                $courses_order[10] = $key;
                break;
            case '9': // C++
            case '239':
                $courses_order[11] = $key;
                break;
           case '1062': // Game Development
           case '1060':
               $courses_order[12] = $key;
               break;
//            case '673': // DEVOPS
//            case '675':
//                $courses_order[13] = $key;
//                break;
//            case '105': // Курсы для детей (1)
//            case '226':
//                $courses_order[14] = $key;
//                break;
//            case '1005': // Курсы для детей (2)
//            case '1007':
//                $courses_order[15] = $key;
//                break;
//            case '1009': // Курсы для детей (3)
//            case '1011':
//                $courses_order[16] = $key;
//                break;/**/
//            case '1146': // DIGITAL MARKETING
//            case '1148':
//                $courses_order[17] = $key;
//                break;
            case '990': // Управление персоналом
            case '992':
                $courses_order[18] = $key;
                break;
            case '746': // Управление проектами
            case '748':
                $courses_order[19] = $key;
                break;
            case '1122': // Менеджмент
            case '1124':
                $courses_order[20] = $key;
                break;
//            case '1202': // Кибербезопасность
//            case '1204':
//                $courses_order[21] = $key;
//                break;
//            case '1234': // Видеомонтаж
            case '673':
                $courses_order[21] = $key; // devops
                break;
            case '1310':
                $courses_order[22] = $key; // Mobile development
                break;
            case '1312':
            $courses_order[23] = $key; // Go Development
            break;
            case '1146':
                $courses_order[24] = $key; // Digital Marketing
                break;
            case '1314':
                $courses_order[25] = $key; // Основы компьютерной грамотности
                break;
            case '1318':
                $courses_order[26] = $key; // Системное администрирование
                break;
            case '1318':
                $courses_order[27] = $key; // Full Stack Java Developer
                break;
            case '1320':
                $courses_order[27] = $key; // Full Stack Java Developer
                break;
            /*default:
                $courses_order[] = $key;*/
        }
    }
    if(in_array(0, $courses_order)){
        $courses_order = array_filter($courses_order, function($element) {
            return !empty($element);
        });
    }
    return $courses_order;
}


function pageCoursesItea($homePage = false) {
    $lang = (get_locale() == 'ru_RU');
    $word1 = ($lang ? 'Просмотреть' : 'Переглянути');
    if($homePage){
        echo '<div role="tabpanel" class="tab-pane fade in active" id="home">';
    } else {
        ?>
        <div class="container" id="flip-scroll">
        <div class="head-section">
            <a class="linkCourseToT" href="<?php echo get_permalink( ($lang ? 17 : 7863) ); ?>">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/calendarIco.png">
                <?php echo ($lang ? 'Перейти к расписанию' : 'Перейти до розкладу'); ?>
            </a>
            <h1><?php single_cat_title(); ?></h1>
        </div>

        <div class="block-news clearfix"><section id="course" class="no-mar">
    <?php } ?>
    <ul class="filter">
        <li data-filter="*" class="current"><?php echo ($lang ? 'Все направления' : 'Всі напрямки'); ?></li>
        <li data-filter=".programming"><?php echo ($lang ? 'Программирование' : 'Програмування'); ?></li>
        <li data-filter=".frontend">Frontend</li>
        <li data-filter=".design">Дизайн</li>
        <li data-filter=".qa">QA</li>
        <!--<li data-filter=".mobile">Mobile development</li>-->
        <?php /*?><li data-filter=".child-sourse"><?php echo ($lang ? 'Курсы для детей' : 'Курси для дітей'); ?></li><?php /**/?>
        <li data-filter=".other"><?php echo ($lang ? 'Другие' : 'Інші'); ?></li>
    </ul>

    <div class="container_12 isotope">
        <?php
        $categories = getListRoadmaps();
        foreach ($categories as $category)
        {
            $thumbnail_id = get_term_thumbnail_id($category->term_id);

            if (empty($thumbnail_id)) { continue; }
            ?>
            <div class="grid_3 item <?php echo get_post_field('post_content', $thumbnail_id); ?>">
                <div class="img" style="background:<?php echo get_post_field('post_excerpt', $thumbnail_id); ?>;">
                    <img src="<?php echo wp_get_attachment_image_src($thumbnail_id, 'full')[0]?>" style="max-width:140px; max-height:70px;">
                    <a href="<?php echo get_category_link($category->term_id); ?>" class="view"><?php echo $word1; ?></a>
                </div>
                <h2><?php echo $category->name; ?></h2>
            </div>
            <?php
        }
        ?>
    </div>
    <?php echo ($homePage ? '' : '</section></div>'); ?>
    </div>
    <?php
}


function pageCorporateEducation($homePage = false) {
    $lang = (get_locale() == 'ru_RU');
    $word1 = ($lang ? 'Просмотреть' : 'Переглянути');
    if($homePage){
        echo '<div role="tabpanel" class="tab-pane fade" id="profile">';
    } else {
        ?>
        <div class="container" id="flip-scroll">
        <div class="head-section">
            <a class="linkCourseToT" href="<?php echo get_permalink( ($lang ? 386 : 7953) ); ?>">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/calendarIco.png">
                <?php echo ($lang ? 'Перейти к расписанию' : 'Перейти до розкладу'); ?>
            </a>
            <h1><?php single_cat_title(); ?></h1>
        </div>
        <div class="block-news clearfix">
        <section id="course" class="no-mar">
    <?php } ?>
    <div class="container_12 isotopSec<?php echo ($homePage ? '' : ' no-pad'); ?>">
        <!-- There is items-->
        <div class="grid_3 item" >
            <div class="img" style="background: linear-gradient(to right, #019C00, #4ED32A)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/windows.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 35 : 340) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>Microsoft</h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #006C9A, #33BCEA)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/cisco.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 31 : 331) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>Cisco</h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #FF5001, #FF8601)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/linux.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 45 : 622) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>UNIX / Linux</h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #EA1B23, #FE4C4C)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/bd.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 47 : 628) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>Oracle</h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #5C2455, #9F799E)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/meto.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 46 : 584) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>ITIL</h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #1E8194, #5CCEF0)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/progr.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 41 : 580) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2><?php echo ($lang ? 'Программирование' : 'Програмування'); ?></h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #26B768, #4AD594)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/man.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 40 : 582) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2><?php echo ($lang ? 'Управление проектами' : 'Управління проектами'); ?></h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #F7A921, #FEF153)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/off.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 58 : 510) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2><?php echo ($lang ? 'Пользовательские курсы' : 'Курси для користувачів'); ?></h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #7C7B7E, #B6B6B6)">
                <img class="img-responsive" style="width:100%;" src="<?php bloginfo('template_directory'); ?>/relize/img/icons/vmware.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 104 : 534) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>Vmware</h2>
        </div>
        <div class="grid_3 item">
            <div class="img" style="background: linear-gradient(to right, #ec8500, #e49632)">
                <img class="img-responsive" style="width:100%;" src="<?php bloginfo('template_directory'); ?>/relize/img/icons/Teradata_logo.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 888 : 890) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>Teradata</h2>
        </div>
        <div class="grid_3 item" >
            <div class="img" style="background: linear-gradient(to right, #d30909, #f82222)">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/icons/ec-council.png" alt="">
                <a href="<?php echo get_category_link( ($lang ? 1213 : 1215) ); ?>" class="view"><?php echo $word1; ?></a></div>
            <h2>EC-Council</h2>
        </div>
    </div>
    <?php echo ($homePage ? '' : '</section></div>'); ?>
    </div>
    <?php
}


function prefix_generate_resume() {
    if ( wp_verify_nonce($_POST['verification'],'ITEA_of_the_best!') )
    {
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'resume';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $sql = "CREATE TABLE {$table_name} (
                id int(11) unsigned NOT NULL AUTO_INCREMENT,
                date_time  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                name   varchar(255) DEFAULT NULL,
                date_birth varchar(255) DEFAULT NULL,
                email   varchar(255) DEFAULT NULL,
                phone   varchar(255) DEFAULT NULL,
                address varchar(255) DEFAULT NULL,
                about_me text DEFAULT NULL,
                linkedin  varchar(500) DEFAULT NULL,
                facebook  varchar(500) DEFAULT NULL,
                portfolio varchar(500) DEFAULT NULL,
                w1_places varchar(500) DEFAULT NULL,
                w1_positions varchar(255) DEFAULT NULL,
                w1_duties    varchar(1000) DEFAULT NULL,
                w1_periods   varchar(255) DEFAULT NULL,
                w2_places    varchar(500) DEFAULT NULL,
                w2_positions varchar(255) DEFAULT NULL,
                w2_duties    varchar(1000) DEFAULT NULL,
                w2_periods   varchar(255) DEFAULT NULL,
                edu1_names   varchar(255) DEFAULT NULL,
                edu1_specialties   varchar(255) DEFAULT NULL,
                edu2_names         varchar(255) DEFAULT NULL,
                edu2_specialties   varchar(255) DEFAULT NULL,
                courses            varchar(2000) DEFAULT NULL,
                tag_cloud          varchar(2000) DEFAULT NULL,
                personal_qualities varchar(2000) DEFAULT NULL,
                eng int(1) unsigned DEFAULT NULL,
                link_to_photo varchar(255) DEFAULT NULL,
                uniqid        varchar(100)  DEFAULT NULL,
                password      varchar(255)  DEFAULT NULL,
                confirm      tinyint(1)    NOT NULL DEFAULT '0',
                public_offer tinyint(1)    DEFAULT NULL,
                comment text DEFAULT NULL,
                PRIMARY KEY (id),
                KEY uniqid (uniqid)
    ) {$charset_collate};";

        //dbDelta($sql);

        $name = (array_key_exists('name', $_POST) ? trim(htmlspecialchars($_POST['name'])) : '');
        $date_birth = (array_key_exists('date_birth', $_POST) ? trim(htmlspecialchars($_POST['date_birth'])) : '');
        $email      = (array_key_exists('email', $_POST) ? trim(htmlspecialchars($_POST['email'])) : '');
        $phone      = (array_key_exists('phone', $_POST) ? trim(htmlspecialchars($_POST['phone'])) : '');
        $address    = (array_key_exists('address', $_POST) ? trim(htmlspecialchars($_POST['address'])) : '');
        $about_me   = (array_key_exists('about_me', $_POST) ? trim(htmlspecialchars($_POST['about_me'])) : '');
        $linkedin   = (array_key_exists('linkedin', $_POST) ? trim(htmlspecialchars($_POST['linkedin'])) : '');
        $facebook   = (array_key_exists('facebook', $_POST) ? trim(htmlspecialchars($_POST['facebook'])) : '');
        $portfolio  = (array_key_exists('portfolio', $_POST) ? trim(htmlspecialchars($_POST['portfolio'])) : '');
        $w1_places    = (array_key_exists('w1_places', $_POST) ? trim(htmlspecialchars($_POST['w1_places'])) : '');
        $w1_positions = (array_key_exists('w1_positions', $_POST) ? trim(htmlspecialchars($_POST['w1_positions'])) : '');
        $w1_duties    = (array_key_exists('w1_duties', $_POST) ? trim(htmlspecialchars($_POST['w1_duties'])) : '');
        $w1_periods   = '';
        $w2_places    = (array_key_exists('w2_places', $_POST) ? trim(htmlspecialchars($_POST['w2_places'])) : '');
        $w2_positions = (array_key_exists('w2_positions', $_POST) ? trim(htmlspecialchars($_POST['w2_positions'])) : '');
        $w2_duties    = (array_key_exists('w2_duties', $_POST) ? trim(htmlspecialchars($_POST['w2_duties'])) : '');
        $w2_periods   = '';
        $edu1_names       = (array_key_exists('edu1_names', $_POST) ? trim(htmlspecialchars($_POST['edu1_names'])) : '');
        $edu1_specialties = (array_key_exists('edu1_specialties', $_POST) ? trim(htmlspecialchars($_POST['edu1_specialties'])) : '');
        $edu2_names       = (array_key_exists('edu2_names', $_POST) ? trim(htmlspecialchars($_POST['edu2_names'])) : '');
        $edu2_specialties = (array_key_exists('edu2_specialties', $_POST) ? trim(htmlspecialchars($_POST['edu2_specialties'])) : '');
        $courses            = '';
        $tag_cloud          = (array_key_exists('tag_cloud', $_POST) ? trim(htmlspecialchars($_POST['tag_cloud'])) : '');
        $personal_qualities = (array_key_exists('personal_qualities', $_POST) ? trim(htmlspecialchars($_POST['personal_qualities'])) : '');
        $eng                = (array_key_exists('eng', $_POST) ? trim(htmlspecialchars($_POST['eng'])) : '');
        $link_to_photo      = '';
        $uniqid       = uniqid();
        $confirm      = (array_key_exists('confirm', $_POST) ? trim(htmlspecialchars($_POST['confirm'])) : '');
        $public_offer = (array_key_exists('public_offer', $_POST) ? 1 : 0);
        $comment      = (array_key_exists('comment', $_POST) ? trim(htmlspecialchars($_POST['comment'])) : '');

        $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP_';
        $pass_length  = 10;
        $pass_size    = strlen($chars)-1;
        $password     = '';
        while($pass_length--) { $password .= $chars[rand(0,$pass_size)]; }


        if(!empty($_POST['w1_periods_1'])) {
            $w1_periods = trim(htmlspecialchars($_POST['w1_periods_1'])) . (!empty($_POST['w1_periods_2']) ? ' – '.trim(htmlspecialchars($_POST['w1_periods_2'])) : '');
        }

        if(!empty($_POST['w2_periods_1'])) {
            $w2_periods = trim(htmlspecialchars($_POST['w2_periods_1'])) . (!empty($_POST['w2_periods_2']) ? ' – '.trim(htmlspecialchars($_POST['w2_periods_2'])) : '');
        }

        if(array_key_exists('courses', $_POST)) {
            $stack = explode(',', trim(htmlspecialchars($_POST['courses'])));
            $courses = array();

            $posts = get_posts([
                'numberposts'     => -1,
                'category'        => '22',
                'post_type'       => 'post',
                'orderby'         => 'title'
            ]);
            foreach ($posts as $_post) {
                if (in_array($_post->post_title, $stack)) {
                    array_push($courses, $_post->ID);
                    if (sizeof($stack) == sizeof($courses)) {break;}
                }
            }

            if(in_array('ICND1', $stack)){array_push($courses, 8519);}
            if(in_array('ICND2', $stack)){array_push($courses, 8521);}

            $courses = implode(',', $courses);
        }

        if ( !function_exists('wp_handle_upload') ){
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $upload_dir = wp_upload_dir();
        $link_to_photo = $upload_dir['url'] .'/'.$uniqid.image_type_to_extension(IMAGETYPE_JPEG);
        if (!empty($_POST['cropped_image'])) {
            $img_format    = mb_substr($_POST['cropped_image'], mb_stripos($_POST['cropped_image'], 'data:')+5);
            $img_format    = mb_substr($img_format, 0, mb_stripos($img_format, ';'));
            $cropped_image = explode(',', $_POST['cropped_image']);
            $cropped_image = array_pop($cropped_image);
            $cropped_image = base64_decode($cropped_image);
            $path_for_img  = $upload_dir['basedir'].'/temp_img.'.mb_substr($img_format, mb_stripos($img_format, 'image/')+6);
            file_put_contents($path_for_img, $cropped_image);
        } else {
            $movefile      = wp_handle_upload( $_FILES['link_to_photo'], array('test_form' => false) );
            $img_format    = $movefile['type'];
            $path_for_img  = $movefile['file'];
        }

        if (stristr($img_format, 'image')) {

            switch($img_format) {
                case "image/gif":
                    $img = imagecreatefromgif($path_for_img);
                    break;
                case "image/jpeg":
                    $img = imagecreatefromjpeg($path_for_img);
                    break;
                case "image/png":
                    $img = imagecreatefrompng($path_for_img);
                    break;
                default:
                    (is_file($path_for_img) ? unlink($path_for_img) : '');
                    echo '<h1>ERROR_#1</h1>'; die;
            }
            list($w,$h) = getimagesize($path_for_img); // берем высоту и ширину
            $new_w = ($w > 250 ? 250 : $w);
            $coeff = $w/$new_w;
            $new_h = ceil($h/$coeff); // с помощью коэффициента вычисляем высоту
            $new_img = imagecreatetruecolor($new_w, $new_h); // создаем картинку
            imagecopyresampled($new_img,$img,0,0,0,0,$new_w,$new_h,imagesx($img),imagesy($img));
            imagedestroy($img);
            (is_file($path_for_img) ? unlink($path_for_img) : '');
            imageconvolution($new_img, array( // улучшаем четкость
                array(-1,-1,-1),
                array(-1,16,-1),
                array(-1,-1,-1) ),
                8, 0);
            imagejpeg($new_img, $upload_dir['path'].'/'.$uniqid.image_type_to_extension(IMAGETYPE_JPEG), 100); // переводим в jpg
            imagedestroy($new_img);
        } else {
            (is_file($path_for_img) ? unlink($path_for_img) : '');
            echo '<h1>ERROR_#2</h1>'; die;
        }

        $creating_resume = $wpdb->insert($table_name, [
                'name'               => htmlspecialchars($name),
                'date_birth'         => htmlspecialchars($date_birth),
                'email'              => htmlspecialchars($email),
                'phone'              => htmlspecialchars($phone),
                'address'            => htmlspecialchars($address),
                'about_me'           => htmlspecialchars($about_me),
                'linkedin'           => esc_sql($linkedin),
                'facebook'           => esc_sql($facebook),
                'portfolio'          => esc_sql($portfolio),
                'w1_places'          => htmlspecialchars($w1_places),
                'w1_positions'       => htmlspecialchars($w1_positions),
                'w1_duties'          => htmlspecialchars($w1_duties),
                'w1_periods'         => htmlspecialchars($w1_periods),
                'w2_places'          => htmlspecialchars($w2_places),
                'w2_positions'       => htmlspecialchars($w2_positions),
                'w2_duties'          => htmlspecialchars($w2_duties),
                'w2_periods'         => htmlspecialchars($w2_periods),
                'edu1_names'         => htmlspecialchars($edu1_names),
                'edu1_specialties'   => htmlspecialchars($edu1_specialties),
                'edu2_names'         => htmlspecialchars($edu2_names),
                'edu2_specialties'   => htmlspecialchars($edu2_specialties),
                'courses'            => htmlspecialchars($courses),
                'tag_cloud'          => htmlspecialchars($tag_cloud),
                'personal_qualities' => htmlspecialchars($personal_qualities),
                'eng'                => htmlspecialchars($eng),
                'link_to_photo'      => esc_sql($link_to_photo),
                'uniqid'             => $uniqid,
                'password'           => md5($password),
                'confirm'            => htmlspecialchars($confirm),
                'public_offer'       => htmlspecialchars($public_offer),
                'comment'            => htmlspecialchars($comment),
        ]);

        if ($creating_resume) {
            $subject = 'Начинай карьеру с ITEA';
            $message  = file_get_contents(get_template_directory().'/mr_sam/Views/email_resume_part1.html');
            $message .= 'URL Вашего резюме: <strong>itea.ua/resume/?id='.$uniqid.'</strong><br>';
            $message .= 'Пароль для редактирования Вашего резюме: <strong>'.$password.'</strong><br><br><br>';
            $message .= '<span style="line-height:40px;color:#fefefe;font-family:Arial;font-size:16px;font-weight:400;display:block;margin:auto;width:246px;height:40px;background-color:#ed1c24;text-align:center;">';
            $message .= '<a href="https://itea.uz/resume/?id='.$uniqid.'" style="text-decoration: none; color: #fefefe; display: block" target="_blank">Перейти к Вашему резюме</a></span>';
            $message .= file_get_contents(get_template_directory().'/mr_sam/Views/email_resume_part2.html');
            $headers = array();
            $headers[] = 'From: IT Education Academy <info@itea.ua>';
            $headers[] = 'Content-Type: text/html';
            wp_mail($email, $subject, $message, $headers);
            wp_redirect( add_query_arg('id', $uniqid, get_permalink(7633)) ); exit;
        } else {
            echo '<h1>ERROR_#3</h1>'; die;
        }
    }
}
add_action( 'admin_post_nopriv_form_to_resume', 'prefix_generate_resume', 10, 0 );
add_action( 'admin_post_form_to_resume'       , 'prefix_generate_resume', 10, 0 );


function prefix_update_resume() {
    $role_user  = wp_verify_nonce($_POST['verification'],$_POST['id'].'you_mortal');
    $role_admin = wp_verify_nonce($_POST['verification'],$_POST['id'].'gns+itea'  );
    if ($role_user || $role_admin) {
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'resume';

        $uniqid        = esc_sql($_POST['id']);
        $w1_periods    = '';
        $w2_periods    = '';
        $courses       = '';
        $link_to_photo = $wpdb->get_var('SELECT link_to_photo FROM '.$table_name.' WHERE uniqid = \''.$uniqid.'\'');

        if(!empty($_POST['w1_periods_1'])) {
            $w1_periods = trim(htmlspecialchars($_POST['w1_periods_1'])) . (!empty($_POST['w1_periods_2']) ? ' – '.trim(htmlspecialchars($_POST['w1_periods_2'])) : '');
        }

        if(!empty($_POST['w2_periods_1'])) {
            $w2_periods = trim(htmlspecialchars($_POST['w2_periods_1'])) . (!empty($_POST['w2_periods_2']) ? ' – '.trim(htmlspecialchars($_POST['w2_periods_2'])) : '');
        }

        if(array_key_exists('courses', $_POST) && is_array($_POST['courses'])) {
            $stack = $_POST['courses'];
            $courses = array();

            $posts = get_posts([
                'numberposts'     => -1,
                'category'        => '22',
                'post_type'       => 'post',
                'orderby'         => 'title'
            ]);
            foreach ($posts as $_post) {
                if (in_array($_post->post_title, $stack)) {
                    array_push($courses, $_post->ID);
                    if (sizeof($stack) == sizeof($courses)) {break;}
                }
            }

            if(in_array('ICND1', $stack)){array_push($courses, 8519);}
            if(in_array('ICND2', $stack)){array_push($courses, 8521);}

            $courses = implode(',', $courses);
        }

        if ( !function_exists('wp_handle_upload') ){
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        if (!empty($_POST['cropped_image']) || $_FILES['link_to_photo']['size'] != 0) {
            $upload_dir = wp_upload_dir();
            if(!empty($_POST['cropped_image'])){
                $img_format    = mb_substr($_POST['cropped_image'], mb_stripos($_POST['cropped_image'], 'data:')+5);
                $img_format    = mb_substr($img_format, 0, mb_stripos($img_format, ';'));
                $cropped_image = explode(',', $_POST['cropped_image']);
                $cropped_image = array_pop($cropped_image);
                $cropped_image = base64_decode($cropped_image);
                $path_for_img  = $upload_dir['basedir'].'/temp_img.'.mb_substr($img_format, mb_stripos($img_format, 'image/')+6);
                file_put_contents($path_for_img, $cropped_image);
            } else {
                $movefile = wp_handle_upload( $_FILES['link_to_photo'], array('test_form' => false) );
                $img_format    = $movefile['type'];
                $path_for_img  = $movefile['file'];
            }

            if(stristr($img_format, 'image') ){
                switch($img_format) {
                    case "image/gif":
                        $img = imagecreatefromgif($path_for_img);
                        break;
                    case "image/jpeg":
                        $img = imagecreatefromjpeg($path_for_img);
                        break;
                    case "image/png":
                        $img = imagecreatefrompng($path_for_img);
                        break;
                    default:
                        (is_file($path_for_img) ? unlink($path_for_img) : '');
                        echo '<h1>ERROR_#1</h1>'; die;
                }
                list($w,$h) = getimagesize($path_for_img); // берем высоту и ширину
                $new_w = ($w > 250 ? 250 : $w);
                $coeff = $w/$new_w;
                $new_h = ceil($h/$coeff); // с помощью коэффициента вычисляем высоту
                $new_img = imagecreatetruecolor($new_w, $new_h); // создаем картинку
                imagecopyresampled($new_img,$img,0,0,0,0,$new_w,$new_h,imagesx($img),imagesy($img));
                imagedestroy($img);
                (is_file($path_for_img) ? unlink($path_for_img) : '');
                imageconvolution($new_img, array( // улучшаем четкость
                    array(-1,-1,-1),
                    array(-1,16,-1),
                    array(-1,-1,-1) ),
                    8, 0);

                $link_to_photo = mb_substr(TEMPLATEPATH, 0, mb_stripos(TEMPLATEPATH, 'wp-content')-1).parse_url($link_to_photo, PHP_URL_PATH);
                (is_file($link_to_photo) ? unlink($link_to_photo) : '');
                $link_to_photo = $upload_dir['url'] .'/'.$uniqid.image_type_to_extension(IMAGETYPE_JPEG);

                imagejpeg($new_img, $upload_dir['path'].'/'.$uniqid.image_type_to_extension(IMAGETYPE_JPEG), 100); // переводим в jpg
                imagedestroy($new_img);
            } else {
                (is_file($path_for_img) ? unlink($path_for_img) : '');
                echo '<h1>ERROR_#2</h1>'; die;
            }
        }

        $new_fields = [
                'name'               => htmlspecialchars($_POST['name']),
                'email'              => htmlspecialchars($_POST['email']),
                'date_birth'         => htmlspecialchars($_POST['date_birth']),
                'phone'              => htmlspecialchars($_POST['phone']),
                'address'            => htmlspecialchars($_POST['address']),
                'about_me'           => htmlspecialchars($_POST['about_me']),
                'linkedin'           => esc_sql($_POST['linkedin']),
                'facebook'           => esc_sql($_POST['facebook']),
                'portfolio'          => esc_sql($_POST['portfolio']),
                'w1_places'          => htmlspecialchars($_POST['w1_places']),
                'w1_positions'       => htmlspecialchars($_POST['w1_positions']),
                'w1_duties'          => htmlspecialchars($_POST['w1_duties']),
                'w1_periods'         => htmlspecialchars($w1_periods),
                'w2_places'          => htmlspecialchars($_POST['w2_places']),
                'w2_positions'       => htmlspecialchars($_POST['w2_positions']),
                'w2_duties'          => htmlspecialchars($_POST['w2_duties']),
                'w2_periods'         => htmlspecialchars($w2_periods),
                'edu1_names'         => htmlspecialchars($_POST['edu1_names']),
                'edu1_specialties'   => htmlspecialchars($_POST['edu1_specialties']),
                'edu2_names'         => htmlspecialchars($_POST['edu2_names']),
                'edu2_specialties'   => htmlspecialchars($_POST['edu2_specialties']),
                'courses'            => htmlspecialchars($courses),
                'tag_cloud'          => htmlspecialchars($_POST['tag_cloud']),
                'personal_qualities' => htmlspecialchars($_POST['personal_qualities']),
                'eng'                => htmlspecialchars($_POST['eng']),
                'link_to_photo'      => esc_sql($link_to_photo),
        ];
        if ($role_admin) {
            $new_fields['confirm'] = (array_key_exists('confirm', $_POST) ? trim(htmlspecialchars($_POST['confirm'])) : '');
            $new_fields['comment'] = (array_key_exists('comment', $_POST) ? trim(htmlspecialchars($_POST['comment'])) : '');

            if (!empty($_POST['new_pass']))
            {
                $new_fields['password'] = md5($_POST['new_pass']);
            }
        } else {
            $new_fields['confirm'] = '0';
        }

        $wpdb->update( $table_name, $new_fields, array('uniqid' => $uniqid) );
        if ($_POST['confirm'] === '1'){
            regForResume();
        }else{
            wp_redirect( add_query_arg('id', $uniqid, get_permalink(7633)) );
        }
    }
}
add_action( 'admin_post_nopriv_form_to_update_resume', 'prefix_update_resume', 10, 0 );
add_action( 'admin_post_form_to_update_resume'       , 'prefix_update_resume', 10, 0 );



function prefix_interview() {
    if ( wp_verify_nonce($_POST['verification'],'I_am_a_student_ITEA!') )
    {
        global $wpdb;
        $table_name = $wpdb->get_blog_prefix() . 'interview';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "CREATE TABLE {$table_name} (
                id int(11) unsigned NOT NULL AUTO_INCREMENT,
                date_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                id_course int(8) unsigned NOT NULL,
                name_inst      varchar(255) DEFAULT NULL,
                course_date    varchar(255) DEFAULT NULL,
                name_stud      varchar(255) DEFAULT NULL,
                phone          varchar(255) DEFAULT NULL,
                date_of_birth  varchar(255) DEFAULT NULL,
                email          varchar(255) DEFAULT NULL,
                actual_courses varchar(255) DEFAULT NULL,
                marks          varchar(255) DEFAULT NULL,
                job_search  tinyint(1)      DEFAULT NULL,
                enough_info int(1) unsigned DEFAULT NULL,
                delivery    tinyint(1)      DEFAULT NULL,
                mentoring   tinyint(1)      DEFAULT NULL,
                comment     text            DEFAULT NULL,
                PRIMARY KEY (id)
        ) {$charset_collate};";

        dbDelta($sql);

        $id_course      = (array_key_exists('id_course', $_POST) ? trim(htmlspecialchars($_POST['id_course'])) : '');
        $name_inst      = (array_key_exists('name_inst', $_POST) ? trim(htmlspecialchars($_POST['name_inst'])) : '');
        $course_date    = '';
        $name_stud      = (array_key_exists('name_stud', $_POST) ? trim(htmlspecialchars($_POST['name_stud'])) : '');
        $phone          = (array_key_exists('phone'    , $_POST) ? trim(htmlspecialchars($_POST['phone'])) : '');
        $date_of_birth  = (array_key_exists('date_of_birth' , $_POST) ? trim(htmlspecialchars($_POST['date_of_birth'])) : '');
        $email          = (array_key_exists('email'         , $_POST) ? trim(htmlspecialchars($_POST['email'])) : '');
        $marks          = (array_key_exists('marks_stars'   , $_POST) ? (gettype($_POST['marks_stars']) === 'array' ? json_encode($_POST['marks_stars']) : '') : '');
        $actual_courses = (array_key_exists('actual_courses', $_POST) ? trim(htmlspecialchars($_POST['actual_courses'])) : '');
        $job_search     = '';
        $enough_info    = '';
        $delivery       = '';
        $mentoring      = '';
        $comment        = (array_key_exists('comment', $_POST) ? trim(htmlspecialchars($_POST['comment'])) : '');
        if (!empty($_POST['comment-step-3'])) {
            $comment .= " \n " . trim(htmlspecialchars($_POST['comment-step-3']));
        }
        if (!empty($_POST['comment-step-4'])) {
            $comment .= " \n " . trim(htmlspecialchars($_POST['comment-step-4']));
        }

        if(array_key_exists('course_date1', $_POST)){
            $course_date = trim(htmlspecialchars($_POST['course_date1']));
            $course_date .= (array_key_exists('course_date2', $_POST) ? (empty($_POST['course_date2']) ? '' : ' - '.trim(htmlspecialchars($_POST['course_date2']))) : '');
        }

        if(array_key_exists('job_search', $_POST)){
            switch($_POST['job_search']){
                case 'no':
                    $job_search = 0;
                    break;
                case 'yes':
                    $job_search = 1;
            }
        }

        if(array_key_exists('enough_info', $_POST)){
            switch($_POST['enough_info']){
                case 'no':
                    $enough_info = 0;
                    break;
                case 'yes':
                    $enough_info = 1;
                    break;
                case 'not-go':
                    $enough_info = 2;
            }
        }

        if(array_key_exists('delivery', $_POST)){
            switch($_POST['delivery']){
                case 'no':
                    $delivery = 0;
                    break;
                case 'yes':
                    $delivery = 1;
            }
        }

        if(array_key_exists('mentoring', $_POST)){
            switch($_POST['mentoring']){
                case 'no':
                    $mentoring = 0;
                    break;
                case 'yes':
                    $mentoring = 1;
            }
        }

        $wpdb->insert( $table_name,
            array( 'id_course'       => $id_course,
                    'name_inst'      => $name_inst,
                    'course_date'    => $course_date,
                    'name_stud'      => $name_stud,
                    'phone'          => $phone,
                    'date_of_birth'  => $date_of_birth,
                    'email'          => $email,
                    'actual_courses' => $actual_courses,
                    'marks'          => $marks,
                    'job_search'     => $job_search,
                    'enough_info'    => $enough_info,
                    'delivery'       => $delivery,
                    'mentoring'      => $mentoring,
                    'comment'        => $comment
            )
        );

        wp_redirect( get_permalink(9176) ); exit;
    }
}
add_action( 'admin_post_nopriv_interview', 'prefix_interview', 10, 0 );
add_action( 'admin_post_interview'       , 'prefix_interview', 10, 0 );



require_once('Utils/Generation_uuid.php');
require_once('Controllers/OrdersController.php');
