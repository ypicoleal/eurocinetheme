
<?php

add_theme_support( 'post-thumbnails' );
add_shortcode( 'amigos', 'amigos_fn' );
add_shortcode( 'home', 'home_fn' );
add_shortcode( 'home_temporal', 'home_temporal_fn' );
add_shortcode( 'festival_programacion', 'festival_programacion_fn' );
add_shortcode( 'festival', 'festival_fn' );

function amigos_fn($atts){
	$template = file_get_contents(get_template_directory() . '/html/nuestros_amigos.html', true);
	$url_template = get_bloginfo( 'template_directory' );
	$template = str_replace("{{template_directory}}", $url_template, $template);
	$template = str_replace("{{caja-apoyo}}", get_apoyos(), $template);
	$template = str_replace("{{slider-patrocinador}}", get_slide('slide-patrocinador'), $template);
	$template = str_replace("{{slider-equipo}}", get_slide('slide-equipo'), $template);
	$template = str_replace("{{slider-superior}}", get_slide_superior(), $template);
	$template = str_replace("{{pauta-img}}", get_option('img_amigos'), $template);
	$template = str_replace("{{pauta-url}}", get_option('amigos_url'), $template);
	return $template;
}

function festival_programacion_fn($atts){
	$ciudad = $_GET['ciudad'];
	$fecha = $_GET['fecha'];
	$meses = array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$date = new DateTime($fecha);
	$mes = date('n',$date->getTimestamp());
	$dia = date('d',$date->getTimestamp());
	$dia = $meses[$mes].' '. $dia;

	$template = file_get_contents(get_template_directory() . '/html/festival_programacion.html', true);
	$url_template = get_bloginfo('template_directory');
	$template = str_replace("{{template_directory}}", $url_template, $template);
	$template = str_replace("{{horarios}}", get_peliculas_by_query($ciudad, $date), $template);
	$template = str_replace("{{ciudad}}", $ciudad, $template);
	$template = str_replace("{{fecha}}", $dia, $template);
	$template = str_replace("{{pauta-img}}", get_option('img_programacion'), $template);
	$template = str_replace("{{pauta-url}}", get_option('programacion_url'), $template);
	return $template;
}

function festival_fn($atts){
	$url_programacion = get_permalink(get_option('page_programacion'));
	$template = file_get_contents(get_template_directory() . '/html/festival_principal.html', true);
	$url_template = get_bloginfo('template_directory');
	$template = str_replace("{{template_directory}}", $url_template, $template);
	$template = str_replace("{{peliculas}}", get_peliculas_slider(), $template);
	$template = str_replace("{{url_programacion}}", $url_programacion, $template);
	return $template;
}

function get_peliculas_slider(){
    $the_query = new WP_Query(array(
			'post_type' => 'pelicula',
			'orderby'	=> 'rand',
			'posts_per_page' => 20,
		));
    $counter = 0;
	$box = '';
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$url = get_post_meta($post->ID, '_url', true);
				if ($counter == 0) {
					$box .= '<li>' . PHP_EOL;
                    $box .= '    <div class="row margin-0 peliculas white-text">' . PHP_EOL;
                    $box .= '        <div class="col s6 m12 l12 padding-0">' . PHP_EOL;
                    $box .= '            <div class="row margin-0 fila1">' . PHP_EOL;
				}else if ($counter == 4) {
					$box .= '</div>';
                    $box .= '<div class="col s6 m12 l12 padding-0">';
                    $box .= '    <div class="row margin-0  fila2">';
				}
				$box .= '<div class="col l2 s12 caja-peli">'. PHP_EOL;
                $box .= '    <a href="'. get_the_permalink() .'"><img src="'. get_the_post_thumbnail_url() .'" alt="">'. PHP_EOL;
                $box .= '        <h6 class="center white-text">'. get_the_title() .'</h6></a>'. PHP_EOL;
                $box .= '</div>'. PHP_EOL;
			    if ($counter == 7) {
			    	$box .= '			</div>'. PHP_EOL;
                    $box .= '        </div>'. PHP_EOL;
                    $box .= '    </div>'. PHP_EOL;
                    $box .= '</li>'. PHP_EOL;
			    	$counter = 0;
			    }else {
			    	$counter++;
			    }
			}
			if ($counter < 7) {
		    	$box .= '			</div>'. PHP_EOL;
                $box .= '        </div>'. PHP_EOL;
                $box .= '    </div>'. PHP_EOL;
                $box .= '</li>'. PHP_EOL;
		    	$counter = 0;
		    }
			wp_reset_postdata();
		}
    return $box;
}

