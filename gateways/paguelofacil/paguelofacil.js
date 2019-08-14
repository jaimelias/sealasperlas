$(function(){
	
	$('.sea_bycard').click(function(){
		$('.sea_checkout_cc').removeClass('hidden');
	});
	
});

function sea_validate_checkout(token)
{
	var sea_form = $('#sealasperlas_quote')[0];
	var invalids = 0;
	var fields = [];
	
	$(sea_form).find('input[name="sea_recaptcha"]').val(token);
	
	$(sea_form).find('input').add('select').each(function(){
			
		var this_field = $(this);
		var exclude_storage = ['CCNum', 'CVV2', 'sea_recaptcha', 'sea_pax', 's_passengers', 's_return_date', 's_return_hour'];
		var exclude = ['s_return_date_submit', 's_departure_date_submit'];
		var name = $(this_field).attr('name');
		
		
		if($(this_field).val() == '')
		{
			if($('[name="s_ferry"]').val() == 0 && $(this_field).attr('name') == 's_return_date')
			{
				$(this_field).removeClass('invalid_field');
			}
			else if(exclude.includes(name))
			{
				$(this_field).removeClass('invalid_field');
			}			
			else
			{
				$(this_field).addClass('invalid_field');
				invalids++;
				fields.push(name);
				console.log($(this_field).attr('name'));		
			}
		}
		else
		{
			if($(this_field).val() == '--')
			{
				if($('[name="s_ferry"]').val() == 0 && $(this_field).attr('name') == 's_return_hour')
				{
					$(this_field).removeClass('invalid_field');
				}
				else
				{
					$(this_field).addClass('invalid_field');
					invalids++;
					fields.push(name);
					console.log($(this_field).attr('name'));					
				}							
			}
			else
			{
				$(this_field).removeClass('invalid_field');
				
				if (typeof(Storage) !== 'undefined')
				{
					if (typeof name !== typeof undefined && name !== false)
					{
						if(!exclude_storage.includes(name))
						{
							sessionStorage.setItem(name, $(this).val());
						}
					}
				}				
			}
		}
	 });
				
	if (invalids == 0)
	{
		console.log(token);
		console.log($(sea_form).serializeArray());	
		$(sea_form).attr({'action': $(sea_form).attr('action')+'paguelofacil'});
		$(sea_form).submit();
	}
	else
	{
		if(typeof ga !== typeof undefined)
		{
			var eventArgs = {};
			eventArgs.eventAction = 'submit';
			eventArgs.eventLabel = fields.join();
			eventArgs.eventCategory = 'Ferry Error';
			ga('send', 'event', eventArgs);
		}		
	}
	return false;
}