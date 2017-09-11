<div>
	<form class="newsmatch-donation-form form-inline level-<?php echo $level; ?>" action="<?php echo $url; ?>" method="POST">
		<div class="newsmatch-donate-label">I would like to donate:</div>
		<div class="form-group col-md-5 col-sm-5">
			<label class="newsmatch-donation-amount-label" for="newsmatch-donation-amount">I would like to donate:</label>
			<input type="number" name="newsmatch-donation-amount" class="newsmatch-donation-amount form-control" value="<?php echo $amount ?>" placeholder="Amount">
		</div>
		<div class="donation-frequency-group" role="group">
			<label class="donation-frequency" tabindex="0"><input type="radio" name="frequency" value="monthly">Per Month</label>
			<label class="donation-frequency selected" tabindex="0" ><input type="radio" name="frequency" value="yearly" checked>Per Year</label>
			<label class="donation-frequency" tabindex="0"><input type="radio" name="frequency" value="once">One Time</label>
		</div>
		<div class="error-message" role="alert" style="display: none;"></div>
		<div class="donation-level-message"></div>
		<div>
			<button type="submit">Give Now</button>
		</div>
		<input class="newsmatch-sf-campaign-id" name="newsmatch-sf-campaign-id" type="hidden" value="<?php echo $sf_campaign_id ?>">
	</form>
</div>
