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
								\phpbb\db\driver\factory $dbal) {
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->auth = $auth;
		$this->user = $user;
		$this->request = $request;
		$this->db = $dbal;
	}

	
	/**
	 * Controller handler for route /demo/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($name) {

		//var_dump($name);
		if ($name == 'data') {
			return $this->getData(); 	
		}
		return $this->getTopics($name); 
	}
	
	
	
	function getData() {
		require("./config.php"); 
		require("dhtmlx/scheduler_connector.php");
		
		var_dump($this->request); 
		//echo "Hello world!";
		$res = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpasswd);
		$connector = new SchedulerConnector($res);
		$connector->render_table("phpbb_minty_competition_events","id","start_date,end_date,text");

		return $this->helper->render('@minty_competitions/test_body.html', 'test');
	}


	function getTopics($name) {

		$l_message = !$this->config['minty_competitions_goodbye'] ? 'COMPETITIONS_HELLO' : 'COMPETITIONS_GOODBYE';
		$this->template->assign_var('COMPETITIONS_MESSAGE', $this->language->lang($l_message, $name));

		$topics = 'SELECT * FROM ' . TOPICS_TABLE . ' WHERE forum_id = 2';
		$topics_result = $this->db->sql_query($topics);

		while( $topics_row = $this->db->sql_fetchrow($topics_result) ) {
			$topic_title       = $topics_row['topic_title'];
			$topic_last_post    = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $topics_row['forum_id'] . '&amp;t=' . $topics_row['topic_id'] . '&amp;p=' . $topics_row['topic_last_post_id']) . '#p' . $topics_row['topic_last_post_id'];
			$topic_last_author    = get_username_string('full', $topics_row['topic_last_poster_id'], $topics_row['topic_last_poster_name'], $topics_row['topic_last_poster_colour']);
			$topic_link       = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $topics_row['forum_id'] . '&amp;t=' . $topics_row['topic_id']);

			$this->template->assign_block_vars('topics', array(
			'TOPIC_TITLE'       => censor_text($topic_title),
			'TOPIC_LAST_POST'    => $topic_last_post,
			'TOPIC_LAST_AUTHOR' => $topic_last_author,
			'TOPIC_LINK'       => $topic_link,
			));

		}
		return $this->helper->render('@minty_competitions/competitions_body.html', $name);
	}

	

}
