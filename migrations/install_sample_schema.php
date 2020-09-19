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

class install_sample_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'users', 'minty_competitions');
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	/**
	 * Update database schema.
	 *
	 * https://area51.phpbb.com/docs/dev/3.2.x/migrations/schema_changes.html
	 *	add_tables: Add tables
	 *	drop_tables: Drop tables
	 *	add_columns: Add columns to a table
	 *	drop_columns: Removing/Dropping columns
	 *	change_columns: Column changes (only type, not name)
	 *	add_primary_keys: adding primary keys
	 *	add_unique_index: adding an unique index
	 *	add_index: adding an index (can be column:index_size if you need to provide size)
	 *	drop_keys: Dropping keys
	 *
	 * This sample migration adds a new column to the users table.
	 * It also adds an example of a new table that can hold new data.
	 *
	 * @return array Array of schema changes
	 */
	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'minty_competition_events'	=> array(
					'COLUMNS'			=> array(
						'id'			=> array('UINT', null, 'auto_increment'),
						'text'			=> array('MTEXT', ''),
						'start_date'	=> array('VCHAR:30', ''),
						'end_date'		=> array('VCHAR:30', ''),
						'sponsor'		=> array('UINT',0),
						'template'		=> array('UINT',0),
						'rules'			=> array('UINT',0),
						'prize'			=> array('UINT',0),
						'created_by'	=> array('UINT',0),
						'won_by'		=> array('UINT',0),
						'post_to'		=> array('UINT',0),
						'status'		=> array('VCHAR:20', ''),
						'posted'    	=> array('BOOL', 0)
					),
					'PRIMARY_KEY'	=> 'id',
				),
			),
			'add_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'minty_competitions'				=> array('UINT', 0),
				),
			),
		);

	}

	/**
	 * Revert database schema changes. This method is almost always required
	 * to revert the changes made above by update_schema.
	 *
	 * https://area51.phpbb.com/docs/dev/3.2.x/migrations/schema_changes.html
	 *	add_tables: Add tables
	 *	drop_tables: Drop tables
	 *	add_columns: Add columns to a table
	 *	drop_columns: Removing/Dropping columns
	 *	change_columns: Column changes (only type, not name)
	 *	add_primary_keys: adding primary keys
	 *	add_unique_index: adding an unique index
	 *	add_index: adding an index (can be column:index_size if you need to provide size)
	 *	drop_keys: Dropping keys
	 *
	 * This sample migration removes the column that was added the users table in update_schema.
	 * It also removes the table that was added in update_schema.
	 *
	 * @return array Array of schema changes
	 */
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'minty_competitions',
				),
			),
			'drop_tables'		=> array(
				$this->table_prefix . 'minty_competition_events',
			),
		);
	}
}
