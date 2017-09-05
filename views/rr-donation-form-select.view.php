<div>
	<form class="rivard-donation-form form-inline type-select level-<?php echo $level; ?>" action="<?php echo $url; ?>" method="POST">
		<div class="rivard-donate-label">I would like to donate:</div>
		<div class="form-group col-md-5 col-sm-5">
			<label class="rivard-donation-amount-label" for="rivard-donation-amount">I would like to donate:</label>
			<input type="number" name="rivard-donation-amount" class="rivard-donation-amount form-control" value="<?php echo $amount ?>" placeholder="Amount">
		</div>
		<select class="donation-frequencies" role="group" name="frequency">
			<option value="monthly">Per Month</option>
			<option value="yearly" selected>Per Year</option>
			<option value="once">One Time</option>
		</select>
		<div class="error-message" role="alert" style="display: none;"></div>
		<div class="donation-level-message"></div>
		<div>
			<button type="submit">Give Now</button>
		</div>
		<input class="rivard-sf-campaign-id" name="rivard-sf-campaign-id" type="hidden" value="<?php echo $sf_campaign_id ?>">
	</form>
</div>
