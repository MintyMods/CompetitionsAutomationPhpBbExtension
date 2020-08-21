<?php
/**
 *
 * Minty Competition Automation. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\competitions\acp;

/**
 * Minty Competition Automation ACP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\minty\competitions\acp\main_module',
			'title'		=> 'ACP_COMPETITIONS_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_COMPETITIONS',
					'auth'	=> 'ext_minty/competitions && acl_a_new_minty_competitions',
					'cat'	=> array('ACP_COMPETITIONS_TITLE')
				),
			),
		);
	}
}
