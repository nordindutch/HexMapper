<?php 
global $user_values;
$user_values = array(
    'creator' => false,
    'contributor' => false
);
function enqueue_media_uploader()
{
    //this function enqueues all scripts required for media uploader to work
    wp_enqueue_media();
};

add_action("wp_enqueue_scripts", "enqueue_media_uploader");
add_action( 'wp_enqueue_scripts', 'enqueue_properties_scripts' );

function enqueue_properties_scripts() {
    if ( 'hexmap' === get_post_type() ) {
        wp_enqueue_script( 'hexmap', get_template_directory_uri() . '/js/hexmap.js', array ( 'jquery' ));
    }
}
function startwordpress_scripts() {
	wp_enqueue_script( 'main', get_template_directory_uri() . '/js/main.js', array ( 'jquery' ));
	wp_enqueue_script( 'hexmap', get_template_directory_uri() . '/js/hexmap.js', array ( 'jquery' ));
	wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
	wp_localize_script( 'main', 'getHex', array( 
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'query_vars' => json_encode( $wp_query->query )
		) 
	);
};
	
add_theme_support( 'custom-logo' );
function themename_custom_logo_setup() {
	$defaults = array(
	'height'      => 100,
	'width'       => 400,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
   'unlink-homepage-logo' => false, 
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'themename_custom_logo_setup' );

//Function to add 0s to short numbers
function alz($value, $threshold = 3) {
    return sprintf('%0' . $threshold . 's', $value);
}
function genRand($length = 10) {
    return substr(str_shuffle(MD5(microtime())), 0, $length);
}
//Register ajax actions
add_action( 'wp_enqueue_scripts', 'startwordpress_scripts' );
add_action( 'wp_ajax_nopriv_load_hex_info', 'load_hex_info' );
add_action( 'wp_ajax_load_hex_info', 'load_hex_info' );
add_action( 'wp_ajax_nopriv_register_user', 'register_user' );
add_action( 'wp_ajax_register_user', 'register_user' );
add_action( 'wp_ajax_nopriv_login', 'login' );
add_action( 'wp_ajax_login', 'login' );
add_action( 'wp_ajax_nopriv_upload_file', 'upload_file' );
add_action( 'wp_ajax_upload_file', 'upload_file' );
add_action( 'wp_ajax_nopriv_create_hexmap', 'create_hexmap' );
add_action( 'wp_ajax_create_hexmap', 'create_hexmap' );
add_action( 'wp_ajax_nopriv_create_item', 'create_item' );
add_action( 'wp_ajax_create_item', 'create_item' );
add_action( 'wp_ajax_nopriv_update_hex', 'update_hex' );
add_action( 'wp_ajax_update_hex', 'update_hex' );
add_action( 'wp_ajax_nopriv_add_note', 'add_note' );
add_action( 'wp_ajax_add_note', 'add_note' );
add_action( 'wp_ajax_nopriv_update_terrain', 'update_terrain' );
add_action( 'wp_ajax_update_terrain', 'update_terrain' );
add_action( 'wp_ajax_nopriv_add_hex', 'add_hex' );
add_action( 'wp_ajax_add_hex', 'add_hex' );
add_action( 'wp_ajax_nopriv_update_hidden', 'update_hidden' );
add_action( 'wp_ajax_update_hidden', 'update_hidden' );

function add_hex(){
	update_field('columns_num', $_POST['columns'], $_POST['post_id']);
	update_field('rows_num', $_POST['rows'], $_POST['post_id']);
	
	foreach($_POST['hexes'] as $hex){
		$rand = genRand(12);
			$hex_post = array(
				'post_title'	=>$rand,
				'post_status'   => 'publish',          
				'post_type'     =>  'hex',
				'post_name'		=> $rand
			);
			$pid = wp_insert_post($hex_post);
			update_field('column',intval($hex['column_hex']),$pid);
			update_field('row',intval($hex['row_hex']),$pid);
			update_field('terrain','plains',$pid);
			update_field('description', 'No description yet...', $pid);
			update_field('hidden',1,$pid);
		$the_hexes = get_field('hexes', $_POST['post_id']);
		array_push($the_hexes, $pid);
		
		update_field('hexes',$the_hexes, $_POST['post_id']);
	}
	$map = new Hexmap($_POST['post_id']);
	$map->build_map($_POST['coords'][ 'top'], $_POST['coords']['left'] );
	die('');
}

function update_hidden(){

	$info = $_POST['hidden'];

	foreach($info as $hid){
		update_field('hidden', $hid['hidden'], get_page_by_title($hid['hex_id'], 'OBJECT', 'hex')->ID);

	};
	die('');
}

function add_note(){
	$title = stripslashes($_POST['title']);
	$content = stripslashes($_POST['content']);
	?>
	<div class="note">
		<span>
			<span>
				<b class="note-title"><?php echo $title; ?></b> by 
				<span class="note-writer" data-writer="<?php echo get_current_user_id() ?>"><?php echo wp_get_current_user()->nickname; ?></span>
			</span>
			<span class="hide-item"></span>
			<span class="remove-item"></span>

		</span>
		<p class="note-content"><?php echo esc_html($content); ?></p>

	</div>
	<?php
	die('');
}
function create_hexmap(){
	$new_hexmap = array(
		'post_title'	=> $_POST['title'],
		'post_status'   => 'publish',          
		'post_type'     =>  'hexmap',
		'post_name'		=> genRand(16)
	);
	$pid = wp_insert_post($new_hexmap);
	$join_code = "jc_";
	$join_code .= genRand(6);
	update_field('creator', get_current_user_id() ,$pid );
	update_field('hex', $_POST['col']*$_POST['row'] ,$pid );
	update_field( 'columns_num', 5, $pid);
	update_field(  'rows_num', 5, $pid);
	update_field(  'join_code', $join_code ,$pid);
	$the_hexmap = new Hexmap($pid);
	$the_hexmap->initial();
	set_post_thumbnail( $pid, $_POST['img_id'] );
	echo "Success";
}
function update_terrain(){
	$info = $_POST['terrain'];

	foreach($info as $hexa){
		update_field('terrain', $hexa['terrain'], get_page_by_title($hexa['hex_id'], 'OBJECT', 'hex')->ID);

	};
}
function upload_file(){
	if($_POST){
		if (!function_exists('wp_generate_attachment_metadata')){
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
		if($_FILES)
		{
			foreach ($_FILES as $file => $array)
			{
				if($_FILES[$file]['error'] !== UPLOAD_ERR_OK){return "upload error : " . $_FILES[$file]['error'];}//If upload error
				$attach_id = media_handle_upload($file,$new_post);
				echo json_encode(
					array(
						"url"	=> wp_get_attachment_url($attach_id),
						"id"	=> $attach_id
					)
				);//upload file URL
			}
		}
		}
		die();
};

function load_hex_info() {
	$post_id = $_POST['post_id'];
	$hex_id = $_POST['hex'];
	$map = new Hexmap($post_id);
	echo $map->hex_info($hex_id);

    die();
}
add_theme_support( 'post-thumbnails' );

// Register a new user
function register_user(){
	$new_user = array(
		'user_login' =>	$_POST['username'],
		'user_pass ' =>	$_POST['password'],
		'user_email' =>	$_POST['email'],
		'first_name' =>	$_POST['first_name'],
		'last_name' =>	$_POST['last_name'],
		'role'		 => 'subscriber'
	);
	$your_user =  wp_insert_user( $new_user );
	if(!is_wp_error($your_user)){
		$creds = array(
			'user_login'    => $new_user['user_login'],
			'user_password' => $new_user['user_pass'],
			'remember'      => true
		);
		$user = wp_signon($creds, false);
	echo json_encode(array(
		'success' => true,
		'id'	  => $your_user,
		'name'	  => $new_user['first_name'],
	));
	
	}
		else{
			$returned_object = array(
				'success' => false,
				'error'	  => $your_user->get_error_message()
			);
			echo json_encode($returned_object);
		}
		die();
	}

add_filter('show_admin_bar', '__return_false');
add_action('wp_login_failed', '_login_failed_redirect');


//Redirect Failed Login
function _login_failed_redirect( $username ){
	$url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
	$current_post_id = url_to_postid( $url );

  //get your page by slug and then its permalink
  $post = get_page_by_path('slug');

  //Or you can get your page ID, if you are assigning a custom template to a page.
  $redirect_page = !empty ( $post ) ? get_permalink ( $current_post_id ) : site_url();

  $user = get_user_by('login', $username );

  if(!$user){
    //Username incorrect
    wp_redirect($redirect_page .'?login_error=1');

  }else{
   //Username Password combination incoorect
    wp_redirect($redirect_page .'?login_error=2');
  }

}
function create_item(){
	$new_args = array(
		'post_type' => $_POST['type'],
		'post_title'=> $_POST['name'],
		'post_status'   => 'publish', 
		'post_name'	=> genRand(16)
	);
	$new_item = wp_insert_post($new_args);
	update_field( 'item_id', genRand(16), $new_item);
	update_field( 'creator', get_current_user_id(), $new_item);
	$map = new Hexmap(get_the_ID());
	$map->load_items();
	die('');
}
class Hexmap{
	public $hexes;
	public $coords;
	public $id;
	public $creator;
	public $contributor;
	function __construct($identifier)
	{

		$this->creator = false;
		$this->contributor = false;
		$this->id = $identifier;
		$col = get_field('columns_num', $this->id);
		$row = get_field('rows_num', $this->id);
		$this->coords = array(
			'col' => $col,
			'row' => $row
		);
		$this->isCreator();
	}
	public function initial()
	{
		$poppy = [];
		$i=0;
		$current_col = 1;
		$current_row = 1;
		$total_hex = $this->coords['col'] * $this->coords['row'];
		while($i < $total_hex){

			$rand = genRand(12);
			$hex_post = array(
				'post_title'	=>$rand,
				'post_status'   => 'publish',          
				'post_type'     =>  'hex',
				'post_name'		=> $rand
			);
			$pid = wp_insert_post($hex_post);
			update_field('column',intval($current_col),$pid);
			update_field('row',intval($current_row),$pid);
			update_field('terrain','plains',$pid);
			update_field('hidden',1,$pid);
			array_push($poppy, $pid);
			//add_row('hex', $hex, $this->id);
			
			$i++;

			if($current_row == $this->coords['row']){
				$current_col++;
				$current_row = 1;
			}
			else{
				$current_row++;
			}
		}
		update_field('hexes', $poppy,  $this->id);
	}
	public function build_map($top = "0px", $left = "0px")
	{
		?>
		
			<div class="the_map" style="display: none;top: <?php echo $top ?>; left:<?php echo $left ?>;" data-total-col="<?php echo get_field('columns_num', $this->id); ?>" data-total-row="<?php echo get_field('rows_num', $this->id); ?>">
			
				<div class="inner-hexmap">
				
				<?php
				$x = 0;

				foreach(get_field('hexes', $this->id) as $the_hex){

					?>

					<div data-hidden="<?php if(get_field('hidden',$the_hex)){echo get_field('hidden',$the_hex);}else{echo 0;} ?>" data-terrain="<?php echo get_field('terrain', $the_hex)['value']; ?>" class="hex-top<?php if(get_field('hidden', $the_hex) && !is_user_logged_in(  )){echo ' hidden';} ?> " 
						<?php if(get_sub_field('name')){echo "name='".get_field('name', $the_hex)."'";} ?> data-col="<?php echo get_field('column', $the_hex) ?>" data-row="<?php echo get_field('row', $the_hex) ?>">
								<div class="hex-container tile "  <?php if(is_user_logged_in(  ) || !get_field('hidden', $the_hex)){ echo 'id="'.get_post_field( 'post_name', $the_hex ).'"'  ;} ?> data-hexkey="<?php echo esc_html(alz(get_field('column', $the_hex)).".".alz(get_field('row', $the_hex))) ?>">
									<div class="hex-wrap">
										<div class="hex"></div>
									</div>
										
								</div>
			
							</div>
				
					<?php
				}


				$x = 0;
				while($x < $this->coords['col']){
					?>
					<div class="column" data-column="<?php echo $x + 1; ?>">
						<div class="hex-top add-hex add-hex-row" data-row="<?php echo $this->coords['row'] + 1; ?>">
							<div class="hex-container tile">
								<div class="hex-wrap">
									<div class="hex add-hex"></div>
								</div>
							</div>
						</div>
					</div>
					<?php
					$x++;
				}
				?>
				<div class="column" data-column="<?php echo $x + 1; ?>">
				<?php
				$x = 0;
				while($x < $this->coords['row']+1){
					?>
					<div class="hex-top add-hex add-hex-column <?php if($this->coords['row'] == $x){echo "add-hex-row";} ?>" data-row="<?php echo $x + 1; ?>">
						<div class="hex-container tile">
							<div class="hex-wrap">
								<div class="hex add-hex"></div>
							</div>
						</div>
					</div>
					
					<?php
					$x++;
				}
				?>
				</div>
				<?php

				?>
				</div>
			</div>
		
		<?php
	}
	public function hex_info($hex){
		$hex_query = new WP_Query( array(
			'name' => $hex,
			'post_type' => 'hex'
		) );

		if ( $hex_query->have_posts() ) {
			
			while ( $hex_query->have_posts() ) {
				$hex_query->the_post();
				get_template_part('template-parts/content', 'hex', 
				array(
					'creator' => $this->creator,
					'contributor' =>  $this->contributor
				));
			}
			
		}
		wp_reset_postdata(  );
		/*if(have_rows('hex', $this->id)){

		while(have_rows('hex', $this->id)){
			the_row();
			if(get_sub_field('column_hex') == $col && get_sub_field('row_hex') == $row){
				get_template_part('template-parts/content', 'hex', 
				array(
					'creator' => $this->creator,
					'contributor' =>  $this->contributor
				));
				break;
			}
		}}
		*/
	}
	public function load_items(){
		$args = array(
			'post_type' => array('event', 'adventure-site', 'npc', 'landmark','monster'),
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation'	=> 'AND',
				array(
					'key' => 'creator',
					'value' => get_current_user_id(),
					'compare' => '=',
				)
			  ),
		);
		$the_query = new WP_Query( $args );
		if( $the_query->have_posts() ){
			while($the_query->have_posts()){
				$the_query->the_post();
				$postType = get_post_type_object(get_post_type());
				?>
				<a class="item" data-type="<?php if ($postType) {
						echo esc_html($postType->rewrite['slug']);
					} ?>" data-item="<?php echo get_field('item_id'); ?>">
					<span><?php the_title(); ?></span>
					<span><?php 
					if ($postType) {
						echo esc_html($postType->labels->singular_name);
					} 
					;?></span>
				</a>
				<?php
	
				wp_reset_postdata(  );
			}
		}
	}
	public function isCreator(){
		if(is_user_logged_in(  )){
			if(get_current_user_id() == get_field('creator', $this->id)['ID'] || get_current_user_id() == 1){
				$this->creator = true;
			}
		}
		foreach(get_field('contributors', $this->id) as $cont){

            if($cont['ID'] == get_current_user_id()){
                $this->contributor = true;
                break;
            }
        }
	}
}

function update_hex(){
	$hexer =  get_page_by_title($_POST['hex_id'], 'OBJECT', 'hex')->ID;
	$desc = strip_tags($_POST['description'], "<p>");
	if($desc == ""){
		$desc = "No description yet...";
	}
	$notes = $_POST['notes'];
	echo json_encode($notes);
	update_field('notes', $notes, $hexer);
	update_field('description', $desc, $hexer);

	die('');
}

