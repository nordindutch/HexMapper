<div data-hidden="<?php if(get_field('hidden',$the_hex)){echo get_field('hidden',$the_hex);}else{echo 0;} ?>" data-terrain="<?php echo get_sub_field('terrain')['value']; ?>" class="hex-top <?php if(get_sub_field('hidden') && !is_user_logged_in(  )){echo "hidden";} ?> <?php if(get_sub_field('terrain')['value'] == 'void'){echo "void";} ?>" 
<?php if(get_sub_field('name')){echo "name='".get_sub_field('name')."'";} ?>
data-col="<?php echo get_sub_field('column_hex') ?>" data-row="<?php echo get_sub_field('row_hex') ?>">
								<div class="hex-container tile "  <?php if(is_user_logged_in(  ) || !get_sub_field('hidden')){ echo 'id="'.get_sub_field('HID').'"'  ;} ?> data-hexkey="<?php echo esc_html(alz(get_sub_field('column_hex')).".".alz(get_sub_field('row_hex'))) ?>">
									<div class="hex-wrap">
										<div class="hex"></div>
									</div>
										
								</div>
								<?php if(get_sub_field('name')){?>
											<div class="name-container">
												<div class="nametag"><?php echo esc_html(get_sub_field('name')); ?></div>
												<?php if(get_sub_field('region')){ ?><div class="regiontag"><?php echo esc_html(get_sub_field('region')); ?></div> <?php } ?>
											</div>
											<?php } ?>
							</div>