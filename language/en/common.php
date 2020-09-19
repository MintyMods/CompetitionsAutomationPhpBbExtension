<?php
/**
 *
 * Minty Competition Automation. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'SPONSOR_FORUM_SELECT'	=> 'Sponsor Templates',
	'RULE_FORUM_SELECT'	=> 'Rules Templates',
	'PRIZE_FORUM_SELECT'	=> 'Prize Templates',
	'SPONSOR_FORUM_DESC'	=> 'Select the forum containing the template posts for Sponsors e.g. Posts starting with SPONSOR',
	'RULE_FORUM_DESC'	=> 'Select the forum containing the template posts for Rules e.g. Posts starting with RULE',
	'PRIZE_FORUM_DESC'	=> 'Select the forum containing the template posts for Prizes e.g. Posts starting with PRIZE',
	'COMPETITIONS_GOODBYE'		=> 'Goodbye %s!',
	'ACP_COMPETITIONS_GOODBYE'			=> 'Test',
	'ACP_COMPETITIONS_SETTING_SAVED'	=> 'Settings have been saved successfully!',
	'MINTY_COMPETITIONS_NOTIFICATION'	=> 'Minty Competition Automation notification',
	'COMPETITIONS_PAGE'			=> 'Competitions',
	'VIEWING_MINTY_COMPETITIONS'			=> 'Viewing Minty Competition Automation',

));
