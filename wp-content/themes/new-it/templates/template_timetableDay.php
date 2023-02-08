<?php /* Template Name: Расписание дневных курсов */

$lang = (get_locale() == 'ru_RU');

$year  = (int) date('y');
$month = (int) date('m');
$day   = (int) date('d');

if ($lang) {
    $all_months = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
} else {
    $all_months = array('Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень');
}

$m1 = $all_months[$month - 1];
$m2 = ($month > 11) ? $all_months[$month - 12] : $all_months[$month];
$m3 = ($month + 1 > 11) ? $all_months[$month - 11] : $all_months[$month + 1];
$m4 = ($month + 2 > 11) ? $all_months[$month - 10] : $all_months[$month + 2];
$m5 = ($month + 3 > 11) ? $all_months[$month - 9]  : $all_months[$month + 3];
$m6 = ($month + 4 > 11) ? $all_months[$month - 8]  : $all_months[$month + 4];

$hid = !is_user_logged_in();
$categories = ($lang ? get_categories('parent=23&orderby=ID') : get_categories('parent=296&orderby=ID'));



/**
 * @param $cat_id
 * @param string $cssClass1
 * @param string $cssClass2
 * @return string
 */
function printCoursesCategory($cat_id, $cssClass1, $cssClass2 = '') {
    global $hid;
    global $year;
    global $month;
    global $day;

    $result = '';
    $posts  = get_posts(array('category' => $cat_id, 'numberposts' => -1, 'order' => 'ASC'));

    foreach ($posts as $post) {

        $all_dates = array();
        $all_dates[0] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date1', true);
        $all_dates[1] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date2', true);
        $all_dates[2] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date3', true);
        $all_dates[3] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date4', true);
        $all_dates[4] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date5', true);
        $all_dates[5] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date6', true);

        if ($all_dates[0] == '' && $all_dates[1] == '' && $all_dates[2] == '' && $all_dates[3] == '' && $all_dates[4] == '' && $all_dates[5] == '' && $hid) {
            continue;
        }

        $dates_bg_color = array();
        $dates_bg_color[0] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date1-bg-color', true);
        $dates_bg_color[1] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date2-bg-color', true);
        $dates_bg_color[2] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date3-bg-color', true);
        $dates_bg_color[3] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date4-bg-color', true);
        $dates_bg_color[4] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date5-bg-color', true);
        $dates_bg_color[5] = get_post_meta(pll_get_post($post->ID, 'ru'), 'date6-bg-color', true);

        $months    = array();
        $ng = '';
        for ($i = 0; $i < sizeof($all_dates); $i++) {

            $text = '';
            $temp = strpos($all_dates[$i], '-');
            if ($temp !== false) {
                $text = trim(mb_substr($all_dates[$i], $temp + 1));
                $all_dates[$i] = trim(mb_substr($all_dates[$i], 0, $temp));
            }

            $mo_m = explode('.', $all_dates[$i]);
            $dif = null;
            if(isset($mo_m[2]) && isset($mo_m[1])){
                if ((int)substr($mo_m[2], -2) > $year) {
                    $dif = 12 - $month + $mo_m[1];
                } elseif ($mo_m[1] > $month || ($mo_m[1] == $month && $mo_m[0] + 1 > $day)) {
                    $dif = $mo_m[1] - $month;
                } else {
                    continue;
                }
                $months[$dif] .= (empty($dates_bg_color[$i]) ? '<span>' : '<span style="background-color:#'.$dates_bg_color[$i].';">') . $all_dates[$i] . '</span>';
                $months[$dif] .= (empty($text) ? '' : (empty($dates_bg_color[$i]) ? '<span class="date-text">' : '<span class="date-text" style="background-color:#'.$dates_bg_color[$i].';">') . $text . '</span>');
            }
            if(!empty($dates_bg_color[$i])){$ng='has-g';}
        }

        if (sizeof($months) == 0 && $hid) {
            continue;
        }
        $uuid = get_post_meta(pll_get_post($post->ID, 'ru'), 'uuid_for_itea_crm', true);

        $result .= "<tr class=\"clFix $cssClass1 $cssClass2 $ng\">";
        $result .= '<td class="cent"><span>' .get_post_meta(pll_get_post($post->ID, 'ru'), 'code', true). '</span></td>';
        $result .= '<td><a href="' .get_permalink($post->ID). '">' .$post->post_title. '</a></td>';
        $result .= '<td class="cent"><span>' .get_post_meta($post->ID, 'long', true). '</span></td>';
        $result .= '<td class="cent cost"><span>' .get_post_meta(pll_get_post($post->ID, 'ru'), 'cost', true) . (get_post_meta(pll_get_post($post->ID, 'ru'), 'currency', true) ? ' $' : ' UZS'). '</span></td>';
        $result .= '<td class="cent"> <span>' . (isset($months[0]) ? (isset($uuid) && !empty($uuid) ? '<span class="label_online">online</span>' : '') . $months[0] : '') . '</span> </td>';
        $result .= '<td class="cent"> <span>' . (isset($months[1]) ? (isset($uuid) && !empty($uuid) ? '<span class="label_online">online</span>' : '') . $months[1] : '') . '</span> </td>';
        $result .= '<td class="cent"> <span>' . (isset($months[2]) ? (isset($uuid) && !empty($uuid) ? '<span class="label_online">online</span>' : '') . $months[2] : '') . '</span> </td>';
        $result .= '<td class="cent"> <span>' . (isset($months[3]) ? (isset($uuid) && !empty($uuid) ? '<span class="label_online">online</span>' : '') . $months[3] : '') . '</span> </td>';
        $result .= '<td class="cent"> <span>' . (isset($months[4]) ? (isset($uuid) && !empty($uuid) ? '<span class="label_online">online</span>' : '') . $months[4] : '') . '</span> </td>';
        $result .= '<td class="cent"> <span>' . (isset($months[5]) ? (isset($uuid) && !empty($uuid) ? '<span class="label_online">online</span>' : '') . $months[5] : '') . '</span> </td>';
        $result .= '</tr>';
    }

    return $result;
}
/**
 * END function printCoursesCategory
 */



