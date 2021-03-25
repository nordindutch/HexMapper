

<?php get_header(); ?>
<?php

if(is_user_logged_in( )){
	
    if(isset($_GET['join']) && $_GET['join'] == get_field('join_code', $identifier) && get_field('creator', $identifier)['ID'] != get_current_user_id()){
        // $post_id is the ID of the post you want to update
    // 3rd arg == false tells ACF not to format the value
    // $users will == an array or user IDs
    $users = get_field('contributors', $identifier);
    
    // add the users ID to the array
    $users[] = get_current_user_id();
    
    // update the field
    // $selector == the field key of the field to update
    update_field('contributors', $users, get_the_ID());
    }
    
}
elseif(isset($_GET['join']) && $_GET['join'] == get_field('join_code', $identifier)){
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
        <h1>Join Us Today</h1>
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
}


?>
<?php
$map = new Hexmap(get_the_ID());
$editor = $map->creator;
$contributor = $map->contributor;
?>
<!--<div class="hexmap-title"><h1><?php //the_title(); ?></h1><span>Created by <?php// echo get_field('creator')['nickname']; ?></span></div>-->
<div class="map-container" data-hexmap="<?php echo get_the_ID();?>">
<?php if($editor){ ?>
<aside class="tools">
    <div class="map-tools">
        <h2>Map Tools</h2>
        <div class="map-toolbar">
            <span id="default-tool" class="active">
                <svg xmlns="http://www.w3.org/2000/svg"viewBox="0 0 24 24"><polygon class="white-svg" points="24 10 13 13 10 24 0 0 "/></svg>
            </span>
            <span id="terrain-tool">
                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 128 93"><path class="white-svg" d="M92.3 18.2c-0.6 0.9-1.1 1.7-1.6 2.6 -1.2 2-2.2 3.8-3.5 5.4l-6 8.5 -6.6 10c-2.3 3.6-4.7 7.3-7 11.2 -2.9-0.4-6 0-8.7 1.2 -4.6 2-7.4 5.3-9.5 8.2 -1.4 2-2.6 4-3.7 5.9H0l31.8-56.8 23.7 20.9L85.5 0 92.3 18.2z"/><path class="white-svg" d="M112.3 71.4H81.4c0-0.2 0-0.4 0-0.7 4.1-2.9 8.1-5.8 12-8.7l9.6-7.4 2.4-2L112.3 71.4z"/><path class="white-svg" d="M71.5 65.3c3.4-6 7.1-11.8 10.8-17.6l5.7-8.5 5.9-8.3c2-2.7 3.7-5.8 5.5-8.7 1.8-2.9 4.1-5.4 6.8-7.5 2.7-2.1 5.7-3.9 9.1-5.4 3.4-1.5 7-2.7 11.4-3.2L128 7.5c-0.2 4.5-1.1 8.1-2.4 11.6 -1.3 3.4-2.8 6.6-4.7 9.4 -1.8 2.9-4.2 5.3-6.9 7.3 -2.8 2-5.7 3.9-8.3 6.1l-7.9 6.5 -8.1 6.3c-5.5 4.1-11 8.2-16.7 12L71.5 65.3z"/><path class="white-svg" d="M72.7 74.9c-1.2 2.7-3.2 4.5-5.4 6.2 -2.3 1.6-4.8 3-7.2 4.4 -5 2.8-10.1 5.4-15.8 7.5 2-5.7 4.7-10.9 7.5-15.8 1.4-2.5 2.9-4.9 4.4-7.2 1.7-2.2 3.5-4.2 6.2-5.4 4-1.8 8.6 0 10.4 3.9C73.6 70.6 73.6 73 72.7 74.9z"/><path class="white-svg" d="M72.4 71.4l0.2 0.2 0.2-0.1H72.4z"/></svg>
            </span>
            <span id="hide-tool">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 104.4"><polygon class="white-svg" points="89 14.6 85 7.7 76.9 7.7 76.9 0 89.4 0 95.6 10.8 "/><rect x="53.8" class="white-svg" width="11.6" height="7.7"/><polygon class="white-svg" points="30.2 14.6 23.6 10.8 29.8 0 42.3 0 42.3 7.7 34.2 7.7 "/><rect x="12.5" y="23.9" transform="matrix(0.5001 -0.866 0.866 0.5001 -14.8984 29.6439)" class="white-svg" width="11.6" height="7.7"/><polygon class="white-svg" points="6.2 62.4 0 51.6 6.2 40.8 12.9 44.7 8.9 51.6 12.9 58.6 "/><rect x="14.4" y="69.7" transform="matrix(0.866 -0.5 0.5 0.866 -35.3051 19.2287)" class="white-svg" width="7.7" height="11.6"/><path class="white-svg" d="M42.3 95.5v0.1c-1 2.1-1.9 4.4-2.7 6.8l-0.3 0.8h-9.4l-6.2-10.8 6.6-3.8 4 7H42.3z"/><path class="white-svg" d="M65.4 100v3.3h-5.8c1.2-0.7 2.4-1.3 3.4-1.9C63.7 100.9 64.5 100.4 65.4 100z"/><polygon class="white-svg" points="89.4 103.2 76.9 103.2 76.9 95.5 85 95.5 89 88.6 95.6 92.4 "/><rect x="95.2" y="71.7" transform="matrix(0.5001 -0.866 0.866 0.5001 -14.9021 125.1634)" class="white-svg" width="11.6" height="7.7"/><path class="white-svg" d="M119.2 51.6l-6.2 10.8 -4.6-2.6 3.2-2.6c1.4-1.1 2.9-2.2 4.5-3.3 0.8-0.5 1.6-1.1 2.4-1.7 0.2-0.2 0.5-0.3 0.7-0.5L119.2 51.6z"/><path class="white-svg" d="M102.3 22.5c-2.1 1.6-3.9 3.5-5.6 5.7l-2-3.5 6.6-3.8L102.3 22.5z"/><path class="white-svg" d="M71.7 76.6c3.4-6 7.1-11.7 10.7-17.5l5.6-8.5 5.9-8.3c2-2.7 3.6-5.8 5.4-8.7 1.8-2.9 4.1-5.4 6.8-7.4 2.7-2 5.7-3.8 9-5.3 3.3-1.5 6.9-2.7 11.3-3.2l1.5 1.4c-0.2 4.4-1.1 8.1-2.3 11.5 -1.2 3.4-2.8 6.5-4.7 9.4 -1.8 2.9-4.2 5.3-6.9 7.3 -2.8 2-5.7 3.9-8.2 6.1l-7.8 6.4 -8.1 6.2c-5.5 4.1-10.9 8.2-16.7 12L71.7 76.6z"/><path class="white-svg" d="M73 86.3c-1.2 2.7-3.2 4.5-5.4 6.2 -2.3 1.6-4.8 3-7.2 4.4 -5 2.8-10.1 5.4-15.8 7.5 2-5.7 4.7-10.9 7.5-15.8 1.4-2.5 2.9-4.9 4.4-7.2 1.7-2.2 3.5-4.2 6.2-5.4 4-1.8 8.6 0 10.4 3.9C73.9 81.9 73.9 84.3 73 86.3z"/><path class="white-svg" d="M98.9 50.3c-0.7-0.7-17.4-16-38.6-16.6 -0.4 0-0.8 0-1.2 0 -0.4 0-0.8 0-1.2 0 -21.3 0.6-38 16-38.7 16.6l-2 1.9 2 1.9c0.7 0.7 18.1 16.6 39.9 16.6 21.8 0 39.1-16 39.9-16.6l2-1.9L98.9 50.3zM24.9 52.2c3.4-2.7 10.2-7.5 18.9-10.6 -2.1 3-3.3 6.6-3.3 10.6 0 3.9 1.2 7.6 3.3 10.6C35.2 59.7 28.3 54.9 24.9 52.2zM59.1 65.6c-7.4 0-13.4-6-13.4-13.4 0-7.4 6-13.4 13.4-13.4 7.4 0 13.4 6 13.4 13.4C72.4 59.6 66.4 65.6 59.1 65.6zM74.2 62.8c2.1-3 3.4-6.7 3.4-10.6 0-3.9-1.2-7.6-3.3-10.6 8.7 3.1 15.6 7.9 18.9 10.6C89.8 54.9 82.9 59.7 74.2 62.8z"/></svg>
            </span>
        </div>
        <div class="tool-options">
            <div id="terrain-tool-options" style="display:none">
                <select name='terrains' id='terrains' size="1">
                    <option value="swamp">Swamp</option>
                    <option value="marshland">Marshland</option>
                    <option value="forest">Forest</option>
                    <option value="deep_forest">Deep Forest</option>
                    <option value="plains">Plains</option>
                    <option value="hills">Hills</option>
                    <option value="mountains">Mountains</option>
                    <option value="high_mountains">High Mountains</option>
                    <option value="canyon">Canyon</option>
                    <option value="desert">Desert</option>
                    <option value="archipelago">Archipelago</option>
                    <option value="lake">Lake</option>
                    <option value="coast">Coast</option>
                    <option value="ocean">Ocean</option>
                    <option value="volcanic">Volcanic</option>
                    <option value="wasteland">Wasteland</option>
                    <option value="jungle">Jungle</option>
                    <option value="tundra">Tundra</option>
                    <option value="glacier">Glacier</option>        
                    <option value="urban">Urban</option>
                    <option value="void">Void</option>
                </select>
            </div>
        </div>
    </div>
</aside>
<?php } ?>
<nav class="creation-tools ">
    <div class="instructions-button" alt="View instructions"></div>
    <?php if($editor){ ?> <div class="tools-button" alt="Open up site creation tools"></div> <?php } ?>
