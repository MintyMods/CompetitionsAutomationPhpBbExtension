<?php
/**
 *
 * Minty Competition Automation. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\competitions\mcp;

/**
 * Minty Competition Automation MCP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\minty\competitions\mcp\main_module',
			'title'		=> 'MCP_COMPETITIONS_TITLE',
			'modes'		=> array(
				'front'	=> array(
					'title'	=> 'MCP_COMPETITIONS',
					'auth'	=> 'ext_minty/competitions && acl_m_new_minty_competitions',
					'cat'	=> array('MCP_COMPETITIONS_TITLE')
				),
			),
		);
	}
}