function get_peliculas_by_query($ciudad, $fecha){
	
	global $post;

	$the_query = new WP_Query(array(
			'post_type' => 'teatro',
			'meta_key' => '_ciudad',
			'meta_value' => $ciudad,
			'posts_per_page' => -1,
		));
	$box = '';
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$direccion = get_post_meta($post->ID, '_direccion', true);
			$telefono = get_post_meta($post->ID, '_telefono', true);
			$title = get_the_title();

			$box .= '<tr>';
            $box .= '    <td><h5>'. $title .'</h5>'. 'Tel: ' . $telefono . '</td>';
            $box .= '    <td>'. $direccion .'</td>';
            $box .= '</tr>';

		    $pelicula_query = new WP_Query(array(
				'post_type' => 'pelicula',
				'posts_per_page' => -1,
			));
		    while ( $pelicula_query->have_posts() ) {
				$pelicula_query->the_post();
				$horarios = get_post_meta($post->ID, '_horarios', true);
				foreach ($horarios as $key => $horario) {
					$date = new DateTime($horario['fecha'] . " " . $horario['hora']);
					$hora = date_format($date, 'g:ia');
					if ($horario['teatro'] == $title && $fecha == new DateTime($horario['fecha'])) {
						$box .= '                <tr>';
			    		$box .= '                    <td><a href="'. get_the_permalink() .'" style="color: #606060;">'. get_the_title() .'</a></td>';
			    		$box .= '                    <td>'. $hora .'</td>';
			    		$box .= '                </tr>';
					}
				}
			}
		    $box .= '<tr class="espacio">';
          	$box .= '	<td>&nbsp;&nbsp;</td>';
          	$box .= '	<td>&nbsp;&nbsp;</td>';
        	$box .= '</tr>';
		}
		wp_reset_postdata();
	}
	return $box;
}

function add_color_category() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[cat_color]"><?php _e( 'Color de categoria', 'eurocine' ); ?></label>
		<input type="text" name="term_meta[cat_color]" id="term_meta[cat_color]" value="">
		<p class="description"><?php _e( 'Ingrese el color de la categoria','eurocine' ); ?></p>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'add_color_category', 10, 2 );

function edit_color_category($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); 
	?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[cat_color]"><?php _e( 'Color de categoria', 'eurocine' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[cat_color]" id="term_meta[cat_color]" value="<?php echo esc_attr( $term_meta['cat_color'] ) ? esc_attr( $term_meta['cat_color'] ) : ''; ?>">
			<p class="description"><?php _e( 'Ingrese el color de la categoria','eurocine' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'edit_color_category', 10, 2 );

function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_category', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_category', 'save_taxonomy_custom_meta', 10, 2 );

function home_fn($atts){

	$cat_id = get_option('category_home');

	$template = file_get_contents(get_template_directory() . '/html/home.html', true);
	$url_template = get_bloginfo( 'template_directory' );
	$template = str_replace("{{template_directory}}", $url_template, $template);
	$template = str_replace("{{contenidos}}", get_contenidos(), $template);
	$template = str_replace("{{peliculas}}", get_peliculas(), $template);
	$template = str_replace("{{pauta-img}}", get_option('img_home'), $template);
	$template = str_replace("{{pauta-url}}", get_option('home_url'), $template);
	$template = str_replace("{{slider-superior}}", get_slide_superior(), $template);
	$template = str_replace("{{category_name}}", get_cat_name($cat_id), $template);
	$template = str_replace("{{category_posts}}", get_home_cat_posts($cat_id), $template);
	return $template;
}

function home_temporal_fn($atts){
	$template = file_get_contents(get_template_directory() . '/html/home_temporal.html', true);
	$url_template = get_bloginfo( 'template_directory' );
	$template = str_replace("{{template_directory}}", $url_template, $template);
	return $template;
}

function get_peliculas(){
    $the_query = new WP_Query(array(
			'post_type' => 'pelicula',
			'orderby'	=> 'rand',
			'posts_per_page' => 4,
		));

	$box = '';
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$box .= '<div class="col l3 s6">';
	            $box .= '    <a href="'. get_the_permalink() .'">';
	            $box .= '        <img src="'. get_the_post_thumbnail_url() .'" alt="">';
	            $box .= '        <h5 class="center">'. get_the_title() .'</h5>';
	            $box .= '    </a>';
	            $box .= '</div>';
			}
			wp_reset_postdata();
		}
    return $box;
}

