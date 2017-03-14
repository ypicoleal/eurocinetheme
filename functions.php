
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
	$template = str_replace("{{pauta-img}}", get_option('img_amigos'), $template);
	$template = str_replace("{{pauta-url}}", get_option('amigps_url'), $template);
	return $template;
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

function register_my_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Menu Principal' ),
    )
  );
}
add_action( 'init', 'register_my_menus' );

function home_fn($atts){
	$template = file_get_contents(get_template_directory() . '/html/home.html', true);
	$url_template = get_bloginfo( 'template_directory' );
	$template = str_replace("{{template_directory}}", $url_template, $template);
	$template = str_replace("{{contenidos}}", get_contenidos(), $template);
	$template = str_replace("{{pauta-img}}", get_option('img_home'), $template);
	$template = str_replace("{{pauta-url}}", get_option('home_url'), $template);
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
			$counter = 0;
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$url = get_post_meta($post->ID, '_url', true);
				if ($counter == 0) {
					$box .='<div class="col s6 m12 l12 padding-0">'. PHP_EOL;
			    	$box .='    <div class="row margin-bottom-0 fila1">'. PHP_EOL;
				}
				
			    $box .='        <div class="col l2 s12 white caja-p" >'. PHP_EOL;
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
		'supports' => array('title', 'thumbnail'),
		'register_meta_box_cb' => 'add_url_apoyo_metabox'
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
	    <h1>Configuración de pautas</h1>
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
	add_menu_page("Pautas", "Pautas", "manage_options", "theme-panel", "theme_settings_page", null, 99);
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

function handle_img_festival_upload(){
	return handle_img_upload("img_festival");
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

    register_setting("section", "home_url");
    register_setting("section", "amigos_url");
    register_setting("section", "eurofilmpedia_url");
    register_setting("section", "festival_url");
    register_setting("section", "img_home", "handle_img_home_upload");
    register_setting("section", "img_amigos", "handle_img_amigos_upload");
    register_setting("section", "img_eurofilmpedia", "handle_img_eurofilmpedia_upload");
    register_setting("section", "img_festival", "handle_img_festival_upload");
}

add_action("admin_init", "display_theme_panel_fields");
?>