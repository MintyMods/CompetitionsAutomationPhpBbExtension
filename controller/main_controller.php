<?php
/**
 *
 * Minty Competition Automation. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\competitions\controller;
use PDO;
use SchedulerConnector;
use LogMaster;
/**
 * Minty Competition Automation main controller.
 */
class main_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\db\driver\factory */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	protected $log_file = "H:/Development/XAMPP/apps/phpbb/htdocs/ext/minty/competitions/minty_debug.log";

	protected $table_name = "phpbb_minty_competition_events"; // @todo

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	Language object
	 * 
	 */
	public function __construct(\phpbb\config\config $config, 
								\phpbb\controller\helper $helper, 
								\phpbb\template\template $template, 
								\phpbb\language\language $language, 
								\phpbb\auth\auth $auth, 
								\phpbb\user $user, 
								\phpbb\request\request $request, 
								\phpbb\db\driver\factory $dbal,
								\phpbb\log\log $log,
								$table_name
								) {
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->auth = $auth;
		$this->user = $user;
		$this->request = $request;
		$this->db = $dbal;
		$this->log		= $log;
		$this->table_name = $table_name;
	}

	
	/**
	 * Controller handler for route /demo/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($name) {
		$this->request->enable_super_globals();
		// var_dump($_SERVER["REQUEST_METHOD"]);
		if ($name == 'sponsors') {
			$this->getSponsors($name); 
		} else if ($name == 'templates') {
			$this->getSponsorTemplates($name); 
		} else if ($name == 'post_to_forum') {
			$this->getPostToForum($name); 			
		} else if ($name == 'archive_to_forum') {
			$this->getArchiveToForum($name); 			

		} else if ($name == 'automation') {
			 return $this->helper->render('@minty_competitions/competitions_body.html', $name);
		} else  {
			switch ($_SERVER["REQUEST_METHOD"]) {
				case "GET":
					$this->getData(); 	
				break;
				case "POST":
					$this->getData();
				break;
				default: 
					throw new Exception("Unexpected Method"); 
				break;
			}
		}

		$this->template->assign_vars(array(
			'PRIZE_FORUM_OPTIONS' => make_forum_select($this->config['minty_prize_forum'], false, false, false)
		));

		return $this->helper->render('@minty_competitions/competitions_body.html', $name);
	}

	
	function getPostToForum() {
		// <select id="minty_prize_forum" name="minty_prize_forum">{PRIZE_FORUM_OPTIONS}</select>


	}

	function getArchiveToForum() {

	}

	function getSponsorTemplates() {
		$sponsor_id = request_var('sponsor_id', 0);
		$sponsor_list = array();
		$sql = 'SELECT forum_id, forum_name FROM ' . FORUMS_TABLE . ' WHERE parent_id = ' . $sponsor_id;
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))	{
			$sponsor_list[] = array(
				'key'		=> (int) $row['forum_id'],
				'label'		=> $row['forum_name'],
			);
		}
		$this->db->sql_freeresult($result);
		$json_response = new \phpbb\json_response();
		$json_response->send($sponsor_list);
	}
	
	function getData() {
		require("./config.php"); 
		require("dhtmlx/scheduler_connector.php");
		$this->request->enable_super_globals();
		
		// var_dump($this->request); 
		// echo "Hello world!";
		$res = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpasswd);
		$connector = new SchedulerConnector($res);
		$connector->enable_log($this->log_file);
		LogMaster::log("Minty Competition Logging Enabled");
		$connector->render_table($this->table_name,"id","start_date,end_date,text,sponsor,status");
	}


	function getSponsors($name) {
		$parent_id = $this->config['minty_sponsor_forum'];
		$sponsor_list = array();
		$sql = 'SELECT forum_id, forum_name FROM ' . FORUMS_TABLE . ' WHERE parent_id = ' . $parent_id . '  and forum_name LIKE "%SPONSOR -%"';
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))	{
			$sponsor_list[] = array(
				'key'		=> (int) $row['forum_id'],
				'label'		=> $row['forum_name'],
			);
		}
		$this->db->sql_freeresult($result);
		$json_response = new \phpbb\json_response();
		$json_response->send($sponsor_list);
	}

}
