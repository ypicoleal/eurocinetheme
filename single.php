<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <!--Import Google Icon Font-->
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
</head>

<body>
    <main class="container">
        <div class="row same-height ">
            <div class="col l3 s12 cont-menu">
                <div class="menu">
                    <ul>
                        <li>
                            <a href="#1" class="opcion1"></a>
                        </li>
                        <li>
                            <a href="#2" class="opcion2"></a>
                        </li>
                        <li>
                            <a href="#3" class="opcion3"></a>
                        </li>
                        <li>
                            <a href="#4" class="opcion4"></a>
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
                            <a href="https://www.youtube.com/channel/UCC8so8MpAfFqrd2ml40anFA" target="_blank"><img src="<?php bloginfo( 'template_directory' );?>/img/social/Y1_1024.png" alt="Youtube"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col l9 s12 contenido">
                <?php if ( have_posts() ) : the_post(); ?>
                <div class="row separador">
                  <div class="col s12">
                    <img src="<?php the_post_thumbnail_url();?>" width="100%" alt="">
                  </div>
                </div>
                <div class="row">
                    <div class="col s12">
                      <h5><?php the_title(); ?></h5>
                      <p>Por: <?php the_author(); ?></p>
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
