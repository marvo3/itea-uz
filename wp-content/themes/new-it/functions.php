<?php
date_default_timezone_set('Europe/Kiev');

// Функция подбора шаблона по ID записи
function mayak_single($mayak_template)
{
    global $wp_query, $post;
    if (file_exists(TEMPLATEPATH . '/single-' . $post->ID . '.php')) {
        return TEMPLATEPATH . '/single-' . $post->ID . '.php';
    }
    if (file_exists(TEMPLATEPATH . '/single.php')) {
        return TEMPLATEPATH . '/single.php';
    }
    return $mayak_template;
}
add_filter('single_template', 'mayak_single');


function stop_authorization()
{
    return 'Неверные имя пользователя или пароль';
}
add_filter('login_errors', 'stop_authorization');


remove_action('wp_head', 'wp_generator');


function security_page_login()
{
    $inquiry = $_SERVER['SERVER_NAME'] . '/wp-login.php?' . $_SERVER['QUERY_STRING'];
    $link = site_url('/wp-login.php');
    if (!(mb_substr($link, mb_strpos($link, '://') + 3) . '?itea' == $inquiry)) {
        wp_redirect(get_home_url(), 301); exit;
    }
}
add_action('login_head', 'security_page_login');


function mytheme_enqueue_scripts()
{
    if (!is_admin()):
        wp_enqueue_script('jquery-ui');
        wp_enqueue_script('jquery-tools');
        wp_enqueue_script('fancyfields');
        wp_enqueue_script('selectBox');
        wp_enqueue_script('jcarousel');
        wp_enqueue_script('jExpand');
        wp_enqueue_script('theme-script'); //script.js
    endif; //!is_admin
}
//add_action('init', 'mytheme_enqueue_scripts');
add_action('wp_print_scripts', 'mytheme_enqueue_scripts');


if (function_exists('add_theme_support')) {
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
}


add_post_type_support('page', 'excerpt');


if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => __('SOC_BOX', wp_title()),
        'id' => 'soc_box_w',
        'description' => __('soc box', wp_title()),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '',
        'after_title' => '',
    ));
}


// get current URL
function get_current_URL()
{
    $current_url  = 'http';
    $server_https = $_SERVER["HTTPS"];
    $server_name  = $_SERVER["SERVER_NAME"];
    $server_port  = $_SERVER["SERVER_PORT"];
    $request_uri  = $_SERVER["REQUEST_URI"];
    if ($server_https == "on")
        $current_url .= "s";
    $current_url .= "://";
    if ($server_port != "80")
        $current_url .= $server_name . ":" . $server_port . $request_uri;
    else
        $current_url .= $server_name . $request_uri;
    return $current_url;
}