</nav>
<aside class="instructions closed">
    <div class="fold-in"></div>
    <div class="instruction">
        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 87.1 200.72" overflow="visible">
        <path d="M46 60v-8c0-11 9-19 9-20 1 0 11-10 11-22V0h-4v10c0 11-9 19-9 19-1 1-11 10-11 23v8C18 61 0 80 0 103v54a44 44 0 0087 0v-54c0-23-18-42-41-43zm3 53v6a5 5 0 01-10 0v-9a5 5 0 0110 0v3zm34 44a40 40 0 01-79 0v-40h31v2a9 9 0 0018 0v-2h30v40zm-30-44v-3a12 12 0 00-1-2v-1a5 5 0 000-1h-1v-1l-1-1-1-1-1-1h-1l-1-1V64c20 1 37 18 37 39v10H53z" fill="#fff"/>
        </svg>
    <p>Left-lick to access hex. Left-click +  drag while  painting to apply paint to hexes. Hover over hex to see basic info.</p>
    </div>
    <div class="instruction">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 87.1 200.72" overflow="visible">
    <path d="M46 60v-8c0-11 9-19 9-20 1 0 11-10 11-22V0h-4v10c0 11-9 19-9 19-1 1-11 10-11 23v8C18 61 0 80 0 103v54a44 44 0 0087 0v-54c0-23-18-42-41-43zM4 103c0-21 17-38 38-39v37h-1l-1 1a10 10 0 00-1 0l-1 1-1 1-1 1a6 6 0 000 1h-1v7H4v-10zm79 54a40 40 0 01-79 0v-40h31v2a9 9 0 0018 0v-2h30v40zm-30-44v-3a12 12 0 00-1-2v-1a5 5 0 000-1h-1v-1l-1-1v-1h-1l-1-1h-1l-1-1V64c20 1 37 18 37 39v10H53z" fill="#fff"/>
    </svg>
    <p>Hold and drag
    middle mouse
    button to move
    across the map.</p>
    </div>
    <div class="instruction">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 87.1 200.72"  overflow="visible">
    <path d="M46 60v-8c0-11 9-19 9-20 1 0 11-10 11-22V0h-4v10c0 11-9 19-9 19-1 1-11 10-11 23v8C18 61 0 80 0 103v54a44 44 0 0087 0v-54c0-23-18-42-41-43zm-7 57v-7a5 5 0 0110 0v9a5 5 0 01-10 0v-2zM4 103c0-21 17-38 38-39v37h-1l-1 1h-1l-1 1h-1v1l-1 1a12 12 0 00-1 1v7H4v-10zm79 54a40 40 0 01-79 0v-40h31v2a9 9 0 0018 0v-2h30v40z" fill="#fff"/>
    </svg>
        <p>Right-click to
    open hex
    options and
    add/delete column
    and row options. </p>
        </div>
