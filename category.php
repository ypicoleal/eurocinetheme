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
            <div class="row euro-titulo">
                <div class="col l4 s5">
                    <img class="" src="<?php bloginfo( 'template_directory' );?>/img/Titulo.png" alt="">
                </div>
                <div class="col l8 s7">
                    <div class="euro-titulo-2">
                        <p class="t1">Blog de la cultura del</p>
                        <p class="t2">Cine Europeo</p>
                    </div>
                </div>
            </div>
              <div class="row">
                  <div class="col s12  white-text separador">
                      <span class="color-contenido titulos cont-span">FILTRAR POR</span><img class="star" src="<?php bloginfo( 'template_directory' );?>/img/star1.svg" alt="">
                  </div>
                  <div class="col s12">
                      <nav class="filtros">
                            <?php $categories = get_categories( array(
                                        'hide_empty'   => 0
                                    ) );
                            $i = 0;
                            foreach( $categories as $category ) {
                                $t_id = $category->term_id;
                                $term_meta = get_option( "taxonomy_$t_id" ); ?>
                            <a href="<?php echo esc_url(get_category_link( $category->term_id )); ?>" class="gris" style="border-bottom:12px <?php echo esc_attr( $term_meta['cat_color'] ); ?> solid;"><span><?php echo esc_html( $category->name ); ?></span><span class="linea <?php echo $category === end($categories)? 'last' : '' ?>"></span></a>
                            <?php }?>
                      </nav>
                  </div>
              </div>
                
                    <?php   
                            $t_id = get_query_var('cat');
                            $term_meta = get_option( "taxonomy_$t_id" ); 
                            $color = esc_attr($term_meta['cat_color']);
                            $flag = true;
                    if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php if($flag): ?>
                    <div class="row same-height">
                    <?php endif; ?>
                        <div class="col l6 s12">
                            <div class="row">
                                <div class="col l11 s12 caja-contenido" style="background-color: <?php echo $color ?>;">
                                  <a href="<?php the_permalink(); ?>">
                                      <img src="<?php the_post_thumbnail_url();?>" alt="">
                                  </a>
                                </div>
                                <div class="col l11 s12 cont-texto">
                                  <p class="titulo-fecha"> <span style="color: <?php echo $color ?>;"><?php single_cat_title(); ?></span> <?php the_date(); ?></p>
                                  <p class="titulo-media-1"><?php the_title(); ?></p>
                                  <p class="subtitulo-media"><?php echo get_post_meta($post->ID, '_subtitulo', true); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php if(!$flag): ?>
                    </div>
                    <?php endif;?>
                    <?php $flag = !$flag; ?>
                    <?php  endwhile; endif; ?>
                    <?php if(!$flag): ?>
                        <div class="col l6 s12 " >
                            <div class="row full-height">
                                <div class="col l11 s12 full-height" style="padding: 0;">
                                    <a href="<?php echo get_option('eurofilmpedia_url'); ?>" target="_blank">
                                        <img src="<?php echo get_option('img_eurofilmpedia'); ?>" style="width: 100%; height: 100%;" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col l6 s12 ">
                                <div class="row full-height">
                                    <div class="col l11 s12 full-height" style="padding: 0;">
                                        <a href="<?php echo get_option('eurofilmpedia_url'); ?>" target="_blank">
                                            <img src="<?php echo get_option('img_eurofilmpedia'); ?>" style="width: 100%; height: 100%;" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
            </div>
        </div>
    </main>
    <?php get_footer() ?>
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/materialize/js/materialize.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/js/jquery.waypoints.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo( 'template_directory' );?>/js/slider.js"></script>
</body>

</html>