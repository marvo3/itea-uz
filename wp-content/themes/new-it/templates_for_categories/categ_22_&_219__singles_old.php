<style>
    .course-content-block .course__details-info {
        padding-left: 40px;
    }

    .course-content-block .course__start-title:before {
        left: -40px;
    }

    .course-content-block .course__bank-data.course__bank-data--empty {
        font-size: 12px;
    }

    .course__bank > div {
        box-sizing: border-box;
        padding-right: 5px;
        width: 50%;
    }

    @media (max-width: 430px) {
        .course__bank > div {
            padding: 0;
            width: 100%;
        }

        .course__bank > div + div {
            margin-top: 15px;
        }

        .course-content-block .course__bank {
            flex-wrap: wrap;
        }
    }
</style>

<section class="course-content-block">
    <div class="container">
        <div class="main__course-wrapper">
            <div class="course__wrapper-header">
                <div class="course__title">
                    <h1><?php the_title(); ?></h1>
                </div>
                <div class="course__details-info">
                    <div class="course__start-title">
                        <?php echo $lang ? 'Старт обучения' : 'Старт навчання' ?>
                    </div>
                    <div class="course__bank">
            <span class="course__bank-data">
            <?php
            if ($all_dates_filiation_1[0] == null) {
                echo ($lang ? 'Дату уточните у администрации' : 'Старт курсу запитуйте в адміністрації');
            } else {
                echo $all_dates_filiation_1[0]. " (online)";
            }
            ?>
            </span>
                    </div>
                </div>
                <div class="course__during-time">
                    <?php echo get_post_meta($post->ID, 'long', true); echo ($lang ? ' час.' : ' год.');?>
                    <span class="course__during-week">по 2-3 раза в неделю</span>
                </div>
            </div>
        </div>
        <div class="sidebar-wrapper">
            <div class="course__sidebar">
                <?php
                if (1 == count($parent_cat = get_the_category())) {
                    $road_id = $parent_cat[0]->term_id;
                    $cat_data_ru = get_option('category_' . pll_get_term($road_id, 'ru'));
                    $minimized = array_key_exists('roadmap_type', $cat_data_ru) ? $cat_data_ru['roadmap_type'] == 'minimized' : false;
                } else {
                    $minimized = false;
                }
                $price = get_post_meta(pll_get_post($post->ID, 'ru'), 'cost', true);
                $currentPrice   = get_post_meta(pll_get_post($post->ID, 'ru'), 'cost'   , true);
                $dis = get_post_meta(pll_get_post($post->ID, 'ru'), 'discont', true);
                $isMonth = get_post_meta(pll_get_post($post->ID, 'ru'), 'ismonth', true);
                $weeks = get_post_meta(pll_get_post($post->ID, 'ru'), 'weeks', true);

                ?>

                <div style="display: none !important;">
                    <div id="location-price" data-price="<?php echo $price ?>"></div>
                    <div id="location-weeks" data-weeks="<?php echo $weeks ?>"></div>
                    <div id="location-part-weeks"
                         data-part-weeks="<?php echo $partsPrice = nicePrice(floor($price / $weeks * 1.15)); ?>"></div>
                </div>

                <p class="course__sidebar-header"><?php echo $lang ? 'Запись на курс' : 'Запис на курс'; ?></p>

                <?php
                if ($isMonth) {
                ?>
                <div class="course month-group">
                    <?php } else { ?>
                    <div class="course">
                        <?php }

                        if (!empty($dis) || !empty($dis_left)) {
                            $oldPrice = priceThousend(nicePrice($currentPrice));
                            $price    = priceThousend(nicePrice(ceil($currentPrice * (100 - $dis) / 100)));
                            $partsPrice = nicePrice(ceil($currentPrice * (100 - $dis) / 100));
                            $oldPartsPrice = priceThousend(nicePrice(ceil($currentPrice / $weeks * 1.15)));
                            $partsPrice    = priceThousend(nicePrice(ceil($partsPrice / $weeks * 1.15)));
                            ?>

                            <div class="fullPay">
                                <p><?php echo $lang ? 'Единоразовая оплата' : 'Одноразова оплата' ?></p>
                                <div class="fullPay-old-price" data-right-price="<?php echo $oldPrice ?>">
                                    <span class="fullPay-disc"><?php echo $oldPrice ?></span>
                                    <span class="fullPay-price"><?php echo $price ?></span>
                                    UZS. <?php echo $minimized ? ($lang ? '/мес.' : '/міс.') : '' ?>
                                </div>
                            </div>
                            <?php
                            if (!$minimized) { ?>
                                <div class="partlyPay">
                                    <p><?php echo $lang ? 'Оплата частями' : 'Оплата частинами' ?></p>
                                    <?php if (empty($weeks)) { ?>
                                        <span><?php echo $lang ? 'Не предусмотрено' : 'Не передбачено' ?></span>
                                    <?php } else { ?>
                                        <div class="partlyPay-old-part-price" data-patrs-right-price=" <?php echo $oldPartsPrice ?>">
                                            <span class="partlyPay-dis"><?php echo $oldPartsPrice ?></span>
                                            <span class="partlyPay-partPrice"><?php echo $partsPrice ?></span> UZS. <span
                                                    class="partlyPay-x">x</span><span class="partlyPay-weeks"><?php echo $weeks ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                            }
                        } else {
                            $oldPrice = priceThousend(nicePrice($currentPrice));
                            $price    = priceThousend(nicePrice(ceil($currentPrice * (100 - $dis) / 100)));
                            $partsPrice = nicePrice(ceil($currentPrice * (100 - $dis) / 100));
                            $partsPrice = priceThousend(nicePrice(floor($partsPrice / $weeks * 1.15)));
                            ?>
                            <div class="fullPay">
                                <p><?php echo $lang ? 'Единоразовая оплата' : 'Одноразова оплата' ?></p>

                                <div class="fullPay-old-price">
                                    <span class="fullPay-price"><?php echo $price ?></span>
                                    UZS. <?php echo $minimized ? ($lang ? '/мес.' : '/міс.') : '' ?>
                                </div>
                            </div>
                            <?php
                            if (!$minimized) { ?>
                                <div class="partlyPay">
                                    <p><?php echo $lang ? 'Оплата частями' : 'Оплата частинами' ?></p>
                                    <?php
                                    if (empty($weeks)) { ?>
                                        <span><?php echo $lang ? 'Не предусмотрено' : 'Не передбачено' ?></span>
                                    <?php } else { ?>

                                        <div class="partlyPay-old-part-price">
                                            <span class="partlyPay-partPrice"><?php echo $partsPrice ?></span> UZS. <span
                                                    class="partlyPay-x">x</span><span class="partlyPay-weeks"><?php echo $weeks ?></span>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                                <?php
                            }
                        } ?>
                    </div>

                    <?php

                    //            var_dump($price);die();

                    $sideBar .= '<div class="contet_box contet-box__course-form">';
                    $sideBar .= '<form action="' . get_permalink(6847) . '" method="POST" class="sendCourseform">
                 <input type="hidden" name="course_id" value="' . $post->ID . '">
                 <input type="hidden" name="discount" value="' . $dis . '">
                 <input type="hidden" name="sourceUuid" value="">
                 <input type="hidden" name="full_price" value="' . $oldPrice . '">
                 <input type="hidden" name="full_parts_price" value="' . $oldPartsPrice . '">
                 <input type="hidden" name="price" value="' . $price . '">
                 <input type="hidden" name="discount_price" value="' . $price . '">
                 <input type="hidden" name="new_parts_price" value="' . $partsPrice . '">
                 <input type="hidden" name="parts_price" value="' . $partsPrice . ' x' . $weeks . '">
                 <input type="hidden" name="parts_weeks" value="' . ($weeks ? $weeks : '') . '">
                 <input type="submit" id="sendCourseToForm" value="' . ($lang ? 'Записаться' : 'Записатись') . '"></form>';

                    $sideBar .= '<form action="' . get_permalink(12002) . '" method="POST">
                 <input type="hidden" name="course_id" value="' . $post->ID . '">
                 <input type="hidden" name="price" value="' . $price . '">
                 <input type="hidden" name="discount" value="' . $dis . '">
                 <input type="hidden" name="priseWithDiscount" value="' . $price . '">
                 <input type="hidden" name="full_parts_price" value="' . $oldPartsPrice . '">
                 <input type="hidden" name="discount_price" value="' . $price . '">
                 <input type="hidden" name="full_price" value="' . $oldPrice . '">
                 <input type="hidden" name="parts_price" value="' . $partsPrice . ' x' . $weeks . '">
                 <input type="hidden" name="new_parts_price" value="' . $partsPrice . '">
                 <input type="hidden" name="parts_weeks" value="' . ($weeks ? $weeks : '') . '">
                 <input type="submit" class="sendCourseFree" value="' . ($lang ? 'ПРОБНОЕ ЗАНЯТИЕ' : 'ПРОБНЕ ЗАНЯТТЯ') . '"></form>';
                    $sideBar .= '</div>'; // END div class contet_box
                    echo $sideBar;
                    ?>

                </div>
            </div>
            <div class="course__wrapper">
                <div class="course-description-block">
                    <!-- <?php // the_content(); ?> -->

                    <?php if (get_post_meta($post->ID, 'Описание', true) != ''): ?>
                        <h2><?php echo($lang ? 'Описание курса' : 'Опис курсу'); ?></h2>
                        <?php echo get_post_meta($post->ID, 'Описание', true); ?>

                    <?php endif;
                    if (get_post_meta($post->ID, 'После курса', true) != ''): ?>
                        <h3><?php echo($lang ? 'После курса вы сможете:' : 'Після курсу Ви зможете:'); ?></h3>
                        <?php echo get_post_meta($post->ID, 'После курса', true); ?>
                    <?php endif; ?>

                    <?php if (false) { ?>
                        <h3><?php echo($lang ? 'Также вы получаете:' : 'Також ви отримуєте:'); ?></h3>
                        <div class="divSingleIcons">

                            <div class="icons3">
                                <div class="circleIco"><?php include('../relize/img/svg/9.svg'); ?></div>
                                <p
                                        class="centre"><?php echo($lang ? 'Сертификат об окончании курсов' : 'Сертифікат про закінчення курсів'); ?></p>
                            </div>

                            <div class="icons3">
                                <div class="circleIco"><?php include('../relize/img/svg/7.svg'); ?></div>
                                <p class="centre"><?php echo($lang ? 'Помощь в трудоустройстве' : 'Допомогу в працевлаштуванні'); ?></p>
                            </div>

                            <div class="icons3">
                                <div class="circleIco"><?php include('../relize/img/svg/8.svg'); ?></div>
                                <p class="centre"><?php echo($lang ? 'Программа стажировки' : 'Програму стажування'); ?></p>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (get_post_meta($post->ID, 'Программа', true) != ''): ?>
                        <h3><?php echo($lang ? 'Программа курса:' : 'Програма курсу:'); ?></h3>
                        <p>
                            <?php echo get_post_meta($post->ID, 'Программа', true); ?>
                        </p>
                    <?php endif;

                    if (get_post_meta($post->ID, 'Требования', true) != ''): ?>
                        <h3><?php echo($lang ? 'Минимальные требования:' : 'Мінімальні вимоги:'); ?></h3>
                        <p>
                            <?php echo get_post_meta($post->ID, 'Требования', true); ?>
                        </p>
                    <?php endif;

                    if (get_post_meta($post->ID, 'Лекторы', true) != ''): ?>
                        <h3><?php echo($lang ? 'Лекторы:' : 'Лектори:'); ?></h3>
                        <p>
                            <?php echo get_post_meta($post->ID, 'Лекторы', true); ?>
                        </p>
                    <?php endif; ?>
                    <p>
                        <span style="color:#E1102F;">*</span>
                        <?php if ($lang) { ?>
                            Указанные скидки не суммируются с другими действующими акциями и специальными предложениями. Если у Вас возникли вопросы, обращайтесь за консультацией к нашим менеджерам!
                        <?php } else { ?>
                            Вказані знижки не сумуються з іншими діючими акціями та спеціальними пропозиціями. Якщо у Вас виникли питання, звертайтеся за консультацією до наших менеджерів!
                        <?php } ?>
                    </p>
                </div>
            </div>


        </div>

</section>


<div class="container">

    <?php
    $courses = get_post_meta(pll_get_post($post->ID, 'ru'), 'courses', true);
    $courses = explode(',', $courses);
    if ($courses[0]) {
        echo '<section class="recommended-sources"><div class="container">';
        echo '<span class="recommended-courses-title" style="clear:both;">', ($lang ? 'Рекомендуемые курсы' : 'Рекомендовані курси'), '</span><div id="course">';

        foreach ($courses as $cours_id) {
            $cours_id = trim($cours_id);
            if (!is_numeric($cours_id)) {
                continue;
            }
            $cours_id = (int)$cours_id;
            if (!$lang) {
                $cours_id = pll_get_post($cours_id, 'uk');
            }

            $di = get_post_meta(pll_get_post($cours_id, 'ru'), 'discont', true);
            echo '<div class="grid_3 item val_cours">';
            echo '<div class="img">';
            if ($di > 0) {
                echo '<div class="val_course-discount"><span>-', $di, '%</span></div>';
            }
            echo get_the_post_thumbnail($cours_id);
            echo '<a href="', get_the_permalink($cours_id), '" class="view" title=', ($lang ? '"Перейти к курсу">Просмотреть' : '"Перейти до курсу"> Переглянути'), '</a>';
            echo '</div>';
            echo '<div class="course-title">', get_the_title($cours_id), '</div>';

            $pr = get_post_meta(pll_get_post($cours_id, 'ru'), 'cost', true);
            if ($di > 0) {
                echo '<div class="course_price"><span>', priceThousend(nicePrice($pr)), '</span>';
                $new_pr = priceThousend(nicePrice(ceil($pr * (100 - $di) / 100)));
                echo '<span>', $new_pr, ' UZS</span></div>';
            } else {
                echo '<div class="course_price"><span>', priceThousend(nicePrice($pr)), ' UZS</span></div>';
            }

            $ti = get_post_meta(pll_get_post($cours_id, 'ru'), 'long', true);
            if ($ti && !$minimized) {
                echo '<p>', ($lang ? 'Длительность курса: ' : 'Тривалість курсу: '), $ti, ($lang ? ' ч.' : ' год.'), '</p>';
            }
            echo '</div>';
        }
        echo '</div></div></section>';
    }
    ?>
</div>
