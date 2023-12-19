<?php 

/**
 * mail module
 * Table definition for 'mails-headers'
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/mail
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2023 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Mail Headers';
$zz['table'] = '/*_PREFIX_*/mails_headers';

$zz['fields'][1]['field_name'] = 'mail_header_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['title'] = 'Mail';
$zz['fields'][2]['field_name'] = 'mail_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT mail_id, mail_date
	FROM /*_PREFIX_*/mails
	ORDER BY mail_date, mail_id';
$zz['fields'][2]['display_field'] = 'mail_date';

$zz['fields'][3]['title'] = 'Header Field';
$zz['fields'][3]['field_name'] = 'header_field_category_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['display_field'] = 'category';
$zz['fields'][3]['sql'] = sprintf('SELECT category_id, category
	FROM categories
	WHERE main_category_id = %d', wrap_category_id('mail-headers'));

$zz['fields'][4]['title'] = 'Header Body';
$zz['fields'][4]['field_name'] = 'header_body';

$zz['sql'] = 'SELECT /*_PREFIX_*/mails_headers.*
		, category
		, CONCAT("[", mail_id, "] ", /*_PREFIX_*/mails.mail_date) AS mail_date
	FROM /*_PREFIX_*/mails_headers
	LEFT JOIN /*_PREFIX_*/mails USING (mail_id)
	LEFT JOIN /*_PREFIX_*/categories
		ON /*_PREFIX_*/categories.category_id = /*_PREFIX_*/mails_headers.header_field_category_id
';
$zz['sqlorder'] = ' ORDER BY mail_date, mail_id';
