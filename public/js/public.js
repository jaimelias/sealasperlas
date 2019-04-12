$(function(){
	var sea_form = $('#sealasperlas_quote');
	
	if($(sea_form).length > 0)
	{
		sea_route(sea_form);
		sea_passengers(sea_form);
		sea_roundtrip(sea_form);
		sea_fieldset(sea_form);
	}
});

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
						$(this).removeClass('invalid_field');
						$('[name="s_destination"]').removeClass('invalid_field');
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
						$(this).removeClass('invalid_field');
						$('[name="s_origin"]').removeClass('invalid_field');						
					}
				}					
			}
		}

		if(invalids == 0)
		{
			sea_options($('[name="s_origin"]').val(), $('[name="s_destination"]').val(), $('[name="s_ferry"]').val());
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
		
		console.log(data);
		
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
	
	 $.getJSON(sea_cors(url), function( data ) {
		
		console.log(data);
		
		if(data != '')
		{
			if(data.hasOwnProperty('s1') && data.hasOwnProperty('s2'))
			{
				var departure = $.parseHTML(data.s1);
				var destination = $.parseHTML(data.s2);			
				$('[name="s_departure_hour"]').html(departure[1]).attr({'selected': 'selected'}).removeAttr('disabled');
				$('[name="s_return_hour"]').html(destination[1]).attr({'selected': 'selected'}).removeAttr('disabled');
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
				args.disable = disabled_dates.departure;
								
				$('[name="s_departure_date"]').pickadate(args);
				args.disable = disabled_dates.return;
				
				$('[name="s_return_date"]').pickadate(args);
				$('.sea_picker').removeAttr('disabled');
				
				console.log(disabled_dates);	
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

function sea_fieldset(sea_form)
{
	$(sea_form).find('.booking_button').click(function()
	{	
		sea_validate_fieldset($('fieldset.sea_booking'));
	});
}

function sea_validate_fieldset(sea_fieldset)
{
	var invalids = 0;
	
	$(sea_fieldset).find('input').add('select').each(function(){
		
		if($(this).val() == '')
		{
			if($(sea_fieldset).find('[name="s_ferry"]').val() == 0 && $(this).attr('name') == 's_return_date')
			{
				$(this).removeClass('invalid_field');
			}
			else
			{
				invalids++;
				$(this).addClass('invalid_field');
				console.log($(this).attr('name')+ ' invalid');				
			}
		}
		else
		{
			
			if($(this).val() == '--')
			{
				if($(sea_fieldset).find('[name="s_ferry"]').val() == 0 && $(this).attr('name') == 's_return_hour')
				{
					$(this).removeClass('invalid_field');
				}
				else
				{
					invalids++;
					$(this).addClass('invalid_field');
					console.log($(this).attr('name')+ ' invalid');					
				}
			}	
			else
			{
				$(this).removeClass('invalid_field');
			}
		}
	});
	
	if(invalids == 0)
	{
		console.log($(sea_fieldset).serializeArray());
		$(sea_fieldset).hide();
	}
}