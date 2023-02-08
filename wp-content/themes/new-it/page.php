<?php
get_header();
global $post;
?>
<section class="broadcrumbs">
    <nav class="container">
        <?php
        if (function_exists('dimox_breadcrumbs')) {
            dimox_breadcrumbs();
        }
        ?>
    </nav>
</section>

<div class="container">

    <div class="head-section">
        <h1>
            <?php the_title(); ?>
        </h1>
    </div>

    <div class="block-news clearfix">
        <div class="articleNew">

        <?php
        if ( get_post_gallery() ) :
        ?>
            <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/relize/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
            <script src="<?php bloginfo('template_directory'); ?>/relize/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
            <script src="social-likes.min.js"></script>

            <?php
            print get_post_gallery();
            ?>

<script type="text/javascript">
$(".gallery-item a").attr("rel", "prettyPhoto[pp_gal]");

 $(document).ready(function(){
    $("a[rel^='prettyPhoto']").prettyPhoto({
        default_width: 600,
        default_height: 400,
        markup: '<div class="pp_pic_holder"> \
                        <div class="ppt">&nbsp;</div> \
                        <div class="pp_top"> \
                            <div class="pp_left"></div> \
                            <div class="pp_middle"></div> \
                            <div class="pp_right"></div> \
                        </div> \
                        <div class="pp_content_container"> \
                        <a class="pp_close" href="#">Close</a> \
                            <div class="pp_left"> \
                            <div class="pp_right"> \
                                <div class="pp_content"> \
                                    <div class="pp_loaderIcon"></div> \
                                    <div class="pp_fade"> \
                                        <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
                                        <div class="pp_hoverContainer"> \
                                            <a class="pp_next" href="#">next</a> \
                                            <a class="pp_previous" href="#">previous</a> \
                                        </div> \
                                        <div id="pp_full_res"></div> \
                                        <div class="pp_details"> \
                                            <p class="pp_description"><div class="pp_social"><div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"><\/script></div><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href='+location.href+'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div></div></p> \
                                            {pp_social} \
                                        </div> \
                                    </div> \
                                </div> \
                            </div> \
                            </div> \
                        </div> \
                        <div class="pp_bottom"> \
                            <div class="pp_left"></div> \
                            <div class="pp_middle"></div> \
                            <div class="pp_right"></div> \
                        </div> \
                    </div> \
                    <div class="pp_overlay"></div>',
        social_tools: ''

    });

  });
</script>

<?php
else :
    echo apply_filters('the_content', $post->post_content);
endif;
?>

        </div>
    </div>
</div>

<?php get_footer(); ?>