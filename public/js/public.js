$(function(){
	var sea_form = $('#sealasperlas_quote')[0];
	
	if($(sea_form).length > 0)
	{
		sea_populate(sea_form);
		sea_route(sea_form);
		sea_passengers(sea_form);
		sea_roundtrip(sea_form);
		sea_fieldset();
		sea_passengers_build();
		sea_passengers_txt();
		//editing
		sea_price_build();
		sea_focus_form(sea_form);
		sea_accordion();

		if(typeof sea_url !== typeof undefined)
		{
			sea_country_dropdown(sea_url(), $("html").attr("lang").slice(0, -3));
		}		
		
	}
});

function sea_steps(label)
{
	if(typeof ga !== typeof undefined)
	{
		var lang = $('html').attr('lang').slice(0, -3);
		var eventArgs = {};
		eventArgs.eventAction = 'click';
		eventArgs.eventLabel = lang;
		eventArgs.eventCategory = label;
		ga('send', 'event', eventArgs);
	}
}

function sea_populate(sea_form)
{
	var input = $(sea_form).find('input');
	input = $(input).add($(sea_form).find('textarea'));
	
	$(input).each(function(){
		
		if (typeof(Storage) !== 'undefined')
		{
			var name = $(this).attr('name');
			
			if (typeof name !== typeof undefined && name !== false)
			{
				if(sessionStorage.getItem(name) != null && sessionStorage.getItem(name) != '')
				{
					$(this).val(sessionStorage.getItem(name));
				}
			}
		}
	});
}

function sea_price_build()
{
	//editing
	var sea_fieldset = $('.sea_booking')[0];
	var input = $(sea_fieldset).find('select');
	input = $(input).add($(sea_fieldset).find('input'));
	
	$(input).change(function(){
		
		var this_field = $(this);
		
		setTimeout(function(){
			
			var rates = sea_rates();
			var free = parseFloat($('[name="s_free"]').val());
			var discount = parseFloat($('[name="s_discount"]').val());
			var adult = parseFloat($('[name="s_adults"]').val());
			var origin = parseFloat($('[name="s_origin"]').val());
			var destination = parseFloat($('[name="s_destination"]').val());
			var route = parseFloat($('[name="s_ferry"]').val());
			var output = {};
			
			//reset dates
			if($(this_field).attr('name') == 's_origin' || $(this_field).attr('name') == 's_destination')
			{
				$('[name="s_departure_date"]').val('');
				$('[name="s_return_date"]').val('');
				
				if (typeof(Storage) !== "undefined")
				{
					sessionStorage.removeItem('s_departure_date');
					sessionStorage.removeItem('s_return_date');									
				}
			}
			
			//reset return if one way
			if($(this_field).attr('name') == 's_ferry')
			{
				if($(this_field).val() == 0)
				{
					$('[name="s_return_date"]').val('');
					$('[name="s_return_hour"]').find('option:selected').removeAttr('selected');
					
					if (typeof(Storage) !== "undefined")
					{
						sessionStorage.removeItem('s_return_hour');
						sessionStorage.removeItem('s_return_date');						
					}
				}
			}
			
			for(var x = 0; x < rates.length; x++)
			{
				if(rates[x][1] != 11 && typeof rates[x][2] != 'undefined')
				{
					if(rates[x][1] == origin || rates[x][1] == destination)
					{
						output.free = 0;
						output.adult = rates[x][2][0];
						output.adult = output.adult * adult;
						output.adult = (output.adult / ((100 - sea_com()) / 100));					
						
						if(discount >= 1)
						{
							output.discount = rates[x][2][1] * discount;
							output.discount = (output.discount / ((100 - sea_com()) / 100));
						}
						
						if(route == 1)
						{
							output.adult = output.adult * 2;
							
							if(discount >= 1)
							{
								output.discount = output.discount * 2;
							}
						}
						
						
						
						output.adult = parseFloat(output.adult.toFixed(2));
						
						if(discount >= 1)
						{
							output.discount = parseFloat(output.discount.toFixed(2));	
						}
						
						//departure
						output.departure = {};					
						output.departure.location = $('[name="s_origin"]').find('option:selected').text();
						output.departure.date = $('[name="s_departure_date"]').val();
						output.departure.hour = $('[name="s_departure_hour"]').find('option:selected').text();
						
						//return
						output.return = {};
						output.return.location = $('[name="s_destination"]').find('option:selected').text();
						
						if(route == 1)
						{
							output.return.date = $('[name="s_return_date"]').val();
							output.return.hour = $('[name="s_return_hour"]').find('option:selected').text();					
						}
						
						//console.log(output);
						
						var total = output.adult;
						
						if(discount >= 1)
						{
							total = total + output.discount;
						}
						
						output.total = total;
						sea_build_itinerary(output, sea_fieldset);
					}
				}
			}			
		}, 100);			
	});
}

