<script type="text/html" id="sea_itinerary_html">
	<?php sealasperlas_public::itinerary(); ?>
</script>

<script type="text/html" id="sea_passengers">
	<?php sealasperlas_public::passengers(); ?>
</script>

<form method="post" action="<?php echo esc_url(home_lang().'sealasperlas/'); ?>" id="sealasperlas_quote" class="padding-10">

	<div class="text-center" style="font-size: 3em;"><?php echo sealasperlas_settings::icons(); ?></div>

	<p class="large text-center"><?php echo esc_html(__('Book Now', 'sealasperlas').'! '.__('We accept', 'sealasperlas').' '.sealasperlas_settings::join_gateways()); ?></p>

	<div class="hidden">
		<input type="number" name="s_passengers" class="bottom-20" value="1" disabled />
		<input name="lang" class="lang" value="<?php echo substr(get_bloginfo('language'), 0, 2); ?>" />
		<input name="channel" class="channel" value="" />
		<input name="landing_path" class="landing_path" value=""/>
		<input name="device" class="device" value=""/>		
	</div>

	<fieldset class="sea_booking padding-10">
		<h3 class="large text-center uppercase"><?php echo esc_html(__('Booking Details', 'sealasperlas')); ?></h3>
		<hr/>
		
		<div class="relative">
			<ul id="sea_accordion" class="list-style-none small">
				<li>
					<ul class="list-style-none padding-0 margin-0 sea_accordion_content">
						<li class="sea_pricing padding-10 bottom-10 strong uppercase pointer"><?php echo esc_html(__('Prices per person', 'sealasperlas')); ?> &#9660;</li>
						<li class="sea_pricing padding-10 bottom-10"><?php echo esc_html(__('Adults or children of 12 years and older. $55 each way ($110 round trip)
			', 'sealasperlas')); ?></li>
						<li class="sea_pricing padding-10 bottom-10"><?php echo esc_html(__('Children from 5 to 11 years old $ 44 each way ($88 round trip)', 'sealasperlas')); ?></li>
						<li class="sea_pricing padding-10 bottom-10"><?php echo esc_html(__('Children from 0 to 4 years old do not pay travel on their parents’ legs', 'sealasperlas')); ?></li>
					</ul>			
				</li>
				<li>
					<ul class="list-style-none padding-0 margin-0 sea_accordion_content">
						<li class="sea_schedule padding-10 bottom-10 strong uppercase pointer"><?php echo esc_html(__('Schedules', 'sealasperlas')); ?> &#9660;</li>
						<li class="sea_schedule padding-10 bottom-10"><?php echo esc_html(__('Departing 7:30am (check-in 7am) from the Flamenco Marina, Causeway Amador, Panama City', 'sealasperlas')); ?>.</li>
						<li class="sea_schedule padding-10 bottom-10"><?php echo esc_html(__('Returning at 3:30pm (check-in 3:00pm) from Contadora Island', 'sealasperlas')); ?>.</li>
						<li class="sea_schedule padding-10 bottom-10"><?php echo esc_html(__('Returning at 3:45pm (check-in 3pm) from Saboga Island', 'sealasperlas')); ?>.</li>
						<li class="sea_schedule padding-10 bottom-10"><?php echo esc_html(__('Returning at 2:00pm (check-in 1:30pm) from Viveros Island', 'sealasperlas')); ?>.</li>
						<li class="sea_schedule padding-10 bottom-10"><?php echo esc_html(__('Arriving at 5:00pm in Panama City', 'sealasperlas')); ?>.</li>
					</ul>			
				</li>
				<li>
					<ul class="list-style-none padding-0 margin-0 sea_accordion_content">
						<li class="sea_pricing padding-10 bottom-10 strong uppercase pointer"><?php echo esc_html(__('Before Book', 'sealasperlas')); ?> &#9660;</li>
						<li class="sea_pricing padding-10 bottom-10"><?php echo esc_html(__('Do not forget your original passports or panamanian ID', 'sealasperlas')); ?>.</li>
						<li class="sea_pricing padding-10 bottom-10"><?php echo esc_html(__('There are no ATMs in Contadora. Some tour operators, hotels and restaurants do not accept Credit Cards.', 'sealasperlas')); ?>.</li>
						<li class="sea_pricing padding-10 bottom-10"><?php echo esc_html(__('Make sure to arrive at least 30 minutes prior to each departure', 'sealasperlas')); ?>.</li>
					</ul>				
				</li>			
			</ul>
		</div>
		
		
		<hr/>
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-map-marker-alt"></i> <?php echo esc_html(__('Origin', 'sealasperlas')); ?></label>
				<select name="s_origin" class="bottom-20">
					<?php sealasperlas_public::option_destinations(true); ?>
				</select>
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-map-marker-alt"></i> <?php echo esc_html(__('Destination', 'sealasperlas')); ?></label>
				<select name="s_destination" class="bottom-20">
					<option selected>--</option>
					<?php sealasperlas_public::option_destinations(); ?>
				</select>
			</div>
		</div>
		
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-male"></i> <?php echo esc_html(__('0 to 4 Years Old', 'sealasperlas')); ?></label>
				<select name="s_free" class="bottom-20">
					<option value="0">0</option>
					<?php sealasperlas_public::option_passengers(); ?>
				</select>
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-male"></i> <?php echo esc_html(__('5 to 11 Years Old', 'sealasperlas')); ?></label>
				<select name="s_discount" class="bottom-20">
				<option value="0">0</option>
					<?php sealasperlas_public::option_passengers(); ?>
				</select>
			</div>
		</div>	
		
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-male"></i> <?php echo esc_html(__('Adults', 'sealasperlas')); ?></label>
				<select name="s_adults" class="bottom-20">
					<?php sealasperlas_public::option_passengers(); ?>
				</select>
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-ship"></i> <?php echo esc_html(__('Ferry', 'sealasperlas')); ?></label>
				<select name="s_ferry" class="bottom-20">
					<option value="1"><?php echo esc_html(__('Round trip', 'sealasperlas')); ?></option>
					<option value="0"><?php echo esc_html(__('One way', 'sealasperlas')); ?></option>
				</select>
			</div>
		</div>

		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-calendar"></i> <?php echo esc_html(__('Departure Date', 'sealasperlas')); ?> <span class="sea_spinner hidden"><i class="fas fa-spinner fa-spin fa-fw"></i></span></label>
				<input type="text" name="s_departure_date" class="bottom-20 sea_picker" disabled />
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label><i class="fas fa-clock"></i> <?php echo esc_html(__('Departure Hour', 'sealasperlas')); ?> <span class="sea_spinner hidden"><i class="fas fa-spinner fa-spin fa-fw "></i></span></label>
				<select name="s_departure_hour" class="bottom-20" disabled>
				<option>--</option>
				</select>
			</div>
		</div>	
		
		<div class="s_return">
			<div class="pure-g gutters">
				<div class="pure-u-1 pure-u-md-1-2">
					<label><i class="fas fa-calendar"></i> <?php echo esc_html(__('Return Date', 'sealasperlas')); ?> <span class="sea_spinner hidden"><i class="fas fa-spinner fa-spin fa-fw"></i></span></label>
					<input type="text" name="s_return_date" class="bottom-20 sea_picker" disabled />
				</div>
				<div class="pure-u-1 pure-u-md-1-2">
					<label><i class="fas fa-clock"></i> <?php echo esc_html(__('Return Hour', 'sealasperlas')); ?> <span class="sea_spinner hidden"><i class="fas fa-spinner fa-spin fa-fw"></i></span></label>
					<select name="s_return_hour" class="bottom-20" disabled>
					<option>--</option>
					</select>
				</div>
			</div>
		</div>
		<p class="text-right small"><button type="button" class="pure-button button-success booking_button"><?php echo esc_html(__('Continue', 'sealasperlas')); ?></button></p>
			
	</fieldset>
	<fieldset class="sea_passengers hidden padding-10">
	
		<div class="sea_itinerary_display"></div>
	
		<div id="sea_passengers_container"></div>
		<div class="hidden">
			<textarea name="sea_pax"></textarea>
		</div>
		<p class="text-right small"><button type="button" class="pure-button passengers_back"><?php echo esc_html(__('Back', 'sealasperlas')); ?></button> <button type="button" class="pure-button button-success passengers_button"><?php echo esc_html(__('Continue', 'sealasperlas')); ?></button></p>
		
	</fieldset>
	
	<fieldset class="sea_checkout hidden padding-10">
	
		<textarea name="itineray" class="sea_itinerary_display hidden"></textarea>
		<div class="sea_itinerary_display"></div>

		<div class="text-center" style="font-size: 3em;"><?php echo sealasperlas_settings::icons(); ?></div>
		<p class="large text-center"><?php echo esc_html(__('Book Now', 'sealasperlas').'! '.__('We accept', 'sealasperlas').' '.sealasperlas_settings::join_gateways()); ?></p>
		<p class="text-center small"><button type="button" class="checkout_back pure-button"><?php echo esc_html(__('Back', 'sealasperlas')); ?></button> <?php sealasperlas_settings::buttons(); ?></p>
		<?php sealasperlas_settings::form(); ?>
	</fieldset>
	
	
</form>