/*Giftvoucher JS*/

function toggleTotalCode(){
	$$('.giftvoucher-total-code').each(function(el){
		el.toggle();
	});
}

function addGiftVoucher(){
	if ($('giftvoucher_code').value != ''){
		if ($('advice-required-entry-giftvoucher_passed'))
			$('advice-required-entry-giftvoucher_passed').hide();
		$('giftvoucher_add').hide();
		$('giftvoucher_wait').show();
		var url = $('giftvoucher_add').value + '/code/' + $('giftvoucher_code').value;
		var request = new Ajax.Request(url,{
			method:'post',
			postBody: '',
			onException: function (response, e){
				$('giftvoucher_message').update('<ul class="error-msg"><li>' + e + '</li></ul>');
			},
			onComplete: function (response){
				if (response.responseText.isJSON()){
					var res = response.responseText.evalJSON();
					var needUpdate = true;
					if (res.ajaxExpired && res.ajaxRedirect){
						setLocation(res.ajaxRedirect);
						needUpdate = false;
					}
					if (needUpdate){
						if (res.html){
							var container = $('payment_form_giftvoucher').parentNode;
							container.update(res.html);
							$('payment_form_giftvoucher').show();
						}else{
							$('giftvoucher_wait').hide();
							$('giftvoucher_add').show();
						}
						var message = '';
						if (res.success){
							message = '<ul class="success-msg"><li>' + res.success + '</li></ul>';
							if (typeof(save_shipping_method) != 'undefined')
								save_shipping_method(shipping_method_url);
						}
						if (res.error)
							message += '<ul class="error-msg"><li>' + res.error + '</li></ul>';
						if (message != '')
							$('giftvoucher_message').update(message);
					}
				}else{
					$('giftvoucher_wait').hide();
					$('giftvoucher_add').show();
					alert(response.responseText);
				}
			}
		});
	}else{
		payment.save();
		if ($('advice-required-entry-giftvoucher_passed'))
			$('advice-required-entry-giftvoucher_passed').hide();
	}
}

function removeGiftVoucher(el){
	var url = el.value;
	var idPrefix = el.id;
	var title = $(idPrefix+'_remove');
	var ajaxAlt = $(idPrefix+'_ajax');
	title.hide();
	el.hide();
	ajaxAlt.show();
	var request = new Ajax.Request(url,{
		method:'post',
		postBody: '',
		onException: function (response, e){
			$('giftvoucher_message').update('<ul class="error-msg"><li>' + e + '</li></ul>');
		},
		onComplete: function (response){
			if (response.responseText.isJSON()){
				var res = response.responseText.evalJSON();
				var needUpdate = true;
				if (res.ajaxExpired && res.ajaxRedirect){
					setLocation(res.ajaxRedirect);
					needUpdate = false;
				}
				if (needUpdate){
					if (res.html){
						var container = $('payment_form_giftvoucher').parentNode;
						container.update(res.html);
						$('payment_form_giftvoucher').show();
					}else{
						ajaxAlt.hide();
						el.show();
						title.show();
					}
					var message = '';
					if (res.success){
						message = '<ul class="success-msg"><li>' + res.success + '</li></ul>';
						if (typeof(save_shipping_method) != 'undefined')
								save_shipping_method(shipping_method_url);
					}
					if (res.error)
						message += '<ul class="error-msg"><li>' + res.error + '</li></ul>';
					if (message != '')
						$('giftvoucher_message').update(message);
				}
			}else{
				ajaxAlt.hide();
				el.show();
				title.show();
				alert(response.responseText);
			}
		}
	});
}