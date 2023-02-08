<?php

if (get_locale() == 'en_GB') {
    if (!in_array(get_the_ID(), [11097, 11093])) {
        wp_redirect(get_permalink(11093));
        exit;
    }
}

$lang = (get_locale() == 'ru_RU');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-68457841-13"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-68457841-13');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="yandex-verification" content="37ad0abcdc36f2be">

    <title><?php wp_title(); ?></title>
    <?php
    if ( is_404() or is_attachment() ) {
        echo '<meta name="robots" content="noindex,nofollow"><meta name="robots" content="none">';
    } elseif (is_front_page() or is_category() or is_single()) {
        echo '<meta name="robots" content="index,follow"><meta name="robots" content="all">';
    }
    ?>

    <?php wp_head(); ?>
    <link rel="icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" type="image/x-icon">

    <?php if (in_array(get_query_var('cat'), [25, 310])) { ?>
        <!-- <style type="text/css">
            .content .container {
                width: 1280px !important;
            }
         </style> -->
    <?php } ?>
    <!--[if IE]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie.css"><![endif]-->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

    <style>
        .hide_body>*:not(#preload-it){
            opacity: 0;
        }
    </style>
</head>
<body class="hide_body">


<header id="header" class="default navbar navbar-default">

    <div class="b-menu-wrapper container">


        <div class="left_header_part">
          <a href="<?php echo get_itea_home_url(); ?>" id="logo" class="pull-left" style="padding-right: 40px; border-right: 1px solid #F3F8FE;height: 100%;">
            <input type="hidden" id="logo-fixed" value="<?php bloginfo('template_directory'); ?>/relize/img/logo-fixed.png"/>
            <img src="<?php bloginfo('template_directory'); ?>/relize/img/logo-itea.svg" alt="ITEA"/>
          </a>
          <a href="#" class="left_header_part_search"></a>
            <?php if (get_locale() != 'en_GB') { ?>
                <ul class="pos">
                    <li class="parent">
                        <a href="<?php echo get_itea_home_url(); ?>" onclick="return false;"><?php echo($lang ? 'Ташкент' : 'Ташкент'); ?></a>
                        <ul class="child">
                            <li class="child_li">
                                <a class="city_name" href="https://lviv.itea.ua/" rel="nofollow">
                                    <?php echo ($lang ? 'Львов' : 'Львів'); ?>
                                </a>
                            </li>
                            <li class="child_li">
                                <a class="city_name" href="https://itea.ua/" rel="nofollow">
                                    <?php echo ($lang ? 'Киев' : 'Київ'); ?>
                                </a>
                            </li>
                            <li class="child_li">
                                <a class="city_name" href="https://kharkiv.itea.ua/" rel="nofollow">
                                    <?php echo ($lang ? 'Харьков' : 'Харьків'); ?>
                                </a>
                            </li>
                            <li class="child_li">
                                <a class="city_name" href="<?php echo get_itea_home_url(); ?>">
                                    <?php echo ($lang ? 'Ташкент' : 'Ташкент'); ?>
                                </a>
                            </li>
                            <li class="child_li">
                                <a class="city_name" href="https://dnipro.itea.ua/"  rel="nofollow">
                                    <?php echo ($lang ? 'Днепр' : 'Дніпро'); ?>
                                </a>
                            </li>
                            <li class="child_li">
                                <a class="city_name" href="https://lutsk.itea.ua/"  rel="nofollow">
                                    <?php echo ($lang ? 'Луцк' : 'Луцьк'); ?>
                                </a>
                            </li>
                            <li class="child_li"> <a class="city_name" href="https://itea.asia/" rel="nofollow"> Ашхабад </a></li>
                        </ul>
                    </li>
                </ul>
            <?php } ?>

            <ul class="lang"><?php pll_the_languages(); ?></ul>
        </div>

        <div class="right_header_part">
          <div class="phones-block">
            <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
            <a href="tel:+420296842475" class="number_phone phones-block__phone">+420 296 842 475</a>
            <ul class="phones-block__list">
              <li><a class="number_phone" href="tel:+420296842475">+420 296 842 475</a></li>
            </ul>
          </div>

          <div class="callback b-header-contacte-phone">
            <a href="#" class="callback-btn">
              <?php echo(get_locale() != 'en_GB' ? ($lang ? 'Обратный звонок' : 'Зворотній дзвінок') : 'Request the callback'); ?>
            </a>
          </div>
        </div>

        <button type="button" class="nav-toggle navbar-toggle collapsed" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1">
        </button>

        <div class="top-search-form">
            <?php get_search_form(); ?>
        </div>
    </div><!-- /.container -->

    <div class="b-nav-wrapper">
        <div class="container">
            <div class="navigation">
                <div class="navbar-header">
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <div class="nav-controls">

                            <?php if (get_locale() != 'en_GB') { ?>
                                <ul class="pos nav-control">
                                    <li class="parent">
                                        <a href="<?php echo get_itea_home_url(); ?>" onclick="return false;">
                                            <span class="glyphicon glyphicon-map-marker"></span>
                                            <?php echo($lang ? 'Ташкент' : 'Ташкент'); ?>
                                        </a>
                                        <ul class="child">
                                            <li class="child_li">
                                                <a class="city_name" href="https://lviv.itea.ua/" rel="nofollow">
                                                    <?php echo ($lang ? 'Львов' : 'Львів'); ?>
                                                </a>
                                            </li>
                                            <li class="child_li">
                                                <a class="city_name" href="https://itea.ua/" rel="nofollow">
                                                    <?php echo ($lang ? 'Киев' : 'Київ'); ?>
                                                </a>
                                            </li>
                                            <li class="child_li">
                                                <a class="city_name" href="https://kharkiv.itea.ua/" rel="nofollow">
                                                    <?php echo ($lang ? 'Харьков' : 'Харьків'); ?>
                                                </a>
                                            </li>
                                            <li class="child_li">
                                                <a class="city_name" href="<?php echo get_itea_home_url(); ?>">
                                                    <?php echo ($lang ? 'Ташкент' : 'Ташкент'); ?>
                                                </a>
                                            </li>
                                            <li class="child_li">
                                                <a class="city_name" href="https://dnipro.itea.ua/"  rel="nofollow">
                                                    <?php echo ($lang ? 'Днепр' : 'Дніпро'); ?>
                                                </a>
                                            </li>
                                            <li class="child_li">
                                                <a class="city_name" href="https://lutsk.itea.ua/"  rel="nofollow">
                                                    <?php echo ($lang ? 'Луцк' : 'Луцьк'); ?>
                                                </a>
                                            </li>
                                            <li class="child_li"> <a class="city_name" href="https://itea.asia/" rel="nofollow"> Ашхабад </a></li>
                                        </ul>
                                    </li>
                                </ul>
                            <?php } ?>

                            <ul class="lang" style="display: none;"><?php pll_the_languages(); ?></ul>
                        </div>

                        <?php
                        $menu_options = [
                            'menu' => 'Main Menu' . (get_locale() == 'ru_RU' ? '' : (get_locale() == 'en_GB' ? ' (en)' : ' (uk)')),
                            'container'       => 'nav',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_id'         => 'flex'
                        ];
                        wp_nav_menu($menu_options);
                        ?>
                    </div>
                </div>
            </div>

            <?php if (get_locale() != 'en_GB') { ?>
                <div class="header-search">
                    <?php get_search_form(); ?>
                    <button class="show-search"><?php echo($lang ? 'Поиск' : 'Пошук'); ?> </button>
                    <button class="show-search show-search-show" style="left: 0; display: none; "></button>
                </div>
            <?php } ?>
        </div>
</header><!-- /header -->

<div class="content">
