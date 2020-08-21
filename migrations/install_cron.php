<?php
/**
 *
 * Minty Competition Automation. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\competitions\migrations;

class install_cron extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['competitions_cron_last_run']);
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('competitions_cron_last_run', 0)),
		);
	}
}
