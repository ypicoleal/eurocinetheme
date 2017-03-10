
<?php

add_theme_support( 'post-thumbnails' );
add_shortcode( 'amigos', 'amigos_fn' );
add_shortcode( 'home', 'home_fn' );

function amigos_fn($atts){
	$template = file_get_contents(get_template_directory() . '/html/nuestros_amigos.html', true);
	$url_template = get_bloginfo( 'template_directory' );
	$template = str_replace("{{template_directory}}", $url_template, $template);
	$template = str_replace("{{caja-apoyo}}", get_apoyos(), $template);
	$template = str_replace("{{slider-patrocinador}}", get_slide('slide-patrocinador'), $template);
	$template = str_replace("{{slider-equipo}}", get_slide('slide-equipo'), $template);
	$template = str_replace("{{slider-superior}}", get_slide_superior(), $template);
	return $template;
}

function home_fn($atts){
	$template = file_get_contents(get_template_directory() . '/html/home.html', true);
	$url_template = get_bloginfo( 'template_directory' );
	$template = str_replace("{{template_directory}}", $url_template, $template);
	$template = str_replace("{{contenidos}}", get_contenidos(), $template);
	//$template = str_replace("{{slider-patrocinador}}", get_slide('slide-patrocinador'), $template);
	//$template = str_replace("{{slider-equipo}}", get_slide('slide-equipo'), $template);
	$template = str_replace("{{slider-superior}}", get_slide_superior(), $template);
	return $template;
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
			    $box .='        	<img src="'. get_the_post_thumbnail_url() .'" style="width:100%; height:100%;">';
			    $box .='        </div>';
			    $box .='        <div class="col s12 cont-rigth">';
			    $box .='          <p> <div class="verde">'. get_the_category()[0]->name .'</div> '. get_the_date() .'</p>';
			    $box .='          <div class="titulo-media">'. get_the_title() .'</div>';
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
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$box .= '<div class="col l2 s6 white caja-p" data-target="modal'.$post->ID.'" style="background-image: url('. get_the_post_thumbnail_url() .');">'; 
				$box .= '</div>';
				$box .= '<div id="modal'.$post->ID.'" class="modal">';
                $box .= '  <div class="modal-header">';
                $box .= '    <button  class="modal-action modal-close waves-effect close modal-close"><i  class="small material-icons white-text">close</i></button>';
                $box .= '  </div>';
                $box .= '  <div class="modal-content">';
                $box .= get_the_content();
                $box .= '  </div>';
                $box .= '</div>';
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
	            $box .= '	    <a href="#modals'.$post->ID.'" class="leer-mas black-text modal-trigger">Leer Mas</a>';
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
		'supports' => array('title', 'thumbnail', 'editor')
		)
	);
}


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
  // add_submenu_page() if you want subpages, but not necessary
}

add_action('admin_menu', 'add_slide_menu');


function add_url_patrocinador_metabox() {
	add_meta_box('wpt_slide_patrocinador_url', 'URL del Slide', 'wpt_slide_url', 'slide-patrocinador', 'normal', 'default');
}

function add_url_equipo_metabox() {
	add_meta_box('wpt_slide_equipo_url', 'URL del Slide', 'wpt_slide_url', 'slide-equipo', 'normal', 'default');
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

?>