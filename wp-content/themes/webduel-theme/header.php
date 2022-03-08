<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PS7XFHN');
    </script>
    <!-- End Google Tag Manager -->

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11" />
    <?php wp_head(); ?>


    <!-- splide -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <!-- font awesome  -->
    <script src="https://kit.fontawesome.com/71827cc3f2.js" crossorigin="anonymous"></script>
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- bing tag -->
    <meta name="msvalidate.01" content="8BB2BD3056EE954D25649333FBFC2D75" />

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '828264374302518');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=828264374302518&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->
    <?php
    // get user email address 
    $pinterestUserEmail;
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $pinterestUserEmail = $current_user->user_email;
        $hashedPinterestEmail = wp_hash($pinterestUserEmail);
    }
    ?>
    <!-- pinterest Tag -->
    <!-- Pinterest Tag -->
    <script>
        ! function(e) {
            if (!window.pintrk) {
                window.pintrk = function() {
                    window.pintrk.queue.push(Array.prototype.slice.call(arguments))
                };
                var
                    n = window.pintrk;
                n.queue = [], n.version = "3.0";
                var
                    t = document.createElement("script");
                t.async = !0, t.src = e;
                var
                    r = document.getElementsByTagName("script")[0];
                r.parentNode.insertBefore(t, r)
            }
        }("https://s.pinimg.com/ct/core.js");
        pintrk('load', '2612506427665', {
            em: '<?php echo $pinterestUserEmail; ?>'
        });
        pintrk('page');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none;" alt="" src='https://ct.pinterest.com/v3/?event=init&tid=2612506427665&pd[em]=<<?php echo $hashedPinterestEmail ?>>&noscript=1' />
    </noscript>
    <!-- end Pinterest Tag -->


</head>

<body id="header" <?php body_class(); ?> data-archive='<?php echo $archive ?>'>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PS7XFHN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <section class="header">
        <div class="top-banner">
            <div class="banner-container">
                <div class="banner-card owl-carousel">
                    <?php

                    $argsBanner = array(
                        'post_type' => 'banners',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'banners_categories',
                                'field'    => 'slug',
                                'terms'    => array('top-banner'),
                            )
                        )
                    );
                    $banner = new WP_Query($argsBanner);

                    while ($banner->have_posts()) {
                        $banner->the_post();
                        if (get_field('banner_link')) {
                    ?>
                            <a href="<?php echo get_field('banner_link'); ?>" class="anchor"> <?php echo get_the_title(); ?> LEARN <i class="fal fa-chevron-right white"></i></a>

                        <?php
                        } else {
                        ?>
                            <a href="<?php echo get_field('banner_link'); ?>" class="anchor"> <?php echo get_the_title(); ?></a>
                    <?php
                        }
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </section>