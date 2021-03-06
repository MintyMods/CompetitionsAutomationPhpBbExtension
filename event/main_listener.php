<?php
/**
 *
 * Minty Competition Automation. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\competitions\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Minty Competition Automation Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup'							=> 'load_language_on_setup',
			'core.page_header'							=> 'add_page_header_link',
			'core.permissions'	=> 'add_permissions',
			'boardtools.cronstatus.modify_cron_task'   => 'add_my_cron_task',
			'boardtools.cronstatus.modify_cron_config'   => 'modify_cronlock',			
		);
	}

	/* @var \phpbb\language\language */
	protected $language;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/** @var string phpEx */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param string                    $php_ext    phpEx
	 */
	public function __construct(\phpbb\language\language $language, \phpbb\controller\helper $helper, \phpbb\template\template $template, $php_ext) {
		$this->language = $language;
		$this->helper   = $helper;
		$this->template = $template;
		$this->php_ext  = $php_ext;
	}


	public function add_my_cron_task($event) {
	   if ($event['task_name'] === 'minty.competitions.cron.task.auto_posting_cron_task') {
		  $last_task_date = $this->config['auto_posting_cron_task_last_gc'];
		  $task_interval = $this->config['auto_posting_cron_task_interval_gc'];
		  $event['task_date'] = $last_task_date;
		  $event['new_task_date'] = $last_task_date + $task_interval;
	   }
	}
 
	public function modify_cronlock($event) {
	   $last_task_date = $this->config['auto_posting_cron_task_last_gc'];
	   if (isset($event['last_task_date']))  {
		  if ($last_task_date >= $event['last_task_date']) {
			 $event['cronlock'] = 'auto_posting_cron_task';
			 $event['last_task_date'] = $last_task_date;
		  }
	   }
	   // Workaround for Cron Status 3.1.1.
	   // The maximum value for the date of the cronlock is not passed to the event object.
	   // We need to find it again.
	   else if ($last_task_date >= $this->phpbb_container->get('boardtools.cronstatus.listener')->maxValueInArray($event['rows'], 'config_value')) {
		  $event['cronlock'] = 'auto_posting_cron_task'; 
		  $rows = $event['rows'];
		  $rows[] = array(
			 "config_name"   => "auto_posting_cron_task_last_gc", // Any name ending with '_last_gc'.
			 "config_value"   => $last_task_date
		  );
		  $event['rows'] = $rows;
	   }
	}


	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'minty/competitions',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Add a link to the controller in the forum navbar
	 */
	public function add_page_header_link()
	{
		$this->template->assign_vars(array(
			'U_COMPETITIONS_PAGE'	=> $this->helper->route('minty_competitions_controller', array('name' => 'automation')),
		));
	}


	/**
	 * Add permissions to the ACP -> Permissions settings page
	 * This is where permissions are assigned language keys and
	 * categories (where they will appear in the Permissions table):
	 * actions|content|forums|misc|permissions|pm|polls|post
	 * post_actions|posting|profile|settings|topic_actions|user_group
	 *
	 * Developers note: To control access to ACP, MCP and UCP modules, you
	 * must assign your permissions in your module_info.php file. For example,
	 * to allow only users with the a_new_minty_competitions permission
	 * access to your ACP module, you would set this in your acp/main_info.php:
	 *    'auth' => 'ext_minty/competitions && acl_a_new_minty_competitions'
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function add_permissions($event)
	{
		$permissions = $event['permissions'];

		$permissions['a_new_minty_competitions'] = array('lang' => 'ACL_A_NEW_MINTY_COMPETITIONS', 'cat' => 'misc');
		$permissions['m_new_minty_competitions'] = array('lang' => 'ACL_M_NEW_MINTY_COMPETITIONS', 'cat' => 'post_actions');
		$permissions['u_new_minty_competitions'] = array('lang' => 'ACL_U_NEW_MINTY_COMPETITIONS', 'cat' => 'post');

		$event['permissions'] = $permissions;
	}
}
