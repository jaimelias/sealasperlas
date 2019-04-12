<form method="get" action="" id="sealasperlas_quote">

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
					<option>--</option>
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
					<label><?php echo esc_html(__('Departure Date', 'sealasperlas')); ?></label>
					<input type="text" name="s_return_date" class="bottom-20 sea_picker" disabled />
				</div>
				<div class="pure-u-1 pure-u-md-1-2">
					<label><?php echo esc_html(__('Departure Hour', 'sealasperlas')); ?></label>
					<select name="s_return_hour" class="bottom-20" disabled>
					<option>--</option>
					</select>
				</div>
			</div>
		</div>
		<p class="text-right small"><button type="button" class="pure-button button-success booking_button"><?php echo esc_html(__('Continue', 'sealasperlas')); ?></button></p>
	</fieldset>
</form>