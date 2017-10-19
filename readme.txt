=== News Match Donation Shortcode ===
Contributors: innlabs
Tags: donation, shortcode
Requires at least: 4.0
Tested up to: 4.8.1
Stable tag: 0.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Short description.

== Description ==

This plugin implements a shortcode allowing participating sites to better collect donations through the [Knight Foundation News Match](https://www.knightfoundation.org/articles/announcing-news-match-2017-2-million-fund-will-match-donations-to-nonprofit-newsrooms) program.

Example usage:
Add donation form with no Salesforce campaign id and no default donation amount specified:
`[newsmatch_donation_form]`

Add donation form with no Salesforce campaign id and $50.00 as the default donation amount:
`[newsmatch_donation_form amount="50"]`

Add a donation form with a Salesforce campaign id of `foo` and $25.00 as the default donation amount:
`[newsmatch_donation_form sf_campaign_id="foo" amount="25"]`

Add a donation form with a Salesforce campaign id of `foo` and do not specify a default donation amount:
`[newsmatch_donation_form sf_campaign_id="foo"]`

You may also desire to use a drop-down instead of buttons; to do that add `type="select"` to the shortcode:

```
[newsmatch_donation_form type="select"]

[newsmatch_donation_form amount="50" type="select"]

[newsmatch_donation_form sf_campaign_id="foo" amount="25" type="select"]

[newsmatch_donation_form sf_campaign_id="foo" type="select"]
```

== Frequently Asked Questions ==

== Styling ==

This plugin comes with a default stylesheet, `assets/css/donation.css`, which is output on pages that have the shortcode. 

If you do not want this stylesheet enqueued on pages where the donation shortcode is displayed, hook a filter on `newsmatch_donate_plugin_css_enqueue` that returns `False`. Within your filter function, you may want to enqueue your own stylesheet.

The structure of the buttons and `<select>`-based dropdown markup can be examined through your browser's inspector, or by [viewing the source code of the views](https://github.com/INN/news-match-donation-shortcode/tree/master/views).

If you wish to augment the plugin's existing styles, examine the `donation.css` file that comes with this plugin. You may want to:

- configure fonts:
	- `label.donation-frequency` for the donation buttons
	- `.donation-level-message` for the text that appears under the buttons
	- `.newsmatch-donation-form button[type="submit"]` for the submit button
	- `.newsmatch-donation-form` for the form in general
- configure colors:
	- `label.donation-frequency` for the donation buttons
	- `label.donation-frequency:hover` for the hovered donation button
	- `label.donation-frequency.selected` for the active donation button
	- `.newsmatch-donation-form button[type="submit"]` for the submit button
	- `.newsmatch-donation-form button[type="submit"]:hover` for the hovered submit button

== Changelog ==
