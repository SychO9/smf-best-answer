<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

namespace SychO\BestAnswer;

class BestAnswer
{
	/**
	 * @var string
	 */
	const VERSION = '0.1.5';

	/**
	 * Registers the rest of the hooks
	 * --------------------------
	 * Easier this way than having to register the hooks in the database,
	 * This way the mod only relies on a couple of hooks to be registered in the database.
	 */
	public static function init()
	{
		self::initSettings();
		self::initPermissions();
		self::initTopicView();
		self::initTopicListView();

		add_integration_function('integrate_credits', '\SychO\BestAnswer\BestAnswer::addCredits', false);
	}

	/**
	 * Registers the hooks for the admin settings.
	 */
	public static function initSettings()
	{
		add_integration_function('integrate_general_mod_settings', '\SychO\BestAnswer\Settings::addSettings', false);
	}

	/**
	 * Registers the hooks for the permissions.
	 */
	public static function initPermissions()
	{
		add_integration_function('integrate_load_permissions', '\SychO\BestAnswer\Permissions::addPermissions', false);
		add_integration_function('integrate_helpadmin', '\SychO\BestAnswer\Permissions::loadHelpText', false);
	}

	/**
	 * Registers the hooks for the visual changes in a topic view.
	 */
	public static function initTopicView()
	{
		if (empty($_GET['topic']))
			return;

		add_integration_function('integrate_display_topic', '\SychO\BestAnswer\TopicView::selectBestMsg', false);
		add_integration_function('integrate_display_message_list', '\SychO\BestAnswer\TopicView::loadBestAnswer', false);
		add_integration_function('integrate_prepare_display_context', '\SychO\BestAnswer\TopicView::prepareMsgContext', false);
		add_integration_function('integrate_load_theme', '\SychO\BestAnswer\TopicView::addMinimalCss', false);
		add_integration_function('integrate_buffer', '\SychO\BestAnswer\TopicView::buffer', false);

		self::initActions();
	}

	/**
	 * Registers the hooks for the actions in a topic view.
	 */
	public static function initActions()
	{
		add_integration_function('integrate_display_message_list', '\SychO\BestAnswer\Actions::createPolicy', false);
		add_integration_function('integrate_prepare_display_context', '\SychO\BestAnswer\Actions::addPostQuickButton', false);
		add_integration_function('integrate_best_answer_quickbuttons', '\SychO\BestAnswer\Actions::addBestAnswerQuickButton', false);
		add_integration_function('integrate_display_message_list', '\SychO\BestAnswer\Actions::markBestAnswer', false);
		add_integration_function('integrate_display_message_list', '\SychO\BestAnswer\Actions::unmarkBestAnswer', false);
	}

	/**
	 * Registers the hooks for the visual changes in topic listings (messagesindex and unread lists)
	 */
	public static function initTopicListView()
	{
		if (!empty($_GET['action']) && in_array($_GET['action'], array('unread', 'unreadreplies')))
		{
			add_integration_function('integrate_unread_list', '\SychO\BestAnswer\TopicListView::loadBestMsgs', false);
			add_integration_function('integrate_unread_list', '\SychO\BestAnswer\TopicListView::markSolvedTopics', false);
		}
		elseif (isset($_GET['board']) && Settings::isEnabledForBoard((int) $_GET['board']))
		{
			add_integration_function('integrate_message_index', '\SychO\BestAnswer\TopicListView::selectBestMsg', false);
			add_integration_function('integrate_messageindex_buttons', '\SychO\BestAnswer\TopicListView::markSolvedTopics', false);
		}
	}

	/**
	 * @hook integrate_credits
	 */
	public static function addCredits()
	{
		$GLOBALS['context']['copyrights']['mods'][] = '<a href="https://github.com/SychO9/smf-best-answer">Best Answer v'.self::VERSION.'</a> by <a href="https://github.com/SychO9">SychO</a> &copy; 2020 | Licensed under <a href="http://en.wikipedia.org/wiki/MIT_License" target="_blank" rel="noopener">The MIT License (MIT)</a>';
	}
}
