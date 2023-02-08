<?php /* Template Name: Страница "Контакты" */ ?>

<?php get_header(); ?>

<?php if (get_locale() != 'en_GB') { ?>
<section class="broadcrumbs">
    <nav class="container">
        <?php
        if (function_exists('dimox_breadcrumbs')) {
            dimox_breadcrumbs();
        }
        ?>
    </nav>
</section>
<?php } ?>

<div class="container">
    <div class="head-section">
        <h1>
            <?php the_title(); ?>
        </h1>
    </div>
    <div class="block-news clearfix">
        <div class="TwoListsCourses isoLinkDay q-contacts">
            <div class="contacts-list-wrapper">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#desc" class="" aria-controls="main" role="tab" data-toggle="tab"><?php echo ( get_locale() == 'ru_RU' ? 'Ташкент' : (get_locale() == 'uk' ? 'Ташкент' : 'Tashkent') ); ?></a></li>
                    <li role="presentation"><a href="#profile" class="" aria-controls="kiev" role="tab" data-toggle="tab"><?php             echo ( get_locale() == 'ru_RU' ? 'Главный офис' : (get_locale() == 'uk' ? 'Головний офіс' : 'Head office') ); ?></a></li>
                    <li role="presentation"><a href="#profile" class="" aria-controls="lviv" role="tab" data-toggle="tab"><?php             echo ( get_locale() == 'ru_RU' ? 'Львов'   : (get_locale() == 'uk' ? 'Львів'  : 'Lviv') ); ?></a></li>
                    <li role="presentation"><a href="#require" class="" aria-controls="dnipro" role="tab" data-toggle="tab"><?php           echo ( get_locale() == 'ru_RU' ? 'Днепр'   : (get_locale() == 'uk' ? 'Дніпро' : 'Dnipro') ); ?></a></li>
                    <li role="presentation"><a href="#require" class="" aria-controls="dnipro" role="tab" data-toggle="tab"><?php           echo ( get_locale() == 'ru_RU' ? 'Харьков' : (get_locale() == 'uk' ? 'Харків' : 'Kharkiv') ); ?></a></li>
                    <li role="presentation"><a href="#require" class="" aria-controls="dnipro" role="tab" data-toggle="tab"><?php           echo ( get_locale() == 'ru_RU' ? 'Одесса'  : (get_locale() == 'uk' ? 'Одеса'  : 'Odessa') ); ?></a></li>
                    <li role="presentation"><a href="#require" class="" aria-controls="dnipro" role="tab" data-toggle="tab"><?php           echo ( get_locale() == 'ru_RU' ? 'Лондон'  : (get_locale() == 'uk' ? 'Лондон' : 'London') ); ?></a></li>
                    <li role="presentation"><a href="#require" class="" aria-controls="dnipro" role="tab" data-toggle="tab"><?php           echo ( get_locale() == 'ru_RU' ? 'Осло'    : (get_locale() == 'uk' ? 'Осло'   : 'Oslo') ); ?></a></li>
                    <li role="presentation"><a href="#require" class="" aria-controls="dnipro" role="tab" data-toggle="tab"><?php           echo ( get_locale() == 'ru_RU' ? 'Луцк'    : (get_locale() == 'uk' ? 'Луцьк'   : 'Lutsk') ); ?></a></li>

                    <button class="btn-toggle-collapse btn" aria-hidden="true"><span class="glyphicon glyphicon-chevron-down"></span></button>
                </ul>
            </div>
            <section class="contacts-content">
                <div id="gmaps-iframe"></div>
                <iframe id="gmaps-iframe" style="max-width: 750px; width: 100%; height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2540.4616256838476!2d30.44319031573133!3d50.45112797947548!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4cc2508bb8547%3A0xb15ba5c3a4a0c8e8!2z0LLRg9C7LiDQodC80L7Qu9C10L3RgdGM0LrQsCwgMzEvMzMsINCa0LjRl9CyLCDQo9C60YDQsNC40L3QsA!5e0!3m2!1sru!2s!4v1464268631583" width="300" height="150" frameborder="0"></iframe>
                <div class="q-contacts-cart">
                    <?php
                    while( have_posts() ) : the_post();
                    ?>
                    <div class="details fade">
                        <?php the_content(); ?>
                    </div>
                        <?php
                    endwhile;
                    ?>
                </div>
            </section>
        </div>
    </div>
</div>

    <script>
        var language = '<?php echo substr(get_locale(), 0, 2); ?>';
    </script>
    <script src="<?php bloginfo('template_directory'); ?>/relize/js/contacts-map.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/relize/js/site-heart-code.js"></script>


<?php get_footer(); ?>