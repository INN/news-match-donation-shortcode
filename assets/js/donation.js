var $ = jQuery;

jQuery(document).ready(function() {
	
	//donationController.init();

	var forms = $('form.newsmatch-donation-form').each(function(){
		var $form = $(this);
		donationInit($form);
	});

	$('.donation-frequency').keypress(function(e){
	    if(e.keyCode === 0 || e.keyCode === 32 || e.keyCode === 13 ){
	        $(this).find('input').trigger('click');
	    }
	});
});

/**
 * Initialize the donation form.
 */
function donationInit(form){
	var $amount_input = form.find('.newsmatch-donation-amount');
	var amount = $amount_input.val();
	var $frequency_checked;
	var level;

	if (form.hasClass('level-business')){
		level = 'business';
	} else if (form.hasClass('level-nonprofit')){
		level = 'nonprofit';
	} else {
		level = 'individual';
	}

	if (form.hasClass('type-select')){
		$frequency_checked = form.find('select[name="frequency"]');
	} else {
		$frequency_checked = form.find('input[name="frequency"]:checked');
	}
	var frequency = $frequency_checked.val();
	var donation_level = getDonationLevel(amount, frequency, level, form);
	var $message = form.find('.donation-level-message');

	// Initialize donation level message when the page loads
	$message.html(donation_level);

	// -------------------------------------------------------------------------------------------------------------
	// WIRE UP EVENTS
	// -------------------------------------------------------------------------------------------------------------
	$amount_input.on('change', function(e) {
		var $input = $(this);
		if (form.hasClass('type-select')){
			$frequency = form.find('select[name="frequency"]').val();
		} else {
			$frequency = form.find('input[name="frequency"]:checked').val();
		}
		$message.html(getDonationLevel(
			$input.val(),
			$frequency,
			level,
			form
		));
	});

	// Update donation level message when user changes the frequency
	if (form.hasClass('type-select')){
		var $frequency_select = form.find('select[name="frequency"]');
		$frequency_select.on('change', function(e) {
			var $select = $(this);
			$message.html(getDonationLevel(
				$amount_input.val(),
				$select.val(),
				level,
				form
			));
		});
	} else {
		var $frequency_input = form.find('input[name="frequency"]');
		var $donation_frequency = form.find('.donation-frequency');
		$frequency_input.on('click', function(e) {
			var $this = $(this);
			$donation_frequency.removeClass('selected');
			$this.parent().addClass('selected');
			$('.donation-frequency input').removeAttr('checked');
			$this.attr('checked', 'checked');

			$message.html(getDonationLevel(
				$amount_input.val(),
				$this.val(),
				level,
				form
			));
		});
	}

	// Append querystring params and redirect user
	form.on('submit', function(e) {
		e.preventDefault();

		var $amount = form.find('.newsmatch-donation-amount');
		var $frequency;
		if (form.hasClass('type-select')){
			$frequency = form.find('select[name="frequency"]');
		} else {
			$frequency = form.find('input[name="frequency"]:checked');
		}
		var $campaign = form.find('.newsmatch-sf-campaign-id');

		if (!isInputValid($amount.val(), $frequency.val(), form)) {
			return false;
		}

		var url = form.attr('action');
		if (url.substr(url.length - 1) !== '/') {
			url += '/';
		}

		var amount = +(parseFloat($amount_input.val()).toFixed(2));
		if (amount <= 0) {
			amount = parseFloat(15).toFixed(2);
		}

		if ($frequency.val() === 'once') {
			url
				+= 'donateform'
				+ '?org_id=newsmatch'
				+ '&amount=' + amount.toFixed(2);
		} else {
			url += 'memberform'
				+ '?org_id=newsmatch'
				+ '&amount=' + amount.toFixed(2)
				+ '&installmentPeriod=' + $frequency.val();
		}

		if ($campaign.val()) {
			url += "&campaign=" + $campaign.val();
		}

		window.location.assign(encodeURI(url));
	});

}
	
	

/**
 * Determine the correct donation level message based on the dollar amount and the frequency of
 * donation.
 *
 * @param  number amount    The dollar amount of the donation.
 * @param  string frequency How often the donation should occur (monthly|yearly|once).
 * @return string           The message to display.
 */