function get_home_cat_posts($cat_id){
    $the_query = new WP_Query(array(
			'post_type' 	 => 'post',
			'posts_per_page' => 2,
			'category'  	 => $cat_id,
		));

	$box = '';
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$box .='<div class="col l6 s12 ">';
			    $box .='    <div class="row">';
			    $box .='        <div class="col s12 color-eventos caja-contenido cont-left">';
			    $box .='        	<a href="'. get_the_permalink() .'"><img src="'. get_the_post_thumbnail_url() .'" style="width:100%; height:100%;"></a>';
			    $box .='        </div>';
			    $box .='        <div class="col s12 cont-left">';
			    $box .='          <p> <span class="verde">'. get_the_category()[0]->name .'</span> '. get_the_date() .'</p>';
			    $box .='          <p class="titulo-media-1">'. get_the_title() .'</p>';
			    $box .='		  <p class="subtitulo-media">'. get_post_meta(get_the_ID(), '_subtitulo', true) . '</p>';
			    $box .='        </div>';
			    $box .='    </div>';
			    $box .='</div>';
			}
			wp_reset_postdata();
		}
    return $box;
}

function get_contenidos(){
    $the_query = new WP_Query(array(
			'post_type' => 'post',
			'orderby'	=> 'rand',
			'posts_per_page' => 2,
		));

	$box = '';
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$box .='<div class="col l6 s12 ">';
			    $box .='    <div class="row">';
			    $box .='        <div class="col s12 color-contenido caja-contenido cont-rigth">';
			    $box .='        	<a href="'. get_the_permalink() .'"><img src="'. get_the_post_thumbnail_url() .'" style="width:100%; height:100%;"></a>';
			    $box .='        </div>';
			    $box .='        <div class="col s12 cont-rigth">';
			    $box .='          <p> <span class="verde">'. get_the_category()[0]->name .'</span> '. get_the_date() .'</p>';
			    $box .='          <p class="titulo-media-1">'. get_the_title() .'</p>';
			    $box .='		  <p class="subtitulo-media">'. get_post_meta(get_the_ID(), '_subtitulo', true) . '</p>';
			    $box .='        </div>';
			    $box .='    </div>';
			    $box .='</div>';
			}
			wp_reset_postdata();
		}
    return $box;
}

function get_apoyos(){

	global $post;

	$the_query = new WP_Query(array(
			'post_type' => 'apoyo',
			'posts_per_page' => -1
		));

	$box = '';
		if ($the_query->have_posts()) {
			$counter = 0;
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$url = get_post_meta($post->ID, '_url', true);
				if ($counter == 0) {
					$box .='<div class="col s6 m12 l12 padding-0">'. PHP_EOL;
			    	$box .='    <div class="row margin-bottom-0 fila1">'. PHP_EOL;
				}
				
			    $box .='        <div class="col l2 m2 s12 caja-p" >'. PHP_EOL;
			    $box .='            <a href="'. $url .'" target="_blank"><img src="'. get_the_post_thumbnail_url() .'" alt=""></a>'. PHP_EOL;
			    $box .='        </div>'. PHP_EOL;
			    if ($counter == 4) {
			    	$box .='     </div>'. PHP_EOL;
			    	$box .='</div>'. PHP_EOL;
			    	$counter = 0;
			    }else {
			    	$counter++;
			    }
			}
			if ($counter < 4) {
				for ($i=$counter; $i < 5; $i++) { 
					$box .= '<div class="col l2 m2 s12 caja-p"></div>';
				}
		    	$box .='     </div>'. PHP_EOL;
		    	$box .='</div>'. PHP_EOL;
		    	$counter == 0;
		    }
			wp_reset_postdata();
		}
    return $box;
}

function get_slide($post_type){

	global $post;

	$the_query = new WP_Query(array(
			'post_type' => $post_type,
			'posts_per_page' => -1
		));

	$box = '<ul class="slides">';
		if ( $the_query->have_posts() ) {
			
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$url = get_post_meta($post->ID, '_url', true);
				$box .= '<li>'; 
				$box .= '<a href="'. $url .'" target="_blank"><img src="'. get_the_post_thumbnail_url() .'"></a>';
                $box .= '</li>';
			}
			wp_reset_postdata();
		}
    $box .= '</ul>';
    return $box;
}

function get_slide_superior(){

	global $post;

	$the_query = new WP_Query(array(
			'post_type' => 'slide-superior',
			'posts_per_page' => -1
		));
	$modals = '';
	$box = '<div class="col s12 cont-slider">';
	$box .= '<div class="slider">';
	$box .= '<ul class="slides">';
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$box .= '<li>'; 
				$box .= '	<img src="'. get_the_post_thumbnail_url() .'">';
				$box .= '	<div class="caption caption2 left-align">';
	            $box .= '	    <a href="#modals'.$post->ID.'" class="leer-mas black-text modal-trigger">Leer Mas <svg height="10" width="10" viewBox="0 0 210 210"><path d="M210 0 L0 0 L0 210 Z " fill="#000"/>Sorry, your browser does not support inline SVG.</svg></a>';
	            $box .= '	</div>';
	            $modals .= '	<div id="modals'.$post->ID.'" class="modal">';
                $modals .= '  		<div class="modal-header">';
                $modals .= '    		<button  class="modal-action modal-close waves-effect close modal-close"><i  class="small material-icons white-text">close</i></button>';
                $modals .= '  		</div>';
                $modals .= '  		<div class="modal-content">';
                $modals .= 			get_the_content();
                $modals .= '  		</div>';
                $modals .= '	</div>';
                $box .= '</li>';
			}
			wp_reset_postdata();
		}
    $box .= '</ul>';
    $box .= '</div>';
    $box .= '</div>';
    $box .= $modals;
    return $box;
}