function sea_build_itinerary(obj, sea_fieldset)
{
	if(obj.adult > 0)
	{
		if(obj.hasOwnProperty('departure'))
		{
			if(obj.departure.location != '' && obj.departure.date != '' && (obj.departure.hour != '' || obj.departure.hour != '--'))
			{
				var html = $('#sea_itinerary_html')[0];
				var template = $.parseHTML($(html).html());
				
				var count_adults = $(sea_fieldset).find('[name="s_adults"] option:selected').text();
				var count_discount = $(sea_fieldset).find('[name="s_discount"] option:selected').text();
				var count_free = $(sea_fieldset).find('[name="s_free"] option:selected').text();
				
				if(count_adults > 0)
				{
					count_adults = parseInt(count_adults);
				}
				
				if(count_discount > 0)
				{
					count_discount = parseInt(count_discount);
				}
				else
				{
					count_discount = 0;
				}
				
				if(count_free > 0)
				{
					count_free = parseInt(count_free);
				}
				else
				{
					count_free = 0;
				}				
				
				$(template).find('.sea_deparute_l').text(obj.departure.location);
				$(template).find('.sea_deparute_d').text(obj.departure.date);
				$(template).find('.sea_deparute_h').text(obj.departure.hour);
				$(template).find('.sea_return_l').text(obj.return.location);
								
				if(obj.hasOwnProperty('return'))
				{
					if(obj.return.hasOwnProperty('date'))
					{
						$(template).find('.sea_return').removeClass('hidden');
						$(template).find('.sea_return_d').text(obj.return.date);
						$(template).find('.sea_return_h').text(obj.return.hour);
					}					
				}
				
				//count				
				$(template).find('.adult_count').text(count_adults);
				$(template).find('.free_count').text(count_free);
				$(template).find('.discount_count').text(count_discount);
				$(template).find('.sea_table_total').text('$'+obj.total.toFixed(2)+' USD');				
				
				if(count_discount == 0)
				{
					$(template).find('.sea_table_discount').remove();
				}
				else
				{
					$(template).find('.discount_total').text('$'+obj.discount+' USD');
				}
				
				if(count_free == 0)
				{
					$(template).find('.sea_table_free').remove();
				}
				else
				{
					$(template).find('.free_total').text('$'+obj.free+' USD');
				}

				//totals
				$(template).find('.adult_total').text('$'+obj.adult+' USD');
				
				
				
				var description = obj.departure.date+' ('+obj.departure.hour+') '+obj.departure.location+'-'+obj.return.location;
				
				if(obj.hasOwnProperty('return'))
				{
					if(obj.return.hasOwnProperty('date'))
					{
						description += ' || '+obj.return.date+' ('+obj.return.hour+') '+obj.return.location+'-'+obj.departure.location;
					}
				}
				
				$('[name="sea_description"]').val(description);
				$('[name="sea_total"]').val(obj.total);										
				
				$('.sea_itinerary_display').each(function(){
					var clone = $("<div />").append($(template).clone()).html();
					$(this).html(clone);
				});
			}
			else
			{
				$('.sea_itinerary_display').html('');
			}
		}
		else
		{
			$('.sea_itinerary_display').html('');
		}		
	}
	else
	{
		$('.sea_itinerary_display').html('');
	}	
}

function sea_roundtrip(sea_form)
{
	$(sea_form).find('[name="s_ferry"]').change(function(){
		$('.s_return').toggleClass('hidden');
	});	
}

function sea_passengers(sea_form)
{
	var adults = 0;
	var discount = 0;
	var free = 0;
	
	$(sea_form).find('[name="s_free"]').add('[name="s_discount"]').add('[name="s_adults"]').change(function(){
		var count = 0;
		count = count + parseFloat($('[name="s_free"]').val());
		count = count + parseFloat($('[name="s_discount"]').val());
		count = count + parseFloat($('[name="s_adults"]').val());
		$('[name="s_passengers"]').val(count);
		sea_passengers_build();
	});
	
}

