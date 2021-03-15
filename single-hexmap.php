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


$map = new Hexmap(get_the_ID());
$editor = $map->creator;
$contributor = $map->contributor;
?>
<script>

</script>
<?php get_header(); ?>
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