function getDonationLevel(amount, frequency, type, form) {
	if (!isInputValid(amount, frequency, form)) {
		return; // TODO: Handle error condition
	}

	var roundedAmount = +(parseFloat(amount).toFixed(2));
	console.log("getDonationLevel::roundedAmount = ", roundedAmount);
	var level = '';
	var supporter = false;
	
	if (frequency === 'monthly') {
		// detemine level and update text based on monthly frequency
		// if (roundedAmount > 0 && roundedAmount <= 2.083 ) {
		// 	supporter = true;
		// 	level = 'a <strong>Supporting Non-member</strong>';
		// } else if (roundedAmount > 2.083 && roundedAmount <= 8.34) {
		// 	level = 'an <strong>Ally</strong>';
		// } else if (roundedAmount > 8.34 && roundedAmount <= 20.83) {
		// 	level = 'an <strong>Enthusiast</strong>';
		// } else if (roundedAmount > 20.83 && roundedAmount <= 83.34) {
		// 	level = 'an <strong>Advocate</strong>';
		// } else if (roundedAmount > 83.34) {
		// 	level = 'an <strong>Ambassador</strong>';
		// }

		if (roundedAmount > 0 && roundedAmount < levels.l1_min/12 ) {
			supporter = true;
			level = levels.gd_a + ' <strong>' + levels.gd_name + '</strong>';
		} else if (roundedAmount >= levels.l1_min/12 && roundedAmount < levels.l1_max/12) {
			level = levels.l1_a + ' <strong>' + levels.l1_name + '</strong>';
		} else if (roundedAmount >= levels.l2_min/12 && roundedAmount < levels.l2_max/12) {
			level = levels.l2_a + ' <strong>' + levels.l2_name + '</strong>';
		} else if (roundedAmount >= levels.l3_min/12 && roundedAmount < levels.l3_max/12) {
			level = levels.l3_a + ' <strong>' + levels.l3_name + '</strong>';
		} else if (roundedAmount >= levels.l3_max/12) {
			level = levels.l4_a + ' <strong>' + levels.l4_name + '</strong>';
		}

	} else {
		// detemine level and update text based on yearly frequency
		// if (roundedAmount < 25) {
		// 	supporter = true;
		// 	level = 'a <strong>Supporting Non-member</strong>';
		// } else if (roundedAmount >= 25 && roundedAmount < 100) {
		// 	level = 'an <strong>Ally</strong>';
		// } else if (roundedAmount >= 100 && roundedAmount < 250) {
		// 	level = 'an <strong>Enthusiast</strong>';
		// } else if (roundedAmount >= 250 && roundedAmount < 1000) {
		// 	level = 'an <strong>Advocate</strong>';
		// } else if (roundedAmount >= 1000) {
		// 	level = 'an <strong>Ambassador</strong>';
		// }

		if (roundedAmount > 0 && roundedAmount < levels.l1_min ) {
			supporter = true;
			level = levels.gd_a + ' <strong>' + levels.gd_name + '</strong>';
		} else if (roundedAmount >= levels.l1_min && roundedAmount < levels.l1_max) {
			level = levels.l1_a + ' <strong>' + levels.l1_name + '</strong>';
		} else if (roundedAmount >= levels.l2_min && roundedAmount < levels.l2_max) {
			level = levels.l2_a + ' <strong>' + levels.l2_name + '</strong>';
		} else if (roundedAmount >= levels.l3_min && roundedAmount < levels.l3_max) {
			level = levels.l3_a + ' <strong>' + levels.l3_name + '</strong>';
		} else if (roundedAmount >= levels.l3_max) {
			level = levels.l4_a + ' <strong>' + levels.l4_name + '</strong>';
		}
	}

	var message = '';
	if (supporter){
		message = 'This gift will make you ' + level + '.';
	} else {
		message = 'This gift will make you ' + level + ' member.';
	}
	return message;
}

/**
 * Verify the amount and frequency values are valid.
 *
 * @param  number  amount    The donation amount
 * @param  string  frequency How often the donation should occur (monthly|yearly|once)
 * @return Boolean           true if the values are valid; otherwise false.
 */
function isInputValid(amount, frequency, form) {
	if (!amount) {
		setErrorMessage("Numerical amount must be provided.", form);
		return false;
	}

	if (isNaN(amount)) {
		setErrorMessage("Amount must be a number.", form);
		return false;
	}

	var parsedAmount = +(parseFloat(amount).toFixed(2));
	if (parsedAmount < 5.00 || parsedAmount > 999999.99) {
		setErrorMessage("Minimum donation amount is $5.00.", form);
		return false;
	}

	if (!frequency) {
		setErrorMessage("Frequency must be provided.", form);
		return false;
	}

	form.find('.error-message').text('').hide();
	return true;
}

/**
 * Display an error message if the input fails validation.
 *
 * @param string msg    The error text for display to the user.
 */
function setErrorMessage(msg, form) {
	form.find('.error-message')
		.addClass('alert alert-danger')
		.text(msg)
		.fadeIn();
	form.find('.donation-level-message').html('');
}


