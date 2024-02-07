<?php 

/**
 * mail module
 * Table definition for 'mails'
 *
 * Part of »Zugzwang Project«
 * https://www.zugzwang.org/modules/mail
 *
 * @author Gustaf Mossakowski <gustaf@koenige.org>
 * @copyright Copyright © 2023-2024 Gustaf Mossakowski
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL-3.0
 */


$zz['title'] = 'Mails';
$zz['table'] = '/*_PREFIX_*/mails';

$zz['fields'][1]['title'] = 'ID';
$zz['fields'][1]['field_name'] = 'mail_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][3]['title'] = 'Date';
$zz['fields'][3]['field_name'] = 'mail_date';
$zz['fields'][3]['type'] = 'datetime';
$zz['fields'][3]['time_format'] = 'H:i:s';
$zz['fields'][3]['default'] = date('Y-m-d H:i:s');

$zz['fields'][6] = zzform_include('mails-headers');
$zz['fields'][6]['title'] = 'Recipients';
$zz['fields'][6]['type'] = 'subtable';
$zz['fields'][6]['table_name'] = 'headers_recipients';
$zz['fields'][6]['min_records'] = 1;
$zz['fields'][6]['form_display'] = 'lines';
$zz['fields'][6]['fields'][2]['type'] = 'foreign_key';
$zz['fields'][6]['sql'] = wrap_edit_sql(
	$zz['fields'][6]['sql'], 'WHERE', sprintf('header_field_category_id NOT IN (%d, %d)',
	wrap_category_id('mail-headers/subject'), wrap_category_id('mail-headers/from'))
);
$zz['fields'][6]['fields'][3]['sql'] = wrap_edit_sql(
	$zz['fields'][6]['fields'][3]['sql'], 'WHERE',
	sprintf('parameters LIKE "%s"', '%type=mailto%')
);
$zz['fields'][6]['hide_in_list'] = true;

$zz['fields'][5] = zzform_include('mails-headers');
$zz['fields'][5]['title'] = 'Subject';
$zz['fields'][5]['type'] = 'subtable';
$zz['fields'][5]['table_name'] = 'headers_subject';
$zz['fields'][5]['min_records'] = 1;
$zz['fields'][5]['min_records_required'] = 1;
$zz['fields'][5]['max_records'] = 1;
$zz['fields'][5]['form_display'] = 'inline';
$zz['fields'][5]['display_field'] = 'subject';
$zz['fields'][5]['sql'] = wrap_edit_sql(
	$zz['fields'][5]['sql'], 'WHERE', sprintf('header_field_category_id = %d',
	wrap_category_id('mail-headers/subject'))
);
$zz['fields'][5]['fields'][2]['type'] = 'foreign_key';
$zz['fields'][5]['fields'][3]['type'] = 'hidden';
$zz['fields'][5]['fields'][3]['value'] = wrap_category_id('mail-headers/subject');
$zz['fields'][5]['fields'][3]['hide_in_form'] = true;
$zz['fields'][5]['fields'][4]['title'] = 'Subject';

$zz['fields'][7] = zzform_include('mails-headers');
$zz['fields'][7]['title'] = 'From';
$zz['fields'][7]['type'] = 'subtable';
$zz['fields'][7]['table_name'] = 'headers_sender';
$zz['fields'][7]['min_records'] = 1;
$zz['fields'][7]['min_records_required'] = 1;
$zz['fields'][7]['max_records'] = 1;
$zz['fields'][7]['form_display'] = 'inline';
$zz['fields'][7]['sql'] = wrap_edit_sql(
	$zz['fields'][7]['sql'], 'WHERE', sprintf('header_field_category_id = %d',
	wrap_category_id('mail-headers/from'))
);
$zz['fields'][7]['fields'][2]['type'] = 'foreign_key';
$zz['fields'][7]['fields'][3]['type'] = 'hidden';
$zz['fields'][7]['fields'][3]['value'] = wrap_category_id('mail-headers/from');
$zz['fields'][7]['fields'][3]['hide_in_form'] = true;
$zz['fields'][7]['fields'][4]['title'] = 'From';
$zz['fields'][7]['hide_in_list'] = true;

$zz['fields'][2]['title'] = 'Message';
$zz['fields'][2]['field_name'] = 'mail';
$zz['fields'][2]['type'] = 'memo';
$zz['fields'][2]['rows'] = 5;
$zz['fields'][2]['hide_in_list'] = true;

$zz['fields'][8] = zzform_include('mails-media');
$zz['fields'][8]['title'] = 'Attachments';
$zz['fields'][8]['type'] = 'subtable';
$zz['fields'][8]['table_name'] = 'attachments';
$zz['fields'][8]['min_records'] = 1;
$zz['fields'][8]['form_display'] = 'lines';
$zz['fields'][8]['fields'][2]['type'] = 'foreign_key';

$zz['fields'][4]['title'] = 'Status';
$zz['fields'][4]['field_name'] = 'mail_status_category_id';
$zz['fields'][4]['type'] = 'select';
$zz['fields'][4]['display_field'] = 'category';
$zz['fields'][4]['sql'] = sprintf('SELECT category_id, category
	FROM categories
	WHERE main_category_id = %d', wrap_category_id('mail-status'));
$zz['fields'][4]['default'] = wrap_category_id('mail-status/draft');

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;


$zz['sql'] = 'SELECT /*_PREFIX_*/mails.*
		, category
		, subjects.header_body AS subject
	FROM /*_PREFIX_*/mails
	LEFT JOIN /*_PREFIX_*/categories
		ON /*_PREFIX_*/categories.category_id = /*_PREFIX_*/mails.mail_status_category_id
	LEFT JOIN /*_PREFIX_*/mails_headers subjects
		ON subjects.mail_id = /*_PREFIX_*/mails.mail_id
		AND subjects.header_field_category_id = %d
';
$zz['sql'] = sprintf($zz['sql'], wrap_category_id('mail-headers/subject'));
$zz['sqlorder'] = ' ORDER BY mail_date DESC, mail_id DESC';
