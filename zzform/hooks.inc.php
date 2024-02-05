<?php 

/**
 * mail module
 * common functions
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/mail
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2024 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * add header to mail in database
 *
 * @param string $header
 * @param string $body
 * @param int $mail_id
 * @return int
 */
function mf_mail_add_header_db($header, $body, $mail_id) {
	$header = strtolower($header);
	if (in_array($header, ['from', 'to', 'cc', 'bcc', 'reply-to']))
		$body = wrap_mail_name($body, false);

	$values = [];
	$values['action'] = 'insert';
	$values['ids'] = ['mail_id', 'header_field_category_id'];
	$values['POST']['mail_id'] = $mail_id;
	$values['POST']['header_field_category_id'] = wrap_category_id('mail-headers/'.$header);
	$values['POST']['header_body'] = $body;
	$ops = zzform_multi('mails-headers', $values);
	if (!$ops['id']) {
		wrap_error('Unable to add header %s (value %s) for mail ID %d, Reason: %s'
			, $header, $body, $mail_id, json_encode($ops['error'])
		);
	}
	return $ops['id'];
}
