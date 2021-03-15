<?php 
get_header();

?><div class="inner-homepage"><?php
if(!is_user_logged_in(  )){
?>
<div class="login-register">
    <div>
        <?php if(!isset( $_GET['user-register'])){ ?>
    <div class="signin-container">
    <h1>Sign In</h1>
        <?php
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
        <?php 
        }
        ?></div><?php
    }
        ?>
    
    <div class="registration-container">
        <h1><?php if(!isset( $_GET['user-register'])){echo "Or ";} ?>Join Us Today</h1>
        <div class="registration-form">
            <div class="full-width">
                <label for="username">Username</label>
                <input type="text" id="username" maxlength="24" name="username" required>
            </div>
            <div class="full-width">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div  class="full-width">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div  class="full-width">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password"  autocomplete="off" required>
            </div>
            <div class="full-width">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name"  autocomplete="on" required>
            </div>
            <div class="full-width">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name"  autocomplete="on" required>
            </div>
            <div>
                <input type="submit" id="register-user" value="Submit" name="submit-user">
            </div>
        </div>
        </div>
        </div>
</div>
<?php
}else{
    $user = get_current_user_id();

    $posts = array(
        'posts_per_page' => -1 ,
        'nopaging' => true,
        'post_type'		=> 'hexmap',
        'orderby'     => 'modified',
        'meta_query'    => array(
            'relation'  => "OR",
            array(
                'key'       => 'creator',
                'value'     => $user,
                'compare'   => "IN"
            ),
            array(
                'key'       => 'contributors',
                'value'     => '"'.$user.'"',
                'compare'   => "LIKE"
            ),
        ),
    );
    ?>

    <?php


// The Query
$the_query = new WP_Query( $posts );
 
// The Loop
?>

<div class="hexmap-column">
    <h1>Your Maps <span class="add" id="hexmap"></span></h1>
    <div class="create-hexmap">
        <div class="image-upload" id="image-upload">
            <form method="post" id="upload-form" enctype="multipart/form-data">
            <label for="hexmap-image-upload">
            <i class="fas fa-image"></i>
            <span>Upload Image</span>
            </label>
                <input id="hexmap-image-upload" type="file" name="imagefile" accept=".jpeg, .png, image/jpeg, image/png" />

            </form>
        </div>
        <div class="hexmap-createform">
            <h2>Name of your map</h2>
            <div>
            <input type="text" name="hexmap-name" id="hexmap-name" maxlength="48" />
            </div>

            <div class="hexmap-submit-container">
                <input type="submit" name="hexmap-submit" id="hexmap-submit" value="Create"/>
            </div>
        </div>
    </div>
<?php

if ( $the_query->have_posts() ) {
    ?>
    <?php

    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        ?>
        <a <?php if(get_the_post_thumbnail_url($post, 'medium_large')){ ?> style="background-image: url(<?php echo get_the_post_thumbnail_url($post, 'medium_large'); ?>)" <?php } ?> class="hexmap-card" href="<?php echo get_the_permalink( )?>">
            <span><?php echo get_the_title() ?></span>
            <span><?php 
            if(get_field('creator')['ID'] == $user){
                echo "Creator";
            }
            else{
                echo "Contributor";
            }
            ?></span>
        </a>
        <?php
    }
    wp_reset_postdata();
} else {
    // no posts found
    echo "No maps founds. Create one today!";
}

$user_data = get_userdata($user);
?></div>
<aside class="side-column">
    <div class="user-info">
        <h1>Welcome back <?php echo $user_data->first_name; ?></h1>
    </div>
    <div class="news-container">
        <h1>HexMapper News</h1>
        <?php 
        $posts = array(
            'numberposts'	=> 5,
            'post_type'		=> 'post',
            'status'        => 'published'
        );
        $the_query = new WP_Query( $posts );

        if ( $the_query->have_posts() ) {
            ?>
            <?php
        
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                ?>
                <a class="news-card" href="<?php echo get_the_permalink( )?>">
                    <h2><?php the_title() ?></h2>
                    <p> <?php echo wp_trim_words( get_the_content(), $num_words = 32, $more = null ); ?></p>
                </a>
                <?php
            }
            wp_reset_postdata();
        }
        else{
            echo "<p>No news yet...</p>";
        }
        ?>
    </div>
</aside>
</div>
<?php
/* Restore original Post Data */

}

get_footer();
?>
