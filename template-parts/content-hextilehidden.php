<div class="hex-top hidden <?php if(get_sub_field('terrain')['value'] == 'void'){echo "void";} ?>" data-col="<?php echo get_sub_field('column_hex') ?>" data-row="<?php echo get_sub_field('row_hex') ?>">
								<div class="hex-container tile" id="<?php echo esc_html(get_sub_field('HID')) ?>"  data-hexkey="<?php echo esc_html(alz(get_sub_field('column_hex')).".".alz(get_sub_field('row_hex'))) ?>">
									<div class="hex-wrap">
										<div class="hex hidden <?php if(get_sub_field('terrain')['value'] == 'void'){echo "void";} ?>"></div>
									</div>
										
								</div>
								
							</div>