</aside>
<?php if($editor){ ?>
<aside class="site-creation closed">
    <div class="fold-in"></div>
    <div class="fade"></div>
    <div class="container">
        <form class="create-item">
        
        <h2>Create New Item </h2>
        <select autocomplete="off" name='new-item-type' id='new-item-type' size="1" required>
            <option value="" selected disabled>- Choose item type -</option>
            <option value="adventure-site">Adventure Site</option>
            <option value="event">Event</option>
            <option value="monster">Monster</option>
            <option value="npc">NPC</option>
            <option value="landmark">Landmark</option>
        </select>
        <input type='text' name='new-item-name' id='new-item-name' placeholder="Name..." required />
        <input type='submit' name='create-new-item' id='create-new-item' value="Create" />
    </form>
    <div class="all-items">
        <h2>Your Items</h2>

        <select autocomplete="off" name='items' id='your-items' size="1">
            <option value="0">All</option>
            <option value="adventure-site">Adventure Site</option>
            <option value="event">Event</option>
            <option value="monster">Monster</option>
            <option value="npc">NPC</option>
            <option value="landmark">Landmark</option>
        </select>
        <div class="item-list">
            <?php
            
            $map->load_items();

            ?>
        </div>
        </div>
    </div>
</aside>
<?php } ?>
<?php
$map->build_map();
?>
</div>
<div id="hex-info">

</div>

<?php
get_footer();

?>