function sea_passengers_build()
{
	var count = 0;
	count = count + parseFloat($('[name="s_free"]').val());
	count = count + parseFloat($('[name="s_discount"]').val());
	count = count + parseFloat($('[name="s_adults"]').val());
	var html = $.parseHTML($('#sea_passengers').html());
	var output = $('<div></div>');
	
	
	for(var x = 0; x < count; x++)
	{
		var clone = $(html).clone();
		
		$(clone).find('.s_p_el').text(x+1);
		$(clone).find('.s_p_name').attr({'data-id': 's_p_'+x+'_name'});
		$(clone).find('.s_p_id').attr({'data-id': 's_p_'+x+'_id'});
		$(clone).find('.s_p_age').attr({'data-id': 's_p_'+x+'_age'});
		
		//get val
		if (typeof(Storage) !== "undefined")
		{
			//get passenger list with no name
			$(clone).find('.s_p_name').val(sessionStorage.getItem('s_p_'+x+'_name'));
			$(clone).find('.s_p_id').val(sessionStorage.getItem('s_p_'+x+'_id'));
			$(clone).find('.s_p_age').val(sessionStorage.getItem('s_p_'+x+'_age'));					
		}		
		
		
		$(clone).appendTo(output);
	}
	
	$('#sea_passengers_container').html(output);
	sea_passengers_txt(output, $('[name="sea_pax"]'), $('.sea_passengers'), $('.sea_checkout'));
	
	$('.passengers_back').click(function(){
		$('.sea_passengers').addClass('hidden');
		$('.sea_booking').removeClass('hidden');
	});	
}

function sea_passengers_txt(html, pax, fieldset, checkout)
{
	$('.passengers_button').click(function(){
		var template = $(html);
		var output = '';
		
		$(template).find('.sea_passengers_wrap').each(function(x, o){
			
			if($(o).find('.s_p_name').val() != '' && $(o).find('.s_p_id').val() != '' && $(o).find('.s_p_age').val() != '')
			{
				var txt = $(o).find('.s_p_name').val()+' | '+$(o).find('.s_p_id').val()+' | '+$(o).find('.s_p_age').val()+'\n';
				output += String(txt);
			}
		});
		
		$(pax).val(output);
		
		if(sea_validate_fieldset(fieldset))
		{
			$(checkout).removeClass('hidden');
			sea_steps('Ferry Step 2');
		}
	});
}

function sea_route(sea_form)
{
	$(sea_form).find('[name="s_origin"]').add('[name="s_destination"]').change(function(){
		
		var invalids = 0;
		
		if($(this).val() == '--')
		{
			$(this).addClass('invalid_field');
			invalids++;
		}
		else
		{
			if($(this).attr('name') == 's_origin')
			{
				if($(this).val() == $('[name="s_destination"]').val())
				{
					$(this).addClass('invalid_field');
					$('[name="s_destination"]').addClass('invalid_field');
					invalids++;
				}
				else
				{
					if($('[name="s_destination"]').val() == '--')
					{
						invalids++;
					}
					else
					{
						if($(this).val() == 11 || $('[name="s_destination"]').val() == 11)
						{
							$(this).removeClass('invalid_field');
							$('[name="s_destination"]').removeClass('invalid_field');							
						}
						else
						{
							$(this).addClass('invalid_field');
							$('[name="s_destination"]').addClass('invalid_field');
							invalids++;		
						}
					}
				}				
			}
			else
			{
				if($(this).val() == $('[name="s_origin"]').val())
				{
					$(this).addClass('invalid_field');
					$('[name="s_origin"]').addClass('invalid_field');
					invalids++;
				}
				else
				{
					if($('[name="s_origin"]').val() == '--')
					{
						invalids++;
					}
					else
					{
						if($(this).val() == 11 || $('[name="s_origin"]').val() == 11)
						{
							$(this).removeClass('invalid_field');
							$('[name="s_origin"]').removeClass('invalid_field');							
						}
						else
						{
							$(this).addClass('invalid_field');
							$('[name="s_origin"]').addClass('invalid_field');
							invalids++;
						}
					}
				}					
			}
		}

		if(invalids == 0)
		{
			sea_options($('[name="s_origin"]').val(), $('[name="s_destination"]').val(), $('[name="s_ferry"]').val());
			$('.sea_spinner').removeClass('hidden');
		}
		else
		{
			sea_dates_stop();
			sea_hour_stop();
		}
		
	});
}