add_action('init', 'create_apoyo_type');

function create_apoyo_type() {
	register_post_type('apoyo', array(
		'labels' => array(
			'name' => __('Nos Apoyan'),
			'singular_name' => __('Apoyo'),
			'add_new' => _x('Añadir nuevo', 'book'),
			'add_new_item' => __('Añadir nuevo Apoyo'),
		),
		'public' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'supports' => array('title', 'thumbnail'),
		'register_meta_box_cb' => 'add_url_apoyo_metabox'
		)
	);
}

add_action('init', 'create_pelicula_type');

function create_pelicula_type() {
	register_post_type('pelicula', array(
		'labels' => array(
			'name' => __('Peliculas'),
			'singular_name' => __('Pelicula'),
			'add_new' => _x('Añadir nuevo', 'book'),
			'add_new_item' => __('Añadir nueva Pelicula'),
		),
		'hierarchical' => true,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'supports' => array('title', 'editor', 'thumbnail'),
		'register_meta_box_cb' => 'add_pelicula_metabox'
		)
	);

	register_post_type('teatro', array(
		'labels' => array(
			'name' => __('Teatros'),
			'singular_name' => __('Teatro'),
			'add_new' => _x('Añadir nuevo', 'book'),
			'add_new_item' => __('Añadir nuevo Teatro'),
		),
		'public' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'supports' => array('title'),
		'register_meta_box_cb' => 'add_teatro_metabox'
		)
	);

	$labels = array(
		'name'                       => _x( 'Categoria', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Categoria', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Buscar categorias', 'textdomain' ),
		'popular_items'              => __( 'Categorias populares', 'textdomain' ),
		'all_items'                  => __( 'Todas las categorias', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Editar categoria', 'textdomain' ),
		'update_item'                => __( 'Actualizar categoria', 'textdomain' ),
		'add_new_item'               => __( 'Agregar categoria', 'textdomain' ),
		'new_item_name'              => __( 'Nuevo nobre de categoria', 'textdomain' ),
		'separate_items_with_commas' => __( 'Categorias separadas por comas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Agregar o borrar categorias', 'textdomain' ),
		'choose_from_most_used'      => __( 'Escojer de las categorias mas usadas', 'textdomain' ),
		'not_found'                  => __( 'No se encontraron categorias.', 'textdomain' ),
		'menu_name'                  => __( 'Categorias', 'textdomain' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'pelicula-categoria' ),
	);

	register_taxonomy( 'pelicula-categoria', 'pelicula', $args );
}

function add_teatro_metabox() {
	add_meta_box('wpt_teatro_meta', 'Direccion del teatro', 'wpt_teatro_metabox', 'teatro', 'normal', 'default');
}

function wpt_teatro_metabox() {

	global $post;

	echo '<input type="hidden" name="teatrometa_noncename" id="teatrometa_noncename" value="' .
	wp_create_nonce(wp_basename(__FILE__)) . '" />';


	$ciudad = get_post_meta($post->ID, '_ciudad', true);
	$direccion = get_post_meta($post->ID, '_direccion', true);
	$telefono = get_post_meta($post->ID, '_telefono', true);
	// Echo out the field

	?>
	<span class="title">Ciudad</span>
	<select name="_ciudad" class="widefat">
		<option <?php echo $ciudad == 'Bogotá'? 'selected': ''; ?> >Bogotá</option>
		<option <?php echo $ciudad == 'Medellín'? 'selected': ''; ?> >Medellín</option>
		<option <?php echo $ciudad == 'Cali'? 'selected': ''; ?> >Cali</option>
		<option <?php echo $ciudad == 'Barranquilla'? 'selected': ''; ?> >Barranquilla</option>
		<option <?php echo $ciudad == 'Pereira'? 'selected': ''; ?> >Pereira</option>
		<option <?php echo $ciudad == 'Cajicá'? 'selected': ''; ?> >Cajicá</option>
		<option <?php echo $ciudad == 'Bucaramanga'? 'selected': ''; ?> >Bucaramanga</option>
	</select>
	<span class="title">Direccion</span>
	<input type="text" name="_direccion" class="widefat" value="<?php echo $direccion ?>" />
	<span class="title">Telefono</span>
	<input type="text" name="_telefono" class="widefat" value="<?php echo $telefono ?>" />
	<?php
}

function add_pelicula_metabox() {
	add_meta_box('wpt_pelicula_meta', 'Datos de la pelicula', 'wpt_pelicula_metabox', 'pelicula', 'normal', 'default');
	add_meta_box('wpt_pelicula_date_meta', 'Horarios de la pelicula', 'wpt_pelicula_hora_metabox', 'pelicula', 'normal', 'default');
}

function wpt_pelicula_metabox() {

	global $post;

	echo '<input type="hidden" name="pelimeta_noncename" id="urlmeta_noncename" value="' .
	wp_create_nonce(wp_basename(__FILE__)) . '" />';


	$director = get_post_meta($post->ID, '_director', true);
	$pais = get_post_meta($post->ID, '_pais', true);
	$youtube = get_post_meta($post->ID, '_youtube', true);

	// Echo out the field

	echo '<span class="title">Director</span><input type="text" name="_director" value="' . $director . '" class="widefat" />';
	echo '<span class="title">Pais</span><input type="text" name="_pais" value="' . $pais . '" class="widefat" />';
	echo '<span class="title">URL YouTube</span><input type="text" name="_youtube" value="' . $youtube . '" class="widefat" />';
}

function wpt_pelicula_hora_metabox	() {

	global $post;

	wp_enqueue_script('peliscript');

	echo '<input type="hidden" name="pelimeta_noncename" id="urlmeta_noncename" value="' .
	wp_create_nonce(wp_basename(__FILE__)) . '" />';


	
	$horarios = get_post_meta($post->ID, '_horarios', true);

	$teatros = new WP_Query(array(
			'post_type' => 'teatro',
			'posts_per_page' => -1
		));

	?>
	<span class="title" style="font-weight: bold;">Agregados</span>
	<div class="widefat" id="hor_cont">
		<?php 
		if ($horarios) {
			foreach ($horarios as $key => $horario) {
				$count = count($horarios);
				$meses = array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
				$date = new DateTime($horario['fecha'] . " " . $horario['hora']);
				$mes = date('n',$date->getTimestamp());
	   			$dia = date('d',$date->getTimestamp());
				$dia = $meses[$mes].' '. $dia;
				$hora = date_format($date, 'g:ia');
				echo '<p class="widefat horario" index="'.($key + 1).'">'. $horario['ciudad'] .', '. $dia .', '. $horario['teatro'] .', '. $hora .'<a index="'.($key + 1).'" class="hor_del"><img src="'. get_template_directory_uri() .'/img/delete.svg" /></a></p>';
				echo '<input type="hidden" name="_ciudad_'.($key + 1).'" index="'.($key + 1).'" value="'.$horario['ciudad'].'"/>';
				echo '<input type="hidden" name="_teatro_'.($key + 1).'" index="'.($key + 1).'" value="'.$horario['teatro'].'"/>';
				echo '<input type="hidden" name="_fecha_'.($key + 1).'" index="'.($key + 1).'" value="'.$horario['fecha'].'"/>';
				echo '<input type="hidden" name="_hora_'.($key + 1).'" index="'.($key + 1).'" value="'.$horario['hora'].'"/>';

			}
		} ?>
	</div><br><br>
	<script type="text/javascript">
		window.template_url = "<?php bloginfo( 'template_directory' );?>";
	</script>
	<style type="text/css">
		.horario{
			padding-top: 5px;
			padding-bottom: 5px;

		}
		.horario a{
			float: right;
			cursor: pointer;
		}
	</style>
	<span class="title">Ciudad</span>
	<select name="_ciudad" class="widefat" id="ciudad_sel">
		<option>Bogotá</option>
		<option>Medellín</option>
		<option>Cali</option>
		<option>Barranquilla</option>
		<option>Pereira</option>
		<option>Cajicá</option>
		<option>Bucaramanga</option>
	</select>
	<span class="title">Teatro</span>
	<select name="_teatro" value="" class="widefat" id="teatro_sel">
		<?php while ($teatros->have_posts()) {
			$teatros->the_post();
			$ciudad = get_post_meta($post->ID, '_ciudad', true);
			echo '<option ciudad="'. $ciudad .'">'. get_the_title() .'</option>';
		}
		?>
	</select>
	<span class="title">Fecha</span>
	<input type="date" name="_fecha" value="" class="widefat" id="hor_fecha" />
	<span class="title">Hora</span>
	<input type="time" name="_hora" value="" class="widefat" id="hor_time" />
	<input type="hidden" name="_num_hor" id="num_hor" value="<?php echo count($horarios); ?>"/><br><br>
	<a class="button button-primary button-large add">Agregar</a>
	<?php
}

add_action('admin_init', 'my_peliscript_init');

function my_peliscript_init(){
	/* Registro de nuestro script. */
	wp_register_script('peliscript', get_template_directory_uri() . '/js/peliculas_admin.js');
}

function wpt_save_pelicula_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	echo $post->post_type;
	if ( !wp_verify_nonce( $_POST['pelimeta_noncename'], wp_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$events_meta['_director'] = $_POST['_director'];
	$events_meta['_pais'] = $_POST['_pais'];
	$events_meta['_youtube'] = $_POST['_youtube'];

	$count = intval($_POST['_num_hor']);
	$horarios = array();

	for ($i=1; $i <= $count; $i++) { 
		array_push(
			$horarios,
			array(
				'fecha' => $_POST['_fecha_'. $i], 
				'hora' => $_POST['_hora_'. $i], 
				'teatro' => $_POST['_teatro_'. $i],
				'ciudad' => $_POST['_ciudad_'. $i]
			)
		);
	}
	update_post_meta($post->ID, '_horarios', $horarios);
	// Add values of $events_meta as custom fields
	
	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}

function wpt_save_teatro_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	echo $post->post_type;
	if ( !wp_verify_nonce( $_POST['teatrometa_noncename'], wp_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$events_meta['_ciudad'] = $_POST['_ciudad'];
	$events_meta['_direccion'] = $_POST['_direccion'];
	$events_meta['_telefono'] = $_POST['_telefono'];
	
	// Add values of $events_meta as custom fields
	
	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}

add_action('save_post', 'wpt_save_teatro_meta', 1, 2);
add_action('save_post', 'wpt_save_pelicula_meta', 1, 2);

//agregando slider tipo para slider superior
add_action('init', 'create_slide_superior_type');

function create_slide_superior_type() {
	register_post_type('slide-superior', array(
		'labels' => array(
			'name' => __('Slider Superior'),
			'singular_name' => __('Slide'),
			'add_new' => _x('Añadir nuevo', 'book'),
			'add_new_item' => __('Añadir nuevo Slide'),
		),
		'show_ui' => true,
		'show_in_menu' => 'euro-silides.php',
		'public' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'supports' => array('title', 'thumbnail', 'editor')
		)
	);
}

//agregando tipo para patrocinadores
add_action('init', 'create_slide_patrocinador_type');

function create_slide_patrocinador_type() {
	register_post_type('slide-patrocinador', array(
		'labels' => array(
			'name' => __('Slider Patrocinador'),
			'singular_name' => __('Slide'),
			'add_new' => _x('Añadir nuevo', 'book'),
			'add_new_item' => __('Añadir nuevo Slide'),
		),
		'show_ui' => true,
		'show_in_menu' => 'euro-silides.php',
		'public' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'supports' => array('title', 'thumbnail'),
		'register_meta_box_cb' => 'add_url_patrocinador_metabox'
		)
	);
}

add_action('init', 'create_slide_equipo_type');

function create_slide_equipo_type() {
	register_post_type('slide-equipo', array(
		'labels' => array(
			'name' => __('Slider Equipo'),
			'singular_name' => __('Slide'),
			'add_new' => _x('Añadir nuevo', 'book'),
			'add_new_item' => __('Añadir nuevo Slide'),
		),
		'show_ui' => true,
		'show_in_menu' => 'euro-silides.php',
		'public' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'supports' => array('title', 'thumbnail'),
		'register_meta_box_cb' => 'add_url_equipo_metabox'
		)
	);
}

function add_slide_menu() {
  add_menu_page( 'Slider Eurocine', 'Sliders Eurocine', 'manage_options', 'euro-silides.php');
}

add_action('admin_menu', 'add_slide_menu');

function add_url_patrocinador_metabox() {
	add_meta_box('wpt_slide_patrocinador_url', 'URL del Slide', 'wpt_slide_url', 'slide-patrocinador', 'normal', 'default');
}

function add_url_equipo_metabox() {
	add_meta_box('wpt_slide_equipo_url', 'URL del Slide', 'wpt_slide_url', 'slide-equipo', 'normal', 'default');
}

function add_url_apoyo_metabox() {
	add_meta_box('wpt_apoyo_url', 'URL del Apoyo', 'wpt_slide_url', 'apoyo', 'normal', 'default');
}

function wpt_slide_url() {

	global $post;

	echo '<input type="hidden" name="urlmeta_noncename" id="urlmeta_noncename" value="' .
	wp_create_nonce(wp_basename(__FILE__)) . '" />';


	// Get the location data if its already been entered
	$url = get_post_meta($post->ID, '_url', true);

	// Echo out the field

	echo '<span class="title">URL</span><input type="text" name="_url" value="' . $url . '" class="widefat" />';
}

function wpt_save_puntos_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	echo $post->post_type;
	if ( !wp_verify_nonce( $_POST['urlmeta_noncename'], wp_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$events_meta['_url'] = $_POST['_url'];
	
	// Add values of $events_meta as custom fields
	
	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}

}

add_action('save_post', 'wpt_save_puntos_meta', 1, 2); // save the custom fields


add_action( 'load-post.php', 'subtitulo_meta_boxes_setup' );
add_action( 'load-post-new.php', 'subtitulo_meta_boxes_setup' );

function subtitulo_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'add_subtitulo_metabox' );
}

function add_subtitulo_metabox() {
	add_meta_box('wpt_post_subtitulo', 'Subtitulo', 'wpt_subtitulo', 'post', 'normal', 'default');
}

function wpt_subtitulo() {

	global $post;

	echo '<input type="hidden" name="submeta_noncename" id="submeta_noncename" value="' .
	wp_create_nonce(wp_basename(__FILE__)) . '" />';


	// Get the location data if its already been entered
	$subtitulo = get_post_meta($post->ID, '_subtitulo', true);

	// Echo out the field

	echo '<span class="title">Subtitulo</span><input type="text" name="_subtitulo" value="' . $subtitulo . '" class="widefat" />';
}

function wpt_save_subtitulo_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	echo $post->post_type;
	if ( !wp_verify_nonce( $_POST['submeta_noncename'], wp_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$events_meta['_subtitulo'] = $_POST['_subtitulo'];
	
	// Add values of $events_meta as custom fields
	
	foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}

}

add_action('save_post', 'wpt_save_subtitulo_meta', 1, 2);


function theme_settings_page() {
	?>
	    <div class="wrap">
	    <h1>Configuración de Eurocine</h1>
	    <form method="post" action="options.php" enctype="multipart/form-data">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");
	            submit_button(); 
	        ?>          
	    </form>
		</div>
	<?php
}

function add_theme_menu_item() {
	add_menu_page("Configuraciones Eurocine", "Configuraciones", "manage_options", "theme-panel", "theme_settings_page", null, 99);
}

add_action("admin_menu", "add_theme_menu_item");


function display_home_pauta_element() {
	?>
    	<input type="text" name="home_url" id="home_url" value="<?php echo get_option('home_url'); ?>" />
    <?php
}

function display_amigos_pauta_element() {
	?>
    	<input type="text" name="amigos_url" id="amigos_url" value="<?php echo get_option('amigos_url'); ?>" />
    <?php
}

function display_eurofilmpedia_pauta_element() {
	?>
    	<input type="text" name="eurofilmpedia_url" id="eurofilmpedia_url" value="<?php echo get_option('eurofilmpedia_url'); ?>" />
    <?php
}

function display_festival_pauta_element() {
	?>
    	<input type="text" name="festival_url" id="festival_url" value="<?php echo get_option('festival_url'); ?>" />
    <?php
}

function display_programacion_pauta_element() {
	?>
    	<input type="text" name="programacion_url" id="programacion_url" value="<?php echo get_option('programacion_url'); ?>" />
    <?php
}

function display_amigos_menu_element() {
	?>
    	<input type="text" name="amigos_menu_url" id="amigos_menu_url" value="<?php echo get_option('amigos_menu_url'); ?>" />
    <?php
}

function display_festival_menu_element() {
	?>
    	<input type="text" name="festival_menu_url" id="festival_menu_url" value="<?php echo get_option('festival_menu_url'); ?>" />
    <?php
}

function display_eurofilmpedia_menu_element() {
	?>
    	<input type="text" name="eurofilmpedia_menu_url" id="eurofilmpedia_menu_url" value="<?php echo get_option('eurofilmpedia_menu_url'); ?>" />
    <?php
}

function home_img_display(){
	?>
		<img src="<?php echo get_option('img_home'); ?>" width="100" /><br>
        <input type="file" name="img_home" />
   <?php
}

function handle_img_home_upload(){
	return handle_img_upload("img_home");
}

function amigos_img_display(){
	?>
		<img src="<?php echo get_option('img_amigos'); ?>" width="100" /><br>
        <input type="file" name="img_amigos" />
   <?php
}

function handle_img_amigos_upload(){
	return handle_img_upload("img_amigos");
}

function eurofilmpedia_img_display(){
	?>
		<img src="<?php echo get_option('img_eurofilmpedia'); ?>" width="100" /><br>
        <input type="file" name="img_eurofilmpedia" />
   <?php
}

function handle_img_eurofilmpedia_upload(){
	return handle_img_upload("img_eurofilmpedia");
}

function festival_img_display(){
	?>
		<img src="<?php echo get_option('img_festival'); ?>" width="100" /><br>
        <input type="file" name="img_festival" />
   <?php
}

function programacion_img_display(){
	?>
		<img src="<?php echo get_option('img_programacion'); ?>" width="100" /><br>
        <input type="file" name="img_programacion" />
   <?php
}

function festival_prog_display(){
	?>
        <select name="page_programacion">
		<?php
			$pages = new WP_Query(array(
				'post_type' => 'page',
				'posts_per_page' => -1
			));
			while ($pages->have_posts()) {
				$pages->the_post();
				$selected = get_option('page_programacion') == get_the_ID()? 'selected':'';
				echo '<option value="'. get_the_ID() .'" '. $selected .'>'. get_the_title() .'</option>';
			}
			wp_reset_postdata();
		?>
	</select>
   <?php
}

function home_category_display(){
	?>
        <select name="category_home">
		<?php
			$categories = get_categories( array(
                'hide_empty'   => 0
            ));
			foreach( $categories as $category ) {
				$t_id = $category->term_id;
				$selected = get_option('category_home') == $t_id? 'selected':'';
				echo '<option value="'. $t_id .'" '. $selected .'>'. esc_html($category->name) .'</option>';
			}
		?>
	</select>
   <?php
}

function handle_img_festival_upload(){
	return handle_img_upload("img_festival");
}

function handle_img_programacion_upload(){
	return handle_img_upload("img_programacion");
}

function handle_img_upload($name){
	global $option;
	if($_FILES[$name]["tmp_name"]){
		$urls = wp_handle_upload($_FILES[$name], array('test_form' => FALSE));
		$temp = $urls["url"];
		return $temp; 
	}
	return get_option($name);
}

function display_theme_panel_fields() {
	add_settings_section("section", "Pautas", null, "theme-options");

	add_settings_field("home_url", "Home pauta Url", "display_home_pauta_element", "theme-options", "section");
	add_settings_field("img_home", "Home imagen pauta", "home_img_display", "theme-options", "section");
    add_settings_field("amigos_url", "Nuestros amigos pauta Url", "display_amigos_pauta_element", "theme-options", "section");
    add_settings_field("img_amigos", "Nuestros amigos imagen pauta", "amigos_img_display", "theme-options", "section");
    add_settings_field("eurofilmpedia_url", "Eurofilmpedia pauta Url", "display_eurofilmpedia_pauta_element", "theme-options", "section");
    add_settings_field("img_eurofilmpedia", "Eurofilmpedia imagen pauta", "eurofilmpedia_img_display", "theme-options", "section");
    add_settings_field("festival_url", "Festival pauta Url", "display_festival_pauta_element", "theme-options", "section");
    add_settings_field("img_festival", "Festival imagen pauta", "festival_img_display", "theme-options", "section");
    add_settings_field("programacion_url", "Programacion pauta Url", "display_programacion_pauta_element", "theme-options", "section");
    add_settings_field("img_programacion", "Programacion imagen pauta", "programacion_img_display", "theme-options", "section");
    add_settings_field("page_programacion", "Festival programacion pagina", "festival_prog_display", "theme-options", "section");
    add_settings_field("category_home", "Home categoria", "home_category_display", "theme-options", "section");
    
    add_settings_field("amigos_menu_url", "Amigos menu Url", "display_amigos_menu_element", "theme-options", "section");
    add_settings_field("festival_menu_url", "Festival menu Url", "display_festival_menu_element", "theme-options", "section");
    add_settings_field("eurofilmpedia_menu_url", "Eurofilmpedia menu Url", "display_eurofilmpedia_menu_element", "theme-options", "section");

    register_setting("section", "amigos_menu_url");
    register_setting("section", "festival_menu_url");
    register_setting("section", "eurofilmpedia_menu_url");

    register_setting("section", "category_home");
    register_setting("section", "page_programacion");
    register_setting("section", "home_url");
    register_setting("section", "amigos_url");
    register_setting("section", "eurofilmpedia_url");
    register_setting("section", "festival_url");
    register_setting("section", "programacion_url");
    register_setting("section", "img_home", "handle_img_home_upload");
    register_setting("section", "img_amigos", "handle_img_amigos_upload");
    register_setting("section", "img_eurofilmpedia", "handle_img_eurofilmpedia_upload");
    register_setting("section", "img_festival", "handle_img_festival_upload");
    register_setting("section", "img_programacion", "handle_img_programacion_upload");
}

add_action("admin_init", "display_theme_panel_fields");
?>