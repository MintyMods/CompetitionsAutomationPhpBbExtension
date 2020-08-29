<?php

/**
 *
 * Minty Competition Automation. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\competitions\cron\task;

/**
 * Minty Competition Automation cron task.
 */
class auto_posting_cron_task extends \phpbb\cron\task\base
{
	protected $config;
	protected $user;
	protected $log;
	protected $db;
	protected $auth;
	protected $phpbb_root_path;
	protected $php_ext;
	protected $table_name = "phpbb_minty_competition_events";
	/**
	* Constructor.
	*
	* @param string $phpbb_root_path The root path
	* @param string $php_ext PHP file extension
	* @param \phpbb\config\config $config The config
	* @param \phpbb\user $user The phpBB user object
	* @param \phpbb\log\log $log The phpBB log system
	* @param \phpbb\db\driver\driver_interface $db The db connection
	*/
	public function __construct( \phpbb\config\config $config, \phpbb\user $user, \phpbb\log\log $log, \phpbb\db\driver\factory $dbal, \phpbb\auth\auth $auth, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->user = $user;
		$this->log = $log;
		$this->db = $dbal;
		$this->auth = $auth;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		include_once($phpbb_root_path . 'includes/functions_posting.' . $php_ext);
		$this->run();
	}
	
	/**
	 * Runs this cron task.
	 *
	 * @return void
	 */
	public function run() {
		$this->postScheduledCompetition();
		$this->config->set('auto_posting_cron_task_last_gc', time(), false);
	}

	public function getFormattedTimeNow() {
		return date("Y-m-d");
	}

	public function postScheduledCompetition() {
		$sql = 'SELECT * FROM ' . $this->table_name . ' WHERE status = "ACTIVE" AND start_date < "' . $this->getFormattedTimeNow() . '" AND end_date > "' . $this->getFormattedTimeNow() . '"';

		$this->info('Competition Auto Poster ' . $sql);
		$res = $this->db->sql_query($sql);

		while ( $row = $this->db->sql_fetchrow($res) ) {
			$this->info('ROW FOUND');
			$id = $row['id'];
			$text = $row['text'];
			$start_date = $row['start_date'];
			$end_date = $row['end_date'];
			$sponsor = $row['sponsor'];
			$template = $row['template'];
			$rules = $row['rules'];
			$prize = $row['prize'];
			$created_by = $row['created_by'];
			$won_by = $row['won_by'];
			$post_to = $row['post_to'];
			$status = $row['status'];
			$posted = $row['posted'];

			$this->postCompetition($text, $text, $created_by);
			$this->updateCompetitionStatus($id, 'POSTED');
		}		
	}

	public function updateCompetitionStatus($id, $status) {
		$sql = 'UPDATE ' . $this->table_name . ' SET status = "' . $status . '" WHERE id = ' . $this->db->sql_escape($id);
		$this->info('Update ' . $sql);
		$this->db->sql_query($sql);
	}

	public function postCompetition($subject, $text, $created_by) {
		$poll = $uid = $bitfield = $options = ''; 
		
		generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
		generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
		
		$data = array( 
				'forum_id'      => 2,
				'icon_id'       => false,
			
			'enable_bbcode'     => true,
			'enable_smilies'    => true,
			'enable_urls'       => true,
			'enable_sig'        => true,
		
			'message'       => $text,
			'message_md5'   => md5($text),
		
			'bbcode_bitfield'   => $bitfield,
			'bbcode_uid'        => $uid,
		
			'post_edit_locked'  => 0,
			'topic_title'       => $subject,
			'notify_set'        => false,
			'notify'            => false,
			'post_time'         => 0,
			'forum_name'        => '',
			'enable_indexing'   => true,
		);
		
		// $user->data['user_id'] = $created_by; 
		// $backup_ip = $user->ip;
		// $user->ip = '0.0.0.0';
		// $backup_auth = $this->auth;
		// $this->auth->acl($user->data);

		submit_post('post', $subject, '', POST_NORMAL, $poll, $data);
				
	}

	/**
	 * Returns whether this cron task can run, given current board configuration.
	 *
	 * For example, a cron task that prunes forums can only run when
	 * forum pruning is enabled.
	 *
	 * @return bool
	 */
	public function is_runnable()
	{
		return true;
	}
	
	/**
	 * Returns whether this cron task should run now, because enough time
	 * has passed since it was last run.
	 *
	 * @return bool
	 */
	public function should_run()
	{
		return true;
		//eturn ($this->config['auto_posting_cron_task_last_gc'] < time() - $this->config['auto_posting_cron_task_interval_gc']);
	}

	public function error($message)
	{
		$user_id = empty($this->user->data) ? ANONYMOUS : $this->user->data['user_id'];
		$user_ip = empty($this->user->ip) ? '' : $this->user->ip;
		$this->log->add('critical', $user_id, $user_ip, 'LOG_MINTY_ERROR', false, array($message));
	}
	
	public function info($message)
	{
		$user_id = empty($this->user->data) ? ANONYMOUS : $this->user->data['user_id'];
		$user_ip = empty($this->user->ip) ? '' : $this->user->ip;
		$this->log->add('admin', $user_id, $user_ip, 'LOG_MINTY_INFO', false, array($message));
	}

}
