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


$zz['title'] = 'Mail Attachements';
$zz['table'] = '/*_PREFIX_*/mails_media';

$zz['fields'][1]['field_name'] = 'mail_medium_id';
$zz['fields'][1]['type'] = 'id';

$zz['fields'][2]['title'] = 'Mail';
$zz['fields'][2]['field_name'] = 'mail_id';
$zz['fields'][2]['type'] = 'select';
$zz['fields'][2]['sql'] = 'SELECT mail_id, mail_date
	FROM /*_PREFIX_*/mails
	ORDER BY mail_date, mail_id';
$zz['fields'][2]['display_field'] = 'mail_date';

$zz['fields'][4]['field_name'] = 'sequence';
$zz['fields'][4]['type'] = 'number';

$zz['fields'][5]['title'] = 'Preview';
$zz['fields'][5]['field_name'] = 'image';
$zz['fields'][5]['type'] = 'image';
$zz['fields'][5]['class'] = 'preview';
$zz['fields'][5]['path'] = [
	'root' => wrap_setting('media_folder'), 
	'webroot' => wrap_setting('files_path'),
	'string1' => '/',
	'field1' => 'filename',
	'string2' => '.',
	'string3' => wrap_setting('media_preview_size'),
	'string4' => '.',
	'extension' => 'thumb_extension',
	'webstring1' => '?v=',
	'webfield1' => 'version'
];
$zz['fields'][5]['path']['extension_missing'] = [
	'string3' => wrap_setting('media_original_filename_extension'),
	'extension' => 'extension'
];

$zz['fields'][3]['title'] = 'Medium';
$zz['fields'][3]['field_name'] = 'medium_id';
$zz['fields'][3]['id_field_name'] = '/*_PREFIX_*/media.medium_id';
$zz['fields'][3]['type'] = 'select';
$zz['fields'][3]['sql'] = sprintf('SELECT /*_PREFIX_*/media.medium_id
		, folders.title AS folder
		, CONCAT("[", /*_PREFIX_*/media.medium_id, "] ", /*_PREFIX_*/media.title) AS image
	FROM /*_PREFIX_*/media 
	LEFT JOIN /*_PREFIX_*/media folders
		ON /*_PREFIX_*/media.main_medium_id = folders.medium_id
	WHERE /*_PREFIX_*/media.filetype_id != %d
	ORDER BY folders.title, /*_PREFIX_*/media.filename', wrap_filetype_id('folder'));
$zz['fields'][3]['sql_character_set'][1] = 'utf8';
$zz['fields'][3]['sql_character_set'][2] = 'utf8';
$zz['fields'][3]['display_field'] = 'image';
$zz['fields'][3]['group'] = 'folder';
$zz['fields'][3]['exclude_from_search'] = true;

$zz['fields'][99]['field_name'] = 'last_update';
$zz['fields'][99]['type'] = 'timestamp';
$zz['fields'][99]['hide_in_list'] = true;

$zz['subselect']['sql'] = 'SELECT mail_id, filename, version
		, t_mime.extension AS thumb_extension
		, o_mime.extension
	FROM /*_PREFIX_*/mails_media
	LEFT JOIN /*_PREFIX_*/media USING (medium_id)
	LEFT JOIN /*_PREFIX_*/filetypes AS o_mime USING (filetype_id)
	LEFT JOIN /*_PREFIX_*/filetypes AS t_mime 
		ON /*_PREFIX_*/media.thumb_filetype_id = t_mime.filetype_id
	WHERE o_mime.mime_content_type = "image"
';
$zz['subselect']['image'] = $zz['fields'][5]['path'];

$zz['sql'] = 'SELECT /*_PREFIX_*/mails_media.*
		, CONCAT("[", /*_PREFIX_*/media.medium_id, "] ", /*_PREFIX_*/media.title) AS image
		, /*_PREFIX_*/media.filename, /*_PREFIX_*/media.version
		, t_mime.extension AS thumb_extension
		, o_mime.extension AS extension
		, CONCAT("[", mail_id, "] ", /*_PREFIX_*/mails.mail_date) AS mail_date
	FROM /*_PREFIX_*/mails_media
	LEFT JOIN /*_PREFIX_*/mails USING (mail_id)
	LEFT JOIN /*_PREFIX_*/media USING (medium_id)
	LEFT JOIN /*_PREFIX_*/filetypes AS o_mime USING (filetype_id)
	LEFT JOIN /*_PREFIX_*/filetypes AS t_mime 
		ON /*_PREFIX_*/media.thumb_filetype_id = t_mime.filetype_id
';
$zz['sqlorder'] = ' ORDER BY mail_date, mail_id';
