<?php /* Template Name: Расписание вечерних курсов */

$lang = (get_locale() == 'ru_RU');

get_header();
?>

<div class="head-section head-section__schedule">
  <div class="container">
    <div class="head-section__left">
      <h1><?php echo get_the_title(); ?></h1>
    </div>
    <div class="head-section__right">
      <div class="head-section__right-item">
        <span class="start-courses">&#8211; <?php echo ($lang ? 'дата старта может меняться' : 'дата старту може змінюватися'); ?></span>
        <span class="garant-courses">&#8211; <?php echo ($lang ? 'старт курса гарантирован' : 'старт курсу гарантований'); ?></span>
        <span class="soon-courses">&#8211; <?php echo ($lang ? 'для старта не хватает 2-3 человека' : 'для старту не вистачає 2-3 людини'); ?></span>
      </div>
    </div>
  </div>
</div>

<?php
$Uuid_filiation = 'e7f33e0e-9605-4f0b-8ed3-7de8cde053b7';
$year  = (int) date('y');
$month = (int) date('m');
$day   = (int) date('d');
if($lang) {
    $all_months = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
} else {
    $all_months = array('Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень');
}
$m1 = $all_months[$month-1];
$m2 = ($month   > 11) ? $all_months[$month-12] : $all_months[$month];
$m3 = ($month+1 > 11) ? $all_months[$month-11] : $all_months[$month+1];
$m4 = ($month+2 > 11) ? $all_months[$month-10] : $all_months[$month+2];

$word1 = ($lang ? 'Длительность' : 'Тривалість');
$word2 = ($lang ? 'Стоимость курса' : 'Вартість курсу');

$categories = getListRoadmaps();
?>

<div class="container" id="flip-scroll">
    <div class="col-md-12 b-schedule-courses-table">
        <ul class="b-inforow">

            <li class="b-inforow-heading">
                <div class="col-sm-4">
                    <p class="b-inforow-heading-item b-inforow-heading-first-item">
                        <span><?php echo ($lang ? 'Название курса' : 'Назва курсу'); ?></span>
                    </p>
                </div>
                <div class="col-sm-2">
                    <p class="b-inforow-heading-item">
                        <span><?php echo $word1; ?></span>
                    </p>
                </div>
                <div class="col-sm-2">
                    <p class="b-inforow-heading-item">
                        <span><?php echo $word2; ?></span>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-heading-item">
                        <span><?php echo $m1; ?></span>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-heading-item">
                        <span><?php echo $m2; ?></span>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-heading-item">
                        <span><?php echo $m3; ?></span>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-heading-item">
                        <span><?php echo $m4; ?></span>
                    </p>
                </div>
            </li>

