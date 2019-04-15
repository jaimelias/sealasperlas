<script type="text/html" id="sea_itinerary_html">
	<?php sealasperlas_public::itinerary(); ?>
</script>

<form method="get" action="<?php echo esc_url(home_lang().'/sealasperlas/'); ?>" id="sealasperlas_quote">

	<div class="text-center" style="font-size: 3em;"><?php echo sealasperlas_settings::icons(); ?></div>

	<p class="large text-center"><?php echo esc_html(__('Book Now', 'sealasperlas').'! '.__('We accept', 'sealasperlas').' '.sealasperlas_settings::join_gateways()); ?></p>

	<div class="hidden">
		<input type="number" name="s_passengers" class="bottom-20" value="1" disabled />
	</div>

	<fieldset class="sea_booking">
		<h3 class="large text-center uppercase"><?php echo esc_html(__('Booking Details', 'sealasperlas')); ?></h3>
		<hr/>
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('Origin', 'sealasperlas')); ?></label>
				<select name="s_origin" class="bottom-20">
					<?php sealasperlas_public::option_destinations(true); ?>
				</select>
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('Destination', 'sealasperlas')); ?></label>
				<select name="s_destination" class="bottom-20">
					<option selected>--</option>
					<?php sealasperlas_public::option_destinations(); ?>
				</select>
			</div>
		</div>
		
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('0 to 4 Years Old', 'sealasperlas')); ?></label>
				<select name="s_free" class="bottom-20">
					<option>0</option>
					<?php sealasperlas_public::option_passengers(); ?>
				</select>
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('5 to 11 Years Old', 'sealasperlas')); ?></label>
				<select name="s_discount" class="bottom-20">
				<option>0</option>
					<?php sealasperlas_public::option_passengers(); ?>
				</select>
			</div>
		</div>	
		
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('Adults', 'sealasperlas')); ?></label>
				<select name="s_adults" class="bottom-20">
					<?php sealasperlas_public::option_passengers(); ?>
				</select>
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('Ferry', 'sealasperlas')); ?></label>
				<select name="s_ferry" class="bottom-20">
					<option value="0"><?php echo esc_html(__('One way', 'sealasperlas')); ?></option>
					<option value="1"><?php echo esc_html(__('Round trip', 'sealasperlas')); ?></option>
				</select>
			</div>
		</div>

		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('Departure Date', 'sealasperlas')); ?></label>
				<input type="text" name="s_departure_date" class="bottom-20 sea_picker" disabled />
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><?php echo esc_html(__('Departure Hour', 'sealasperlas')); ?></label>
				<select name="s_departure_hour" class="bottom-20" disabled>
				<option>--</option>
				</select>
			</div>
		</div>	
		
		<div class="s_return hidden">
			<div class="pure-g gutters">
				<div class="pure-u-1 pure-u-md-1-2">
					<label><?php echo esc_html(__('Return Date', 'sealasperlas')); ?></label>
					<input type="text" name="s_return_date" class="bottom-20 sea_picker" disabled />
				</div>
				<div class="pure-u-1 pure-u-md-1-2">
					<label><?php echo esc_html(__('Return Hour', 'sealasperlas')); ?></label>
					<select name="s_return_hour" class="bottom-20" disabled>
					<option>--</option>
					</select>
				</div>
			</div>
		</div>
		<p class="text-right small"><button type="button" class="pure-button button-success booking_button"><?php echo esc_html(__('Continue', 'sealasperlas')); ?></button></p>
	</fieldset>
	<fieldset class="sea_passengers hidden">
	
		<div class="sea_itinerary_display"></div>
	
		<script type="text/html" id="sea_passengers">
			<h4 class="text-center uppercase large light"><?php echo esc_html(__('Passenger', 'sealasperlas')); ?> <span class="s_p_el"></span></h4>
			<div class="pure-g gutters sea_passengers_wrap bottom-20">
				<div class="pure-u-1 pure-u-md-2-5">
					<label class="light"><?php echo esc_html(__('Full Name', 'sealasperlas')); ?></label>
					<input type="text" class="bottom-20 s_p_name" placeholder="<?php echo esc_html(__('Full Name', 'sealasperlas')); ?>" />
				</div>
				<div class="pure-u-1 pure-u-md-2-5">
					<label class="light"><?php echo esc_html(__('Passport / Identification', 'sealasperlas')); ?></label>
					<input type="text" class="bottom-20 s_p_id" placeholder="<?php echo esc_html(__('Passport / Identification', 'sealasperlas')); ?>"  />
				</div>
				<div class="pure-u-1 pure-u-md-1-5">
				<label class="light"><?php echo esc_html(__('Age', 'sealasperlas')); ?></label>
				<input type="number" class="bottom-20 s_p_age" placeholder="<?php echo esc_html(__('Age', 'sealasperlas')); ?>"  />
				</div>
			</div>
			<hr/>
		</script>
		<div id="sea_passengers_container"></div>
		<textarea name="sea_pax" class="hidden"></textarea>
		<p class="text-right small"><button type="button" class="pure-button passengers_back"><?php echo esc_html(__('Back', 'sealasperlas')); ?></button> <button type="button" class="pure-button button-success passengers_button"><?php echo esc_html(__('Continue', 'sealasperlas')); ?></button></p>
		
	</fieldset>
	
	<fieldset class="sea_checkout hidden">
	
		<div class="sea_itinerary_display"></div>

		<div class="text-center" style="font-size: 3em;"><?php echo sealasperlas_settings::icons(); ?></div>
		<p class="large text-center"><?php echo esc_html(__('Book Now', 'sealasperlas').'! '.__('We accept', 'sealasperlas').' '.sealasperlas_settings::join_gateways()); ?></p>
		<p class="text-center small"><button type="button" class="checkout_back pure-button"><?php echo esc_html(__('Back', 'sealasperlas')); ?></button> <?php sealasperlas_settings::buttons(); ?></p>
		<?php sealasperlas_settings::form(); ?>
	</fieldset>
	
	
</form>