function real_date_diff($date1, $date2 = NULL)
{
    $diff = array();

    if (!$date2) {
        $cd = getdate();
        $date2 = $cd['year'] . '-' . $cd['mon'] . '-' . $cd['mday'] . ' ' . $cd['hours'] . ':' . $cd['minutes'] . ':' . $cd['seconds'];
    }

    $pattern = '/(\d+)-(\d+)-(\d+)(\s+(\d+):(\d+):(\d+))?/';
    preg_match($pattern, $date1, $matches);
    $d1 = array((int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[5], (int)$matches[6], (int)$matches[7]);
    preg_match($pattern, $date2, $matches);
    $d2 = array((int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[5], (int)$matches[6], (int)$matches[7]);

    for ($i = 0; $i < count($d2); $i++) {
        //if($d2[$i]>$d1[$i]) break;
        if ($d2[$i] > $d1[$i])
            return false;
        if ($d2[$i] < $d1[$i]) {
            $t = $d1;
            $d1 = $d2;
            $d2 = $t;
            break;
        }
    }

    $md1 = array(31, $d1[0] % 4 || (!($d1[0] % 100) && $d1[0] % 400) ? 28 : 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $md2 = array(31, $d2[0] % 4 || (!($d2[0] % 100) && $d2[0] % 400) ? 28 : 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $min_v = array(NULL, 1, 1, 0, 0, 0);
    $max_v = array(NULL, 12, $d2[1] == 1 ? $md2[11] : $md2[$d2[1] - 2], 23, 59, 59);
    for ($i = 5; $i >= 0; $i--) {
        if ($d2[$i] < $min_v[$i]) {
            $d2[$i - 1]--;
            $d2[$i] = $max_v[$i];
        }
        $diff[$i] = $d2[$i] - $d1[$i];
        if ($diff[$i] < 0) {
            $d2[$i - 1]--;
            $i == 2 ? $diff[$i] += $md1[$d1[1] - 1] : $diff[$i] += $max_v[$i] - $min_v[$i] + 1;
        }
    }

    return $diff;
}


function education_date_format($post_time)
{
    $post_time = real_date_diff($post_time);
    if ($post_time) {
        $time_count = '';
        $years = $post_time[0];
        $month = $post_time[1];
        $days = $post_time[2];
        if ($years && $years % 10 > 4) {
            $time_count .= $years . ' лет ';
        } elseif ($years && $years % 10 <= 4 && $years % 10 >= 1) {
            $time_count .= $years . ' года ';
        } elseif ($years && $years % 10 == 1) {
            $time_count .= $years . ' год ';
        }
        if ($month && $month % 10 > 4) {
            $time_count .= $month . ' месяцев ';
        } elseif ($month && $month % 10 <= 4 && $month % 10 >= 1) {
            $time_count .= $month . ' месяца ';
        } elseif ($month && $month % 10 == 1) {
            $time_count .= $month . ' месяц ';
        }
        if ($days && $days % 10 > 4) {
            $time_count .= $days . ' дней';
        } elseif ($days && $days % 10 <= 4 && $days % 10 >= 1) {
            $time_count .= $days . ' дня';
        } elseif ($days && $days % 10 == 1) {
            $time_count .= $days . ' день';
        }
        return $time_count;
    } else
        return false;
}


function breadcrumb()
{
}
function dimox_breadcrumbs()
{
    /* === ОПЦИИ === */
    $text['home'] = (get_locale() == 'ru_RU' ? 'Главная' : 'Головна'); // текст ссылки "Главная"
    $text['category'] = '%s'; // текст для страницы рубрики
    $text['search'] = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
    $text['tag'] = 'Записи с тегом "%s"'; // текст для страницы тега
    $text['author'] = 'Статьи автора %s'; // текст для страницы автора
    $text['404'] = 'Ошибка 404'; // текст для страницы 404

    $show_current = 1; // 1 - показывать название текущей статьи/страницы/рубрики, 0 - не показывать
    $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
    $show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
    $show_title = 1; // 1 - показывать подсказку (title) для ссылок, 0 - не показывать
    $delimiter = ' &raquo; '; // разделить между "крошками"
    $before = '<span class="current">'; // тег перед текущей "крошкой"
    $after = '</span>'; // тег после текущей "крошки"
    /* === КОНЕЦ ОПЦИЙ === */

    global $post;
    $home_link = home_url('/');
    $link_before = '<span typeof="v:Breadcrumb">';
    $link_after = '</span>';
    $link_attr = ' rel="v:url" property="v:title"';
    $link = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $parent_id = $parent_id_2 = $post->post_parent;
    $frontpage_id = get_option('page_on_front');

    if (is_home() || is_front_page()) {

        if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

    } else {

        echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
        if ($show_home_link == 1) {
            echo '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $text['home'] . '</a>';
            if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
        }

        if (is_category()) {
            $this_cat = get_category(get_query_var('cat'), false);
            if ($this_cat->parent != 0) {
                $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif (is_search()) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;

        } elseif (is_day()) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;

        } elseif (is_month()) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;

        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;

        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_link . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
            } else {
                $all_cat = get_the_category();
                $cat = $all_cat[0];

                if (!empty($_SERVER['HTTP_REFERER'])) {
                    $cat_slug = trim($_SERVER['HTTP_REFERER']);
                    $cat_slug = trim($cat_slug, '/');
                    $cat_slug = substr($cat_slug, strrpos($cat_slug, '/') + 1);
                    foreach ($all_cat as $item) {
                        if ($item->slug == $cat_slug) {
                            $cat = $item;
                            break;
                        }
                    }
                } elseif (!empty($_SERVER['REQUEST_URI'])) {
                    $cat_slug = trim($_SERVER['REQUEST_URI']);
                    $cat_slug = trim($cat_slug, '/');
                    $cat_slug = substr($cat_slug, 0, strrpos($cat_slug, '/'));
                    $cat_slug = substr($cat_slug, strrpos($cat_slug, '/') + 1);
                    foreach ($all_cat as $item) {
                        if ($item->slug == $cat_slug) {
                            $cat = $item;
                            break;
                        }
                    }
                }

                $cats = get_category_parents($cat->term_id, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
                if ($show_current == 1) echo $before . get_the_title() . $after;
            }

        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;

        } elseif (is_attachment()) {
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID);
            $cat = $cat[0];
            if ($cat) {
                $cats = get_category_parents($cat->term_id, TRUE, $delimiter);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif (is_page() && !$parent_id) {
            if ($show_current == 1) echo $before . get_the_title() . $after;

        } elseif (is_page() && $parent_id) {
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs) - 1) echo $delimiter;
                }
            }
            if ($show_current == 1) {
                if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
                echo $before . get_the_title() . $after;
            }

        } elseif (is_tag()) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif (is_404()) {
            echo $before . $text['404'] . $after;

        } elseif (has_post_format() && !is_singular()) {
            echo get_post_format_string(get_post_format());
        }

        if (get_query_var('paged')) {
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ' (';
            echo 'Страница ' . get_query_var('paged');
            if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ')';
        }

        echo '</div><!-- .breadcrumbs -->';

    }
} // end dimox_breadcrumbs


