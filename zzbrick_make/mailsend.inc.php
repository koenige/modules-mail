<?php 

/**
 * mail module
 * send an e-mail
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/mail
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2024 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


/**
 * send an e-mail
 *
 * @param array $params
 *		int [0] ID of e-mail
 * @return array
 */
function mod_mail_make_mailsend($params) {
	if (count($params) !== 1) return false;
	
	$sql = 'SELECT mail_id, mail, mail_date, mail_status_category_id
		FROM mails
		WHERE mail_id = %d';
	$sql = sprintf($sql, $params[0]);
	$data = wrap_db_fetch($sql);
	if (!$data) return false;
	
	if ($data['mail_status_category_id'] !== wrap_category_id('mail-status/draft')) {
		$data['already_sent'] = true;
		$page['text'] = wrap_template('mailsend', $data);
		return $page;
	}
	
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		$data['need_post'] = true;
		$page['text'] = wrap_template('mailsend', $data);
		return $page;
	}

	$sql = 'SELECT category, header_body
		FROM mails_headers
		LEFT JOIN categories
			ON mails_headers.header_field_category_id = categories.category_id
		WHERE mail_id = %d';
	$sql = sprintf($sql, $data['mail_id']);
	$data['headers'] = wrap_db_fetch($sql, '_dummy_', 'key/value');

	// @todo read mails_media
	
	$headers = $data['headers'];
	unset($headers['To']);
	unset($headers['Subject']);
	$mail = [
		'message' => $data['mail'],
		'subject' => $data['headers']['Subject'] ?? wrap_text('E-Mail via %s', ['values' => [wrap_setting('site')]]),
		'to' => $data['headers']['To'] ?? wrap_setting('own_e_mail'),
		'headers' => $headers
	];
	$data['successful_sent'] = wrap_mail($mail);
	if ($data['successful_sent']) {
		wrap_include_files('zzform/batch', 'mail');
		mf_mail_update_status_db($data['mail_id']);
	}
	
	$page['text'] = wrap_template('mailsend', $data);
	return $page;
}