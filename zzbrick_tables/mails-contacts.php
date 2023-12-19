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


$zz['title'] = 'Senders and Recipients';
$zz['table'] = '/*_PREFIX_*/mails_contacts';

$zz['fields'][1]['field_name'] = 'mail_contact_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['title'] = 'Mail';
$zz['fields'][2]['field_name'] = 'mail_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT mail_id, mail_date
	FROM /*_PREFIX_*/mails
	ORDER BY mail_date, mail_id';
$zz['fields'][2]['display_field'] = 'mail_date';

$zz['fields'][4]['title'] = 'Header Field';
$zz['fields'][4]['field_name'] = 'header_field_category_id';
$zz['fields'][4]['type'] = 'select';
$zz['fields'][4]['display_field'] = 'category';
$zz['fields'][4]['sql'] = sprintf('SELECT category_id, category
	FROM categories
	WHERE main_category_id = %d
	AND parameters LIKE "%%&type=mailto%%"', wrap_category_id('mail-headers'));

$zz['fields'][3]['field_name'] = 'contact_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['sql'] = 'SELECT contact_id, contact
	FROM /*_PREFIX_*/contacts
	ORDER BY identifier';
$zz['fields'][3]['display_field'] = 'contact';

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;


$zz['sql'] = 'SELECT /*_PREFIX_*/mails_contacts.*
		, category, contact
		, CONCAT("[", mail_id, "] ", /*_PREFIX_*/mails.mail_date) AS mail_date
	FROM /*_PREFIX_*/mails_contacts
	LEFT JOIN /*_PREFIX_*/contacts USING (contact_id)
	LEFT JOIN /*_PREFIX_*/mails USING (mail_id)
	LEFT JOIN /*_PREFIX_*/categories
		ON /*_PREFIX_*/categories.category_id = /*_PREFIX_*/mails_contacts.header_field_category_id
';
$zz['sqlorder'] = ' ORDER BY mail_date, mail_id';
