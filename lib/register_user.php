<?php
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
add_action( 'wp_ajax_nopriv_register_user', 'register_user' );
add_action( 'wp_ajax_register_user', 'register_user' );
?>
