=== News Match Donation Shortcode ===
Contributors: innlabs
Tags: donation, shortcode
Requires at least: 4.0
Tested up to: 4.8.1
Stable tag: 0.1
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

== Frequently Asked Questions ==

== Changelog ==