function sea_options(origin, destination, route)
{
	var url = 'https://ferrypearlislands.com/slp/public/ajax/tipopasajeros/json';
	url += '?destination_id1='+origin+'&destination_id2='+destination+'&roundtrip='+route
	
	 $.getJSON(sea_cors(url), function( data ) {
		
		//console.log(data);
		
		if(data != '')
		{
			if(data.hasOwnProperty('options'))
			{
				if(data.options.length > 0)
				{
					sea_schedules(origin, destination);
					sea_dates(origin, destination, route);
				}
				else
				{
					$('[name="s_destination"]').addClass('invalid_field');
					$('[name="s_origin"]').addClass('invalid_field');
					sea_hour_stop();
					sea_dates_stop();
				}
			}			
		}
	});
}

function sea_hour_stop()
{
	$('[name="s_departure_hour"]').html('<option>--</option>').attr({'disabled': 'disabled'});
	$('[name="s_return_hour"]').html('<option>--</option>').attr({'disabled': 'disabled'});	
}

function sea_schedules(origin, destination)
{
	var url = 'https://ferrypearlislands.com/slp/public/ajax/get_schedules_book';
	url += '?di1='+origin+'&di2='+destination;
	
	$.getJSON(sea_cors(url), function(data){
		
		//console.log(data);
		
		if(data != '')
		{
			if(data.hasOwnProperty('s1') && data.hasOwnProperty('s2'))
			{
				var departure = $.parseHTML(data.s1);
				var destination = $.parseHTML(data.s2);
				$(departure[0]).text('--').val('--');
				$(destination[0]).text('--').val('--');
				$('[name="s_departure_hour"]').html(departure).removeAttr('disabled');
				$('[name="s_return_hour"]').html(destination).removeAttr('disabled');
			}			
		}
	});
}

function sea_cors(url)
{
	return 'https://cors-anywhere.herokuapp.com/'+url;
}

function sea_dates(origin, destination, route)
{
	var url = 'https://ferrypearlislands.com/slp/public/ajax/avoid_dates/json';
	
	 $.getJSON(sea_cors(url), function(data) {
		
		if(data != '')
		{
			if(data.hasOwnProperty('avoid_dates'))
			{
				data = data.avoid_dates;
				var disabled_dates = {};
				disabled_dates.departure = [];
				disabled_dates.return = [];
				var now = Date.now();
				var today = new Date();
								
				for(var x = 0; x < data.length; x++)
				{
					if(data[x].hasOwnProperty('date'))
					{
						var date = new Date(data[x].date.split('-'));
						
						if(date.getTime() >= now)
						{
							if(data[x].destination_id1 == origin && data[x].destination_id2 == destination)
							{
								disabled_dates.departure.push(date);
							}
							if(data[x].destination_id1 == destination && data[x].destination_id2 == origin)
							{
								disabled_dates.return.push(date);
							}							
						}
					}
				}
								
				var args = {};
				args.format = 'yyyy-mm-dd';
				args.min = 1;
				
				
				//adds 1 day more if the clients tries to book the ferry after 3pm si el origin en Ciudad de Panama
				
				if(today.getHours() >= 17 && origin == 11)
				{
					args.min = 2;
				}
				
				args.disable = disabled_dates.departure;
								
				$('[name="s_departure_date"]').pickadate(args);
				args.disable = disabled_dates.return;
				
				$('[name="s_return_date"]').pickadate(args);
				$('.sea_picker').removeAttr('disabled');
				
				$('.sea_spinner').addClass('hidden');
				
				//console.log(disabled_dates);	
			}			
		}
	});	
}

function sea_dates_stop()
{
	var sea_picker = $('.sea_picker');
	$(sea_picker).attr({'disabled': 'disabled'});
	
	var $input = $(sea_picker).pickadate();
	var picker = $input.pickadate('picker')	;	
	picker.stop();	
}

function sea_fieldset()
{
	var sea_passengers = $('.sea_passengers')[0];
	var sea_booking = $('.sea_booking')[0];
	
	$(sea_booking).find('.booking_button').click(function()
	{	
		if(sea_validate_fieldset($(sea_booking)))
		{
			$('.sea_passengers').removeClass('hidden');
			sea_steps('Ferry Step 1');
		}
	});
	
	var sea_checkout = $('.sea_checkout')[0];
	$(sea_checkout).find('.checkout_back').click(function(){
		$(sea_checkout).addClass('hidden');
		$(sea_passengers).removeClass('hidden');
	});
}