function courseSideBar($course, $isDaytime)
{
    $lang = (get_locale() == 'ru_RU');
    $uuid = get_post_meta(pll_get_post($course->ID, 'ru'), 'uuid_for_itea_crm', true);
    $sideBar = '';

    if ($isDaytime == true) {
        $all_dates = array();
        $all_dates[] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date1', true);
        $all_dates[] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date2', true);
        $all_dates[] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date3', true);
        $all_dates[] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date4', true);
        $all_dates[] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date5', true);
        $all_dates[] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date6', true);

        $all_dates = array_filter($all_dates, function ($date) {
            return !empty($date) && time() < strtotime($date . ' 10:00');
        });
        usort($all_dates, function ($d1, $d2) {
            return strtotime($d1) < strtotime($d2) ? -1 : 1;
        });

        $all_dates_str = '';
        if (!empty($all_dates)) {
            $all_dates_str .= '<tr><th colspan="2">' . ($lang ? 'Ближайшие даты' : 'Найближчі дати') . '</th></tr>';
            $all_dates_str .= '<tr><td colspan="2" style="padding:0 10px;"><ul style="float:left;">';
            for ($i = 0; $i < 3 && $i < sizeof($all_dates); $i++) {
                $all_dates_str .= '<li>' . $all_dates[$i] . (isset($uuid) && !empty($uuid) ? '<span class="label_online">online</span>': '') . '</li>';
            }
            $all_dates_str .= '</ul></td></tr>';
        }

        $typeCurrency = (get_post_meta(pll_get_post($course->ID, 'ru'), 'currency', true)) ? ' $' : ' UZS';

        $sideBar  = '<div class="priceZone"><p class="day borderBR">' . ($lang ? 'Запись на курс' : 'Запис на курс') . '</p>';
        $sideBar .= '<div class="day">';
        $sideBar .= '<table class="day">';
        $sideBar .= '<tr><th>' . ($lang ? 'Код курса' : 'Код курсу') . '</th><td>' . get_post_meta(pll_get_post($course->ID, 'ru'), 'code', true) . '</td></tr>';
        $sideBar .= '<tr><th>' . ($lang ? 'Длительность' : 'Тривалість') . '</th><td>' . get_post_meta($course->ID, 'long', true) . '</td></tr>';
        $sideBar .= '<tr><th>' . ($lang ? 'Код экзамена' : 'Код екзамену') . '</th><td>' . get_post_meta(pll_get_post($course->ID, 'ru'), 'testing', true) . '</td></tr>';
        $sideBar .= '<tr><th>' . ($lang ? 'Стоимость без НДС' : 'Вартість без ПДВ') . '</th><td>' . get_post_meta(pll_get_post($course->ID, 'ru'), 'cost', true) . $typeCurrency . '</td></tr>';
        $sideBar .= $all_dates_str;
        $sideBar .= '</table>';
        $sideBar .= '<div class="contet_box"><form action="' . get_permalink(6872) . '" method="POST" class="sendCourseform">';
        $sideBar .= '<input type="hidden" name="course_id" value="' . $course->ID . '">';
        $sideBar .= '<input type="submit" id="sendCourseToForm" value="' . ($lang ? 'Записаться' : 'Записатись') . '"></form></div></td>';
        $sideBar .= '</div><div style="height: 20px;"></div></div>';
    }

    return $sideBar;
}