$all_colors  = ['passion', 'blue', 'green', 'orange', 'lblue', 'purl', 'yellow', 'pink', 'hucki', 'aqua', 'passion'];
$dropdown1_li = '';
$dropdown2_li = '';
$schedule_table = '';

$all_slugs = '';

foreach ($categories as $category) {

    $all_slugs .= ' '.$category->slug;
    $dropdown1_li .= "<li data-filter=\".{$category->slug}\"><a href=\"#\">{$category->name}</a></li>";

    $color = array_shift($all_colors);
    $schedule_table .= "<tr class=\"no {$category->slug} {$color} parent-category\">";
    $schedule_table .= '<th colspan="10" class="cent main"><a href="' .get_category_link($category->term_id). '">' .$category->cat_name. '</a></th>';
    $schedule_table .= '</tr>';

    $cat_2_level = get_categories("parent=$category->cat_ID&orderby=ID");

    if (empty($cat_2_level)) {

        $schedule_table .= printCoursesCategory($category->cat_ID, $category->slug);

    } else {
        $dropdown2_li.="<li class='store-category {$category->slug}'>{$category->name}</li>";
        foreach ($cat_2_level as $cat) {

            $all_slugs .= ' '.$cat->slug;
            $dropdown2_li .= "<li class=\"{$category->slug}\" data-filter=\".{$cat->slug}\"><a href=\"#\">{$cat->cat_name}</a></li>";

            $schedule_table .= "<tr class=\"no {$category->slug} {$cat->slug} child-category\">";
            $schedule_table .= '<th colspan="10"><a href="' .get_category_link($cat->term_id). '">' .$cat->cat_name. '</a></th>';
            $schedule_table .= '</tr>';
            $schedule_table .= printCoursesCategory($cat->cat_ID, $category->slug, $cat->slug);
        }
    }
}