function sea_validate_fieldset(fieldset)
{
	var invalids = 0;
	var fields = [];
		
	$(fieldset).find('input').add($(fieldset).find('select')).each(function(){
		
		var exclude_storage = ['CCNum', 'CVV2', 'sea_recaptcha', 'sea_pax', 's_passengers', 's_return_date', 's_return_hour'];
		var exclude = ['s_return_date_submit', 's_departure_date_submit'];
		var this_field = $(this);
		var name = null;
		var dataid = $(this_field).attr('data-id');	
		name = $(this_field).attr('name');
		
		if(typeof dataid !== typeof undefined && dataid !== false)
		{
			name = $(this_field).attr('data-id');
		}
		
		var value = $(this_field).val();
		
		if(value == '')
		{
			if($(fieldset).find('[name="s_ferry"]').val() == 0 && name == 's_return_date')
			{
				$(this_field).removeClass('invalid_field');
			}
			else if(exclude.includes(name))
			{
				$(this_field).removeClass('invalid_field');
			}
			else
			{
				invalids++;
				$(this_field).addClass('invalid_field');
				fields.push(name);				
			}
		}
		else
		{
			if(value == '--')
			{
				if($(fieldset).find('[name="s_ferry"]').val() == 0 && name == 's_return_hour')
				{
					$(this_field).removeClass('invalid_field');
				}
				else
				{
					invalids++;
					$(this_field).addClass('invalid_field');
					fields.push(name);
				}
			}
			else
			{
				$(this_field).removeClass('invalid_field');
				
				//save val
				if (typeof(Storage) !== 'undefined')
				{
					//save passenger list with no name
					
					if (typeof dataid !== typeof undefined && dataid !== false)
					{
						sessionStorage.setItem(dataid, value);
					}

					//var save inputs with name
					if (typeof name !== typeof undefined && name !== false)
					{
						if(!exclude_storage.includes(name))
						{
							sessionStorage.setItem(name, value);
						}
					}
				}
			}
		}		
	});
	
	if(invalids == 0)
	{
		//console.log($(fieldset).serializeArray());
		$(fieldset).addClass('hidden');
		return true;
	}
	else
	{
		if(typeof ga !== typeof undefined)
		{
			var eventArgs = {};
			eventArgs.eventAction = 'click';
			eventArgs.eventLabel = fields.join();
			eventArgs.eventCategory = 'Ferry Error';
			ga('send', 'event', eventArgs);
		}	
		
		console.log(fields);
		return false;
	}
}

function sea_country_dropdown(url, lang)
{
	$(window).on('load', function (e) {
		
		if($('.countrylist').length > 0)
		{
			$.getJSON( url + 'languages/countries/'+lang+'.json')
				.done(function(data) 
				{
					sea_country_options(data);
				})
				.fail(function()
				{
					$.getJSON(url + 'languages/countries/en.json', function(data) {

						sea_country_options(data);
					});				
				});
		}		
	});
}

function sea_country_options(data)
{
	$('.countrylist').each(function() {
		for (var x = 0; x < data.length; x++) 
		{
			$(this).append('<option value=' + data[x][0] + '>' + data[x][1] + '</option>');
		}
	});		
}


function sea_recaptcha()
{	
	var args = {};
	args.sitekey = sea_recaptcha_key();
	args.isolated = true;
	args.badge = 'inline';
	var sea_checkout_widget;
	
	if($('#sealasperlas_quote').length)
	{
		args.callback = function(token){
			return new Promise(function(resolve, reject) { 
				if(sea_validate_checkout(token) == false)
				{
					grecaptcha.reset(sea_checkout_widget);
				}
				resolve();
			});
		};
		sea_checkout_widget = grecaptcha.render('sea_confirm_checkout', args);
	}
}

function sea_focus_form(sea_form)
{
	$(sea_form).find('.booking_button').click(function(){
		$(sea_form).find('[data-id="s_p_0_name"]').focus();
	});	
	
	$(sea_form).find('.passengers_button').click(function(){
		$(sea_form).find('.sea_bycard').focus();
	});	
	
	$(sea_form).find('.sea_bycard').click(function(){
		$(sea_form).find('.sea_checkout_cc').find('input[name="fname"]').focus();
		sea_steps('Ferry Step 3');
	});
}

function sea_accordion()
{
	var accordion = $('#sea_accordion');
	
	$(accordion).find('.sea_accordion_content').each(function(){
		var this_content = $(this).find('li').not(':first-child');
		var this_button = $(this).find('.pointer');
		
		$(this_button).click(function(){
			$(this_content).slideToggle();
		});
		
	});
	
}