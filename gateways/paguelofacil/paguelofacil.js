$(function(){
	
	$('.sea_bycard').click(function(){
		$('.sea_checkout_cc').removeClass('hidden');
	});
	
});

function sea_validate_checkout(token)
{
	var sea_form = $('#sealasperlas_quote')[0];
	var invalid_field = 0;
	
	$(sea_form).find('input[name="sea_recaptcha"]').val(token);
	
	$(sea_form).find('input').add('select').each(function(){
			
		var this_field = $(this);
		var exclude_storage = ['CCNum', 'CVV2', 'sea_recaptcha', 'sea_pax', 's_passengers'];
		var name = $(this_field).attr('name');		
		
		if($(this_field).prop('tagName') == 'INPUT')
		{
			if($(this_field).attr('type') == 'text' || $(this_field).attr('type') == 'email' || $(this_field).attr('type') == 'number') 
			{
				if($(this_field).val() == '' && !$(this_field).hasClass('optional'))
				{
					if($('[name="s_ferry"]').val() == 0 && $(this_field).attr('name') == 's_return_date')
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
					else
					{
						$(this_field).addClass('invalid_field');
						invalid_field++;
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
		}
		else if($(this_field).prop('tagName') == 'SELECT')
		{
			if($(this_field).val() == '--')
			{
				$(this_field).addClass('invalid_field');
				invalid_field++;
				console.log($(this_field).attr('name'));							
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
	 });
				
	if (invalid_field == 0)
	{
		console.log(token);
		console.log($(sea_form).serializeArray());	
		$(sea_form).attr({'action': $(sea_form).attr('action')+'paguelofacil'});
		$(sea_form).submit();
	}
	return false;
}