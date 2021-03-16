
<?php 
$editor = $args['creator'];
$contributor =  $args['contributor'];

?>
<div class="hex-information">
    <div class="basic-information">
        <div class="hex-top" data-terrain="<?php echo get_field('terrain')['value'] ?>">
            <div class="hex-container" data-hexkey="<?php echo get_field('hexkey') ?>">
                    <div class="hex-wrap">
                        <div class="hex "></div>
                    </div>
                        
                </div>
                <?php if(get_field('name')){?>
                            <div class="name-container">
                                <div class="nametag" name="name"><input  type="text" value="<?php echo get_field('name'); ?>" name="hexname" id="hexname" readonly></div>
                                <?php if(get_field('region')){?> <div class="regiontag" name="region"><?php echo get_field('region');?> </div><?php } ?>
                            </div>
                            <?php } ?>
    
        
                </div> 
                <div class="basic-card">
                <?php if(!get_field('name') && get_field('region')){ ?>
                    <div class="info"><span class="label">Region</span><span role="textbox" class="content"><?php echo get_field('region') ?></span></div>
                    <?php } ?>
                <div class="info">
                    <span class="label non-edit">Terrain</span><span class='content none-edit'><?php echo get_field('terrain')['label']; ?></span>
                    </div>
      
            </div>   
        
    </div>
    <div class="extended-information">
        <div class="edit-container">
            <span>Close hex to save changes</span><div id="close-hex" <?php if($editor){echo "class='editor' data-hex='".get_post_field( 'post_name' )."'";}elseif($contributor){ echo "class='contributor' data-hex='".get_post_field( 'post_name' )."'";} ?> > 
            </div>
            
        </div>
        
        <div class="columns">
            <div class="left-column">
                <div id="hex-description">
                    <h1>Description</h1>
                    <div <?php if($editor){echo "contenteditable=true";} ?>>
                    <?php echo get_field('description') ?>
                    </div>
                </div>
                <div id="hex-notes">
                    <h1>Notes <?php if($editor || $contributor){ ?><span class="add" id="notes"></span><?php } ?></h1>
                    <?php if($editor || $contributor){
                        ?>
                        <div class="new-item-container">
                            <input type="text" name="new-note-name" id="new-note-name" placeholder="Name..."/>
                            <textarea name="new-note-content" id="new-note-content" rows="4" placeholder="Type your note here..."></textarea>
                            <input type="submit" name="add-note" id="add-note" value="Add Note">
                        </div>
                        <?php

                        $notes = get_field('notes');
                        if($notes){
                            foreach($notes as $note){
                                
                                if(!$note['hidden'] || $editor)
                                
                                ?>
                                <div class="note">

                                    <span>
                                    <span>
                                    <b class="note-title"><?php echo $note['title'] ?></b> by <span class="note-writer" <?php if($editor || $contributor){ echo 'data-writer="'.$note['writer']['ID'].'"';}?>><?php echo $note['writer']['nickname'] ?></span>
                                    </span>
                                    <?php  if($editor || $contributor){ ?><span class="hide-item<?php if($note['hidden']){echo " hidden";} ?>"></span><?php } if($editor || $contributor && $note['writer']['ID'] == get_current_user_id()){ ?>
                                    <span class="remove-item"></span><?php } ?>
                                    
                                    </span>
                                    <p class="note-content"><?php echo $note['content']; ?></p>
                                </div>
                                <?php
                            }
                        }
                        else{
                            echo "No notes yet... ";
                        }
                    } ?>
                    
                </div>
            </div>
            <div class="right-column">
                    <div id="hex-sites">
                        <h1>Adventure Sites <?php if($editor){ ?><span class="add" id="adventure-site"></span><?php } ?></h1>
                        No Adventure Sites yet...
                    </div>
                    <div id="hex-landmarks">
                        <h1>Landmarks <?php if($editor){ ?><span class="add" id="landmark"></span><?php } ?></h1>
                        No Landmarks yet...
                    </div>
                    <div id="hex-npc">
                        <h1>NPCs <?php if($editor){ ?><span class="add" id="npc"></span><?php } ?></h1>
                        No NPCs yet... 
                    </div>
                    <div id="hex-monsters">
                        <h1>Monsters <?php if($editor){ ?><span class="add" id="monster"></span><?php } ?></h1>
                        No Monsters yet...
                    </div>
                    <div id="hex-events">
                        <h1>Events <?php if($editor){ ?><span class="add" id="event"></span><?php } ?></h1>
                        No Events yet...
                    </div>
                    <div id="hex-items">
                        <h1>Items <?php if($editor){ ?><span class="add" id="item"></span><?php } ?></h1>
                        No Items yet...
                    </div>
            </div>
        </div>
        
    </div>
</div>