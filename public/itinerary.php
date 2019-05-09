	<div class="pure-g gutters">
		<div class="pure-u-1 pure-u-lg-1-2">
			<div class="sea_itinerary">
				<p class="sea_departure">
					<strong><?php echo esc_html(__('Departure', 'sealasperlas')); ?>:</strong> <span class="sea_deparute_l"></span> - <span class="sea_return_l"></span><br/>
					<strong><?php echo esc_html(__('Departure Date', 'sealasperlas')); ?>:</strong> <span class="sea_deparute_d"></span></br>
					<strong><?php echo esc_html(__('Departure Hour', 'sealasperlas')); ?>:</strong> <span class="sea_deparute_h"></span>
				</p>
			</div>
		</div>
		<div class="pure-u-1 pure-u-lg-1-2">
			<p class="sea_return hidden">
				<strong><?php echo esc_html(__('Return', 'sealasperlas')); ?>:</strong> <span class="sea_return_l"></span> - <span class="sea_deparute_l"></span><br/>
				<strong><?php echo esc_html(__('Return Date', 'sealasperlas')); ?>:</strong> <span class="sea_return_d"></span></br>
				<strong><?php echo esc_html(__('Return Hour', 'sealasperlas')); ?>:</strong> <span class="sea_return_h"></span>				
			</p>	
		</div>
	</div>
	
	<hr/>
	
			<table class="sea_table small text-center pure-table pure-table-bordered uppercase">
				<thead>
					<tr>
						<th><?php echo esc_html(__('Description', 'sealasperlas')); ?></th>
						<th><?php echo esc_html(__('Number', 'sealasperlas')); ?></th>
						<th><?php echo esc_html(__('Subtotal', 'sealasperlas')); ?></th>				
					</tr>
				</thead>
				<tbody>
					<tr class="sea_table_adults">
						<td><?php echo esc_html(__('Adults', 'sealasperlas')); ?></td>
						<td class="adult_count">0</td>
						<td class="adult_total">0</td>
					</tr>
					<tr class="sea_table_discount">
						<td><?php echo esc_html(__('5 to 11 Years Old', 'sealasperlas')); ?></td>
						<td class="discount_count">0</td>
						<td class="discount_total">0</td>
					</tr>
					<tr class="sea_table_free">
						<td><?php echo esc_html(__('0 to 4 Years Old', 'sealasperlas')); ?></td>
						<td class="free_count">0</td>
						<td class="free_total">0</td>
					</tr>				
				</tbody>
				<tfoot class="strong">
					<tr>
						<th colspan="3" class="strong"><?php echo esc_html(__('Total', 'sealasperlas')); ?> = <span class="sea_table_total"></span></th>
					</tr>			
				</tfoot>
			</table>	
			
<hr/>			