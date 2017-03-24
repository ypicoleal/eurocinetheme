<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?php bloginfo( 'template_directory' );?>/materialize/css/materialize.min.css" media="screen,projection" />
    <link rel="stylesheet" href="<?php bloginfo( 'template_directory' );?>/css/style.css">
    <link rel="stylesheet" href="<?php bloginfo( 'template_directory' );?>/css/festival.css">
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
            <div class="col l3 s12 cont-menu">
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
                            <a href="<?php echo get_option('eurofilmpedia_menu_url'); ?>" class="opcion4"></a>
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
                  <div class="col s12 trailer padding-0">
                      <?php 
                        $youtube = get_post_meta($post->ID, '_youtube', true); 
                        $video = explode("youtu.be/", $youtube);
                      ?>
                      <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php echo $video[1]; ?>" frameborder="0" allowfullscreen></iframe>
                  </div>
                </div>
                <div class="row">
                  <div class="col s12 color-patrocinador caja-patrocinador" style="padding: 0;">
                    <a href="<?php echo get_option('festival_url'); ?>" target="_blank">
                        <img src="<?php echo get_option('img_festival'); ?>" style="width: 100%; height: 100%;" />
                    </a>
                  </div>
                </div>
                <div class="row">
                  <div class="col s12">
                    <div class="row">
                      <div class="col s12 l6 m6">
                        <h5 class="titulo-pelicula"><?php the_title() ?></h5>
                      </div>
                      <div class="col s12 l6 m6">
                        <h5 class="categoria"><?php 
                          $categories = get_the_terms(get_the_ID(), 'pelicula-categoria');
                          foreach ($categories as $category) {
                            echo $category->name;
                            echo  $category === end($categories)? '':' - ';
                          }
                        ?></h5>
                      </div>
                      <div class="col s12 info">
                        <span>Director: <?php echo get_post_meta(get_the_ID(), '_director', true); ?></span><br>
                        <span>País: <?php echo get_post_meta(get_the_ID(), '_pais', true); ?></span>
                        <p>
                          <?php the_content(); ?>
                        </p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col s12 info">
                        <ul>
                          <?php
                            $horarios = get_post_meta(get_the_ID(), '_horarios', true);
                            foreach ($horarios as $key => $horario) {
                              $meses = array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                              $date = new DateTime($horario['fecha'] . " " . $horario['hora']);
                              $mes = date('n',$date->getTimestamp());
                              $dia = date('d',$date->getTimestamp());
                              $dia = $meses[$mes].' '. $dia;
                              $hora = date_format($date, 'g:ia');
                              echo "<li>". $horario['ciudad'] .", ". $dia .", ". $horario['teatro'] .", ". $hora ."</li>";
                            }
                          ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
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