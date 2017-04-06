<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <!--Import Google Icon Font-->
    <meta charset="UTF-8">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?php bloginfo( 'template_directory' );?>/materialize/css/materialize.min.css" media="screen,projection" />
    <link rel="stylesheet" href="<?php bloginfo( 'template_directory' );?>/css/style.css">
    <link rel="stylesheet" href="<?php bloginfo( 'template_directory' );?>/css/eurofilpedia.css">
    <link rel="shortcut icon" href="<?php bloginfo( 'template_directory' );?>/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php bloginfo( 'template_directory' );?>/img/favicon.ico" type="image/x-icon">
    <!--Let browser know website is optimized for mobile-->
    <title>Eurocine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
    <style type="text/css">
        .aligncenter {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .alignleft {
            float: left;
            margin: 0.5em 1em 0.5em 0;
        }

        .alignright {
            float: right;
            margin: 0.5em 0 0.5em 1em;
        }
    </style>
</head>

<body>
    <main class="container">
        <div class="row same-height ">
            <div class="col m3 l3 s12 cont-menu">
                <div class="menu">
                    <ul>
                        <li>
                            <a href="<?php echo get_home_url(); ?>" class="opcion1"></a>
                        </li>
                        <li>
                            <a href="<?php echo get_option('festival_menu_url'); ?>" class="opcion2"></a>
                        </li>
                        <li>
                            <a href="<?php echo get_option('amigos_menu_url'); ?>" class="opcion3"></a>
                        </li>
                        <li>
                            <a href="<?php echo get_option('eurofilmpedia_menu_url'); ?>" class="opcion4 active3"></a>
                        </li>
                    </ul>
                </div>
                <div class="espacio"></div>
                <div class="social">
                    <ul>
                        <li>
                            <a href="https://www.facebook.com/eurocinecolombia/" target="_blank"><img src="<?php bloginfo( 'template_directory' );?>/img/social/F1_1024.png" alt="Facebook"></a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/eurocinecolombia/" target="_blank"><img src="<?php bloginfo( 'template_directory' );?>/img/social/I1_1024.png" alt="Instagram"></a>
                        </li>
                        <li>
                            <a href="https://twitter.com/EUROCINE_Col" target="_blank"><img src="<?php bloginfo( 'template_directory' );?>/img/social/T1_1024.png" alt="Twitter"></a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UC2Nlm415Wg2IRlNsgFNPJnw" target="_blank"><img src="<?php bloginfo( 'template_directory' );?>/img/social/Y1_1024.png" alt="Youtube"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col m11 l9 s12 contenido">
                <?php if ( have_posts() ) : the_post(); ?>
                <div class="row separador">
                  <div class="col s12 banner" style="background-image: url(<?php the_post_thumbnail_url();?>); background-position: center;"></div>
                </div>
                <div class="row">
                    <div class="col l8 m8 s12">
                      <h5><?php the_title(); ?></h5>
                      <p>Por: <?php the_author(); ?></p>
                    </div>
                    <div class="col l4 m4 s12 share">
                        <div class="facebook">
                            <iframe src="https://www.facebook.com/plugins/share_button.php?href=<?php echo urlencode(the_permalink()); ?>&layout=button&mobile_iframe=true&width=81&height=20&appId" width="81" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                        </div>
                        <div class="twitter">
                        <iframe id="tweet-button" allowtransparency="true" frameborder="0" scrolling="no"
                            src="http://platform.twitter.com/widgets/tweet_button.html?via=EUROCINE_Col&amp;text=<?php echo the_permalink(); ?>&amp;count=horizontal"
                            style="width:110px; height:20px;"></iframe>
                         </div>
                    </div>
                    <div class="col s12">
                      <?php the_content();?>
                    </div>
                </div>
            <?php endif;?>
            </div>
        </div>
    </main>
    <?php get_footer(); ?>
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/materialize/js/materialize.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/js/jquery.waypoints.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/js/slider.js"></script>
</body>

</html>