get_header();
?>

    <script src="<?php bloginfo('template_directory'); ?>/relize/js/jquery-scrolltofixed.js" type="text/javascript"></script>
    <script type="text/javascript">
        function DropDown(el) {
            this.dd = el;
            this.initEvents();
        }

        DropDown.prototype = {
            initEvents: function () {
                var obj = this;

                // obj.dd.on('click', function (event) {
                //     $(this).toggleClass('active');
                //     event.stopPropagation();
                // });
                //
                // obj.dd.children().each(function () {
                //     var self = $(this);
                //     self.on('click', function () {
                //         self.parent().removeClass('active');
                //     });
                // });
            }
        };

        $(function () {
            var dd = new DropDown($('#dd'));
            var dd1 = new DropDown($('#dd1'));

            $(document).click(function () {
                // all dropdowns
                $('.wrapper-dropdown-2').removeClass('active');
            });
        });
    </script>


    <div class="head-section">
        <div class="container">
            <a class="linkCourseToT" href="<?php echo get_category_link(($lang ? 23 : 296)); ?>">
                <img src="<?php bloginfo('template_directory'); ?>/relize/img/back-arrow.svg">
                <?php echo($lang ? 'Курсы по вендорам' : 'Курси по вендорам'); ?>
            </a>
            <h1><?php the_title(); ?></h1>
            <?php /* ?><a class="timetable-link-to-other-courses"
           href="<?php echo get_permalink(($lang ? 17 : 7863)); ?>"><?php echo get_the_title(($lang ? 17 : 7863)); ?></a><?php /**/ ?>
        </div>
    </div>

    <div class="container" id="flip-scroll">

        <div class="filters-courses">
            <div class="filters-select">
                <div id="dd" class="wrapper-dropdown-2 filter3" tabindex="1">
                    <div><span class="all-vendors"><?php echo($lang ? 'Все вендоры' : 'Всі вендори'); ?></span><ul class="chosen-vendors"></ul></div>
                    <div class="nano">
                        <ul class="dropdown nano-content">
                            <li class="current" data-filter="*">
                                <a href="#"><?php echo($lang ? 'Все вендоры' : 'Всі вендори'); ?></a>
                            </li>
                            <?php echo $dropdown1_li; ?>
                        </ul>
                    </div>
                </div>

                <div id="dd1" class="wrapper-dropdown-2 notactive" tabindex="1">
                    <div><span class="all-technology"><?php echo($lang ? 'Все технологии' : 'Всі технології'); ?></span><ul class="chosen-vendors chosen-technology"></ul></div>
                    <div class="nano">
                        <ul class="dropdown nano-content">
                            <?php echo $dropdown2_li; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="filters-radio">
                <div class="garanted">
                    <label><input type="radio" name="f-course" value="hide" checked="checked"><span class="circle"></span><span class="text"><?php echo($lang ? 'Только гарантированные курсы' : 'Тільки гарантовані курси'); ?></span></label>
                </div>
                <div class="all-c">
                    <label><input type="radio" name="f-course"><span class="circle"></span><span class="text"><?php echo($lang ? 'Все курсы' : 'Всі курси'); ?><span class="info"></span></span></label>
                    <div class="legend">
                        <div class="legend-item"><span class="indicator" style="background-color: #ffffff;"></span><span class="text">- <?php echo($lang ? 'дата старта курса может меняться' : 'дата старту курсу може змінюватися'); ?></span></div>
                        <div class="legend-item"><span class="indicator" style="background-color: #95c5a9;"></span><span class="text">- <?php echo($lang ? 'курс гарантирован 100%' : 'курс гарантований 100%'); ?></span></div>
                        <div class="legend-item"><span class="indicator" style="background-color: #eee888;"></span><span class="text">- <?php echo($lang ? 'для старта курса не хватает еще одного человека' : 'для старту курсу не вистачає ще однієї людини'); ?></span></div>
                    </div>
                </div>
            </div>
        </div>
        <table class="timeTableCourses dayCourse">
            <tr class="infoRow <?php echo $all_slugs; ?> has-g">
                <td><?php echo ($lang ? 'Код курса' : 'Код курсу'); ?></td>
                <td class="lef"><?php echo ($lang ? 'Название курса' : 'Назва курсу'); ?></td>
                <td><?php echo ($lang ? 'Длительность' : 'Тривалість'); ?></td>
                <td><?php echo ($lang ? 'Цена без НДС' : 'Ціна без ПДВ'); ?></td>
                <td><?php echo $m1; ?></td>
                <td><?php echo $m2; ?></td>
                <td><?php echo $m3; ?></td>
                <td><?php echo $m4; ?></td>
                <td><?php echo $m5; ?></td>
                <td><?php echo $m6; ?></td>
            </tr>
            <tr class="prld-tr"><th colspan="10" align="center" style="text-align: center;"><img src="/wp-content/themes/new-it/images/Spinner-1s-200px.svg" alt="spinner-it" width="48" height="48"></th></tr>
            <?php //echo $schedule_table; ?>
        </table>

        <div class="legend lg-under">
            <div class="legend-item"><span class="indicator" style="background-color: #ffffff;"></span><span class="text">- <?php echo($lang ? 'дата старта курса может меняться' : 'дата старту курсу може змінюватися'); ?></span></div>
            <div class="legend-item"><span class="indicator" style="background-color: #95c5a9;"></span><span class="text">- <?php echo($lang ? 'курс гарантирован 100%' : 'курс гарантований 100%'); ?></span></div>
            <div class="legend-item"><span class="indicator" style="background-color: #eee888;"></span><span class="text">- <?php echo($lang ? 'для старта курса не хватает еще одного человека' : 'для старту курсу не вистачає ще однієї людини'); ?></span></div>
        </div>

        <div style="padding:0 20px 20px;color:#133b54;font-size:10px;">* Курсы, которые проводятся не на территории Украины, читаются нашими партнерами.</div>
    </div>


    <!-- Start SiteHeart code -->
    <script>
        var sheduleTable = '<?php echo $schedule_table; ?>';
        $(document).ready(function(){
            setTimeout(function(){
                // $('.timeTableCourses.dayCourse').append(sheduleTable);
                $('.timeTableCourses.dayCourse .prld-tr').remove();
                $('.timeTableCourses.dayCourse tbody').html($('.timeTableCourses.dayCourse tbody').html()+sheduleTable);
                //Select guaranteed course
                var bulo = '';
                $('.timeTableCourses.dayCourse tr.has-g:not(.infoRow)').each(function(){
                    if(bulo!=$(this).attr('class')){
                        var classes = $(this).attr('class').split(' ').map(function(item){return item.trim();}).filter(function(item){return item!=='clFix';}).filter(function(item){return item!=='has-g';});
                        var parent = '';
                        classes.forEach(function(element,index){
                            if(index==0){
                                $('.timeTableCourses.dayCourse tr.no.parent-category.'+element).addClass('has-g');
                                $('#dd1 .dropdown li.store-category.'+element).addClass('has-g');
                                parent='.'+element;
                            }
                            if(index==1 && element!=''){
                                $('.timeTableCourses.dayCourse tr.no.child-category.'+element+parent).addClass('has-g');
                                $('#dd1 .dropdown li[data-filter=".'+element+'"]').addClass('has-g');
                            }
                        });
                        bulo=$(this).attr('class');
                    }
                });
                $('.filter3 .dropdown li.current[data-filter="*"]').click();
            },1000);
        });
        (function () {
            var widget_id = 806115;
            _shcp = [{widget_id: widget_id, side: 'left', position: 'center'}];
            var lang = (navigator.language || navigator.systemLanguage || navigator.userLanguage || "en")
                .substr(0, 2).toLowerCase();
            var url = "widget.siteheart.com/widget/sh/" + widget_id + "/" + lang + "/widget.js";
            var hcc = document.createElement("script");
            hcc.type = "text/javascript";
            hcc.async = true;
            hcc.src = ("https:" == document.location.protocol ? "https" : "http") + "://" + url;
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hcc, s.nextSibling);
        })();
    </script>
    <!-- End SiteHeart code -->

<?php get_footer(); ?>