<?php
foreach ($categories as $category) {
    $cat_data_ru = get_option('category_' . pll_get_term($category->cat_ID, 'ru'));
    if (!is_array($cat_data_ru)) {
        $cat_data_ru = array();
    }
    $minimized = array_key_exists('roadmap_type', $cat_data_ru) ? $cat_data_ru['roadmap_type'] == 'minimized' : false;
    $word3 = $minimized ? ($lang ? ' мес.' : ' міс.') : ($lang ? ' ч.' : ' год.');

    echo '<li class="b-inforow-title"><a href="'.get_category_link($category->term_id).'">'.$category->cat_name.'</a></li>';

    $courses = get_posts(array('category' => $category->cat_ID, 'numberposts' => -1));
    $courses = array_reverse( $courses );

    foreach($courses as $course) {
        $course_price = get_post_meta(pll_get_post($course->ID, 'ru'), 'cost', true);
        $di = get_post_meta(pll_get_post($course->ID, 'ru'), 'discont', true);
        $uuid = get_post_meta(pll_get_post($course->ID, 'ru'), 'uuid_for_itea_crm', true);
        $price_val = (int)$course_price;
        if ($di > 0 && $course_price > 0) {
            $course_price = '<span>' . priceThousend(nicePrice($course_price)) . '</span>';
            $price_val = priceThousend(nicePrice(ceil($price_val * (100 - $di) / 100)));
            $course_price .= '<span>' . $price_val . '</span> UZS';
        } else {
            $di = false;
            if (0 == $course_price) {
                $course_price = '<b>Free</b>';
            } else {
                $course_price = '<span>' . priceThousend(nicePrice($course_price)) . ' UZS</span>';
            }
        }

        $all_dates = array();
        $all_dates[0] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date1', true);
        $all_dates[1] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date2', true);
        $all_dates[2] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date3', true);
        $all_dates[3] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date4', true);
        $all_dates[4] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date5', true);
        $all_dates[5] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date6', true);

        $dates_bg_color = array();
        $dates_bg_color[0] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date1-bg-color', true);
        $dates_bg_color[1] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date2-bg-color', true);
        $dates_bg_color[2] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date3-bg-color', true);
        $dates_bg_color[3] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date4-bg-color', true);
        $dates_bg_color[4] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date5-bg-color', true);
        $dates_bg_color[5] = get_post_meta(pll_get_post($course->ID, 'ru'), 'date6-bg-color', true);

//      $all_dates = array();
//      $dates_bg_color = array();
//      $id = $course->ID;
////                  $Uuid_filiation = '788d3b28-a67c-4c55-ab96-69c3d8c69f4d';
//
//      $result = $wpdb->get_results("
//                      SELECT *
//                      FROM  $wpdb->postmeta
//                          WHERE post_id = $id AND meta_filiation = '$Uuid_filiation';
//                  ");
//
//      foreach($result as $k => $item) {
//        if ($item -> meta_filiation === $Uuid_filiation) {
//          switch ($item -> meta_key) {
//            case 'date1':
//            case 'date2':
//            case 'date3':
//            case 'date4':
//              $all_dates[] = $item -> meta_value;
//              break;
//          }
//        } else {
//          $all_dates[] = '';
//        }
//
//        if ($item -> meta_filiation === $Uuid_filiation) {
//          switch ($item -> meta_key) {
//            case 'date1-bg-color':
//            case 'date2-bg-color':
//            case 'date3-bg-color':
//            case 'date4-bg-color':
//              $dates_bg_color[] = $item -> meta_value;
//              break;
//          }
//        } else {
//          $dates_bg_color[] = '';
//        }
//      }

        $months = array_fill(0, 4, '');
        for ($i = 0; $i < sizeof($all_dates); $i++) {
            if ( time() > strtotime($all_dates[$i] . ' 19:00') ) { continue; }
            $mo_m = explode('.', $all_dates[$i]);

            if ((int)substr($mo_m[2], -2) > $year) {
                $dif = 12 - $month + $mo_m[1];
                $months[$dif] .= (empty($dates_bg_color[$i]) ? '<span>' : '<span style="background-color:#'.$dates_bg_color[$i].';">') . $all_dates[$i] . '</span>';
            } elseif ($mo_m[1] > $month || ($mo_m[1] == $month && $mo_m[0] + 5 > $day)) {
                $dif = $mo_m[1] - $month;
                $months[$dif] .= (empty($dates_bg_color[$i]) ? '<span>' : '<span style="background-color:#'.$dates_bg_color[$i].';">') . $all_dates[$i] . '</span>';
            }
        }
        ?>
            <li class="b-inforow-courses">
                <div class="col-sm-4">
                    <p class="b-inforow-courses-item b-inforow-courses-first-item">
                        <span>
                            <a href="<?php echo get_permalink($course->ID); ?>"><?php echo $course->post_title; ?></a>
                        </span>
                        <?php if($di){ ?>
                            <span class="b-inforow-courses-discounts-icon">
                                <span class="b-inforow-courses-discounts">-<?php echo $di; ?> %</span>
                            </span>
                        <?php } ?>
                    </p>
                </div>

                <div class="col-sm-2">
                    <p class="b-inforow-courses-item ">
                        <span class="b-inforow-courses-item-characteristics"><?php echo $word1; ?></span>
                        <span><?php echo get_post_meta(pll_get_post($course->ID, 'ru') , 'long' , true), $word3; ?></span>
                    </p>
                </div>
                <div class="col-sm-2">
                    <p class="b-inforow-courses-item<?php echo ($di ? ' b-inforow-courses-item-discounts' : ''); ?>">
                        <span class="b-inforow-courses-item-characteristics"><?php echo $word2; ?></span>
                        <?php echo $course_price; ?>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-courses-item">
                        <span class="b-inforow-courses-item-characteristics"><?php echo $m1; ?></span>
                        <span class="b-inforow-courses-date-item<?php echo ($di ? ' b-inforow-courses-date-item-discounts' : ''); ?>">
                            <?= (!empty($uuid) && !empty($months[0])) ? '<span class="label_online">online</span>' : ''; ?>
                            <?php echo $months[0]; ?>
                        </span>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-courses-item">
                        <span class="b-inforow-courses-item-characteristics"><?php echo $m2; ?></span>
                        <span class="b-inforow-courses-date-item<?php echo ($di ? ' b-inforow-courses-date-item-discounts' : ''); ?>">
                            <?= (!empty($uuid) && !empty($months[1])) ? '<span class="label_online">online</span>' : ''; ?>
                            <?php echo $months[1]; ?>
                        </span>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-courses-item">
                        <span class="b-inforow-courses-item-characteristics"><?php echo $m3; ?></span>
                        <span class="b-inforow-courses-date-item<?php echo ($di ? ' b-inforow-courses-date-item-discounts' : ''); ?>">
                            <?= (!empty($uuid) && !empty($months[2])) ? '<span class="label_online">online</span>' : ''; ?>
                            <?php echo $months[2]; ?>
                        </span>
                    </p>
                </div>
                <div class="col-sm-1">
                    <p class="b-inforow-courses-item">
                        <span class="b-inforow-courses-item-characteristics"><?php echo $m4; ?></span>
                        <span class="b-inforow-courses-date-item<?php echo ($di ? ' b-inforow-courses-date-item-discounts' : ''); ?>">
                            <?= (!empty($uuid) && !empty($months[3])) ? '<span class="label_online">online</span>' : ''; ?>
                            <?php echo $months[3]; ?>
                        </span>
                    </p>
                </div>
            </li>
<?php }} ?>

        </ul>
    </div>
</div>

<?php get_footer(); ?>