function wp_corenavi()
{
    global $wp_query;
    $pages = '';
    $max = $wp_query->max_num_pages;
    if (!$current = get_query_var('paged')) $current = 1;
    $a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
    $a['total'] = $max;
    $a['current'] = $current;

    $total = 1; //1 - выводить текст "Страница N из N", 0 - не выводить
    $a['mid_size'] = 3; //сколько ссылок показывать слева и справа от текущей
    $a['end_size'] = 1; //сколько ссылок показывать в начале и в конце
    $a['prev_text'] = '&laquo;'; //текст ссылки "Предыдущая страница"
    $a['next_text'] = '&raquo;'; //текст ссылки "Следующая страница"

    if ($max > 1) echo '<div class="navigation pos-fix">';
    if ($total == 1 && $max > 1) $pages = '<span class="pages">Страница ' . $current . ' из ' . $max . '</span>' . "\r\n";
    echo $pages . paginate_links($a);
    if ($max > 1) echo '</div>';
}


add_filter('upload_size_limit', 'PBP_increase_upload');
function PBP_increase_upload($bytes)
{
    return 104857600; // 1 megabyte
}


//================================================================================
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
require_once('mr_sam/mr_sam_code.php');

// загружаем css во фронтэнде сайта
//function first_enqueue_style(){
//    wp_enqueue_style(
//        'bootstrap.min',
//        get_template_directory_uri() . '/relize/css/bootstrap.min.css',
//        array(),
//        null,
//        null
//    );
//    wp_enqueue_style(
//        'styles',
//        get_template_directory_uri() . '/relize/css/styles.css',
//        array(),
//        null,
//        null
//    );
//}
//add_action( 'wp_enqueue_scripts', 'first_enqueue_style' );


  add_action('wp_ajax_my_action_callback', 'my_action_callback' );
  add_action('wp_ajax_nopriv_my_action_callback', 'my_action_callback');
  function my_action_callback() {

    $ID = $_POST['courseID'];


    $get_price          = get_post_meta($ID, 'cost', true);
    $get_weeks          = get_post_meta($ID, 'weeks', true);
    $get_discount       = get_post_meta($ID, 'discont', true);


    if ($get_discount !== '') {
      $right_band = nicePrice(ceil($get_price * (100 - $get_discount) / 100));
    } else {
      $right_band = $get_price;
    }
    $old_parts_price = nicePrice(floor($get_price / $get_weeks * 1.15));
    $new_part_price_right = nicePrice(floor($right_band / $get_weeks * 1.15));

    $arr = array(
      'ID'   => $ID,
      'old_price'   => priceThousend($get_price),
      'price_right' => priceThousend($right_band),
      'discont'     => $get_discount,
      'weeks'         => $get_weeks,
      'old_part_price'        => priceThousend($old_parts_price),
      'new_part_price_right'  => priceThousend($new_part_price_right),
    );
    echo json_encode($arr);

    die();
  }




