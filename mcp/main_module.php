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
 * Minty Competition Automation MCP module.
 */
class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 * Main MCP module
	 *
	 * @param int    $id   The module ID
	 * @param string $mode The module mode (for example: manage or settings)
	 * @throws \Exception
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		/** @var \minty\competitions\controller\mcp_controller $mcp_controller */
		$mcp_controller = $phpbb_container->get('minty.competitions.controller.mcp');

		/** @var \phpbb\language\language $language */
		$language = $phpbb_container->get('language');

		// Load a template for our MCP page
		$this->tpl_name = 'mcp_competitions_body';

		// Set the page title for our MCP page
		$this->page_title = $language->lang('MCP_COMPETITIONS_TITLE');

		// Make the $u_action url available in our MCP controller
		$mcp_controller->set_page_url($this->u_action);

		// Load the display options handle in our MCP controller
		$mcp_controller->display_options();
	}
}
