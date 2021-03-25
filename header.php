<html>


<head>
<title>Hexmapper</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
<?php wp_head(); ?> 
</head>
<body class="<?php echo get_post_type(  ) ?>">
<header>

<?php
$map = new Hexmap(get_the_ID());
$editor = $map->creator;
$contributor = $map->contributor;
if(get_post_type() != "hexmap"){
    
    if ( function_exists( 'the_custom_logo' ) ) {
        the_custom_logo();
    }
    ?>

    <?php if(get_post()->post_title != 'User Registration'){ ?>
    <nav class="nav-bar">
    <?php
    if( ! is_user_logged_in() ){
        if(!is_front_page(  )){
        ?>
        <span>
            <a class="menu-item" href="<?php echo get_home_url( ); ?>/?user-register=1">Sign Up</a>
        </span>
        
        <span class="menu-login dropdown <?php if( isset( $_GET['login_error'] )){echo "active";} ?>">
            <span class="menu-item ">Log In</span>
            <div class="sub-menu">
            <?php
            //$output = wp_login_form( $args );
            wp_login_form( 
        
                array(
                    'echo'           => true,
                    // Default 'redirect' value takes the user back to the request URI.
                    'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                    'form_id'        => 'loginform',
                    'label_username' => __( 'Username' ),
                    'label_password' => __( 'Password' ),
                    'label_remember' => __( 'Remember Me' ),
                    'label_log_in'   => __( 'Log In' ),
                    'id_username'    => 'user_login',
                    'id_password'    => 'user_pass',
                    'id_remember'    => 'rememberme',
                    'id_submit'      => 'wp-submit',
                    'remember'       => true,
                    'value_username' => '',
                    // Set 'value_remember' to true to default the "Remember me" checkbox to checked.
                    'value_remember' => false,
                )
                            
            );
            if(isset( $_GET['login_error'] ) ){
            ?>
            <div class="wp_login_error">
                <?php if( isset( $_GET['login_error'] ) && $_GET['login_error'] == '2' ){ ?>
                    <p>The password you entered is incorrect, Please try again.</p>
                <?php } 
                else if( isset( $_GET['login_error'] ) && $_GET['login_error'] == 'empty' ) { ?>
                    <p>Please enter both username and password.</p>
                <?php } 
                else if(isset( $_GET['login_error'] ) && $_GET['login_error'] == '1'){
                    ?>
                    <p>Username not found</p>
                    <?php
                }
                ?>
            </div> 
            <?php } ?>
            <?php } ?>
        </div>
        </span>
    
        <?php
    }else{
        $current_user = wp_get_current_user();
        
        $output = '<span class="menu-logout">' . wp_loginout( get_home_url() , false ) . '</span>';
        echo $output;
    }
    ?>
    </nav>
    <?php }
}
else{
    if ( function_exists( 'the_custom_logo' ) ) {
        the_custom_logo();
       }
    if($editor){
    ?>
    <div  class="hex-menu-button" id="join-code" data-clipboard-text="<?php echo get_permalink()."?join=".get_field('join_code'); ?>"></div>
    
    <?php
    }

} ?>
</header>