//new template
pll_register_string ("New Template","Показать весь текст","New Template");
pll_register_string ("New Template","ВРЕМЯ","New Template");
pll_register_string ("New Template","ЦЕНА","New Template");
pll_register_string ("New Template","Для юр. лиц цена указана без НДС","New Template");
pll_register_string ("New Template","Записаться на курс","New Template");
pll_register_string ("New Template","О курсе","New Template");
pll_register_string ("New Template","Чему вы научитесь?","New Template");
pll_register_string ("New Template","Чего не будет","New Template");
pll_register_string ("New Template","Теории без практики","New Template");
pll_register_string ("New Template","Устаревших механик работы","New Template");
pll_register_string ("New Template","Пересказа чужих лекций и книг","New Template");
pll_register_string ("New Template","Вопросов без ответа","New Template");
pll_register_string ("New Template","В рамках курса вы будете работать в группах над реальными проектами.<br>Это будет увлекательно и эффективно!","New Template");
pll_register_string ("New Template","Записаться на курс","New Template");
pll_register_string ("New Template","Данный курс есть частью является частью программы","New Template");
pll_register_string ("New Template","Программа обучения","New Template");
pll_register_string ("New Template","Преподаватели","New Template");
pll_register_string ("New Template","Сделайте первые шаги в обучении под руководством практикующих специалистов!","New Template");
pll_register_string ("New Template","Трудоустройство","New Template");
pll_register_string ("New Template","Помогаем в трудоустройстве после прохождения комплексной программы обучения","New Template");
pll_register_string ("New Template","Поможем составить резюме и проверим результат","New Template");
pll_register_string ("New Template","Подберем вакансии в партнерских компаниях и рекомендуем ваc","New Template");
pll_register_string ("New Template","Предоставим поддержку и консультации при прохождении собеседований","New Template");
pll_register_string ("New Template","Что говорят наши выпускники","New Template");
pll_register_string ("New Template","Отзывы с ресурса","New Template");
pll_register_string ("New Template","Часто задаваемые вопросы","New Template");
pll_register_string ("New Template","Для кого данный курс","New Template");
pll_register_string ("New Template","Требования к студентам","New Template");
pll_register_string ("New Template","Показать всю программу","New Template");
pll_register_string ("New Template","Оплата частями:","New Template");
pll_register_string ("New Template","Не предусмотрено","New Template");
pll_register_string ("New Template","Что включает курс?","New Template");
pll_register_string ("New Template","с 19:00 до 22:00","New Template");
pll_register_string ("New Template","по 2-3 раза в неделю","New Template");
pll_register_string ("New Template","Успей забронировать свое место в группе","New Template");
pll_register_string ("New Template","Дату уточните у администрации","New Template");
pll_register_string ("New Template","Что говорят наши работодатели","New Template");
pll_register_string ("New Template","Отзывы о работе Карьерного центра ITEA","New Template");

pll_register_string ("New Template","Требования к студентам","Form");
pll_register_string ("New Template","Выбери удобный формат","Form");
pll_register_string ("New Template","Офлайн","Form");
pll_register_string ("New Template","Онлайн","Form");
pll_register_string ("New Template","Ваше имя","Form");
pll_register_string ("New Template","Ваш E-mail","Form");
pll_register_string ("New Template","Ваш номер телефона","Form");
pll_register_string ("New Template","Выберите локацию","Form");
pll_register_string ("New Template","Подписанием и отправкой этой заявки я подтверждаю, что я ознакомлен с Политикой конфиденциальности и принимаю её условия, включая регламентирующие обработку моих персональных данных, и согласен с ней. Я даю своё согласие на обработку персональных данных в соответствии с данной Политикой конфиденциальности","Form");
pll_register_string ("New Template","Стоимость:","Form");
pll_register_string ("New Template","Что включает офлайн формат?","Form");
pll_register_string ("New Template","Занятия в одном из учебных центров на м.Позняки или м.Берестейская","Form");
pll_register_string ("New Template","Готовый проект по окончании курса","Form");
pll_register_string ("New Template","Помощь в трудоустройстве","Form");
pll_register_string ("New Template","Сертификат об окончании курса","Form");
pll_register_string ("New Template","Что включает онлайн формат?","Form");
pll_register_string ("New Template","Обучение в формате просмотра стрима или записи занятий","Form");
pll_register_string ("New Template","Помощь ментора курса","Form");
pll_register_string ("New Template","Забронировать место","Form");
pll_register_string ("New Template","Личный кабинет с доступом к записи уроков","Form");
pll_register_string ("New Template","Берестейская","Form");
pll_register_string ("New Template","Позняки","Form");


pll_register_string ("New Template","Мы предоставляем нашим студентам возможность трудоустройства в компании-партнеры по их запросу. Также ITEA активно сотрудничает с платформой <a class='link link--blue' href='https://jungo.dev/'>Jungo</a>, которая помогает Junior-специалистам найти работу.Благодаря этому Вы получаете:","New Template");
pll_register_string ("New Template","Карьерную консультацию","New Template");
pll_register_string ("New Template","Помощь в создании перспективного резюме","New Template");
pll_register_string ("New Template","Доступ к рекомендательной системе повышения квалификации, которая поможет адаптировать ваши навыки под современный IT-рынок","New Template");
pll_register_string ("New Template","Первый опыт работы на стажировке/фрилансе/аутстаффинге, в том числе в и на зарубежных рынках","New Template");
