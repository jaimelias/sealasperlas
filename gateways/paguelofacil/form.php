<div class="sea_checkout_cc hidden">
	<input type="hidden" name="sea_recaptcha" />

	<div class="text-center text-muted bottom-20" style="font-size: 3em;">
	<i class="fab fa-cc-visa" ></i> <i class="fab fa-cc-mastercard" ></i>
	</div>		

	<div class="hidden">
	<input type="text" name="channel" class="channel" value="channel" />
	</div>

	<fieldset>
	<h3><?php echo esc_html(__('Contact Details', 'sealasperlas')); ?></h3>
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label for="name"><?php echo esc_html(__('Name', 'sealasperlas')); ?></label>
				<input type="text" name="name" class="bottom-20" />
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label for="lastname"><?php echo esc_html(__('Last Name', 'sealasperlas')); ?></label>
				<input type="text" name="lastname" class="bottom-20" />
			</div>
		</div>
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-2">
				<label for="email"><?php echo esc_html(__('Email', 'sealasperlas')); ?></label>
				<input type="email" name="email" class="bottom-20" />
			</div>
			<div class="pure-u-1 pure-u-md-1-2">
				<label for="phone"><?php echo esc_html(__('Phone', 'sealasperlas')); ?></label>
				<input type="text" name="phone" class="bottom-20" />
			</div>
		</div>
		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-3">
				<label for="country"><?php echo esc_html(__('Country', 'sealasperlas')); ?></label>
				<select name="country" class="countrylist bottom-20"><option>--</option></select>
			</div>
			<div class="pure-u-1 pure-u-md-1-3">
				<label for="city"><?php echo esc_html(__('City', 'sealasperlas')); ?></label>
				<input type="text" name="city" class="bottom-20" />
			</div>
			<div class="pure-u-1 pure-u-md-1-3">
				<label for="address"><?php echo esc_html(__('Address', 'sealasperlas')); ?></label>
				<input type="text" name="address" class="bottom-20" />
			</div>					
		</div>	
					
	</fieldset>



	<fieldset>
	<h3><?php echo esc_html(__('Billing Details', 'sealasperlas')); ?></h3>
	<p><label for="CCNum"><?php echo esc_html(__('Credit Card Number', 'sealasperlas')); ?></label>
	<input class="large" min="16" type="number" name="CCNum" /></p>

		<div class="pure-g gutters">
			<div class="pure-u-1 pure-u-md-1-3">
				<label for="ExpMonth"><?php echo esc_html(__('Expiration Month', 'sealasperlas')); ?></label>
			
				<select name="ExpMonth" class="bottom-20">
				<?php 
					for($x = 0; $x < 12; $x++ )
					{
						echo '<option>'.sanitize_text_field(sprintf("%02d", $x+1)).'</option>';
					}
				?>
				</select>

				
			</div>
			<div class="pure-u-1 pure-u-md-1-3">
				<label for="ExpYear"><?php echo esc_html(__('Expiration Year', 'sealasperlas')); ?></label>
				<select name="ExpYear" class="bottom-20">
				<?php 
					for($x = intval(date('y')); $x < intval(date('y'))+10; $x++ )
					{
						echo '<option>'.sanitize_text_field(sprintf("%02d", $x)).'</option>';
					}
				?>						
				</select>
			</div>					
			<div class="pure-u-1 pure-u-md-1-3">
				<label for="CVV2">CVV</label>
				<input min="0" max="999" type="number" name="CVV2" class="bottom-20"/>
			</div>
		</div>
	</fieldset>		

	<p><button type="submit" class="pure-button pure-button-primary width-100 block"><?php echo esc_html(__('Pay Deposit', 'sealasperlas')); ?></button></p>
	
</div>
