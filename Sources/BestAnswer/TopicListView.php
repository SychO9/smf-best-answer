<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

namespace SychO\BestAnswer;

class TopicListView
{
	/**
	 * Registers the rest of the hooks
	 */
	public static function init()
	{
		if (isset($_GET['board']) && Settings::isEnabledForBoard((int) $_GET['board']))
		{
			add_integration_function('integrate_message_index', '\SychO\BestAnswer\TopicListView::selectBestMsg', false);
			add_integration_function('integrate_messageindex_buttons', '\SychO\BestAnswer\TopicListView::markSolvedTopics', false);
		}
		elseif (!empty($_GET['action']) && in_array($_GET['action'], array('unread', 'unreadreplies')))
		{
			add_integration_function('integrate_unread_list', '\SychO\BestAnswer\TopicListView::loadBestMsgs', false);
			add_integration_function('integrate_unread_list', '\SychO\BestAnswer\TopicListView::markSolvedTopics', false);
		}
	}

	/**
	 * @hook integrate_unread_list
	 */
	public static function loadBestMsgs()
	{
		global $context, $smcFunc;

		$topic_ids = array_map(function ($topic) {
			return $topic['id'];
		}, $context['topics']);

		$best_msgs = array();

		$request = $smcFunc['db_query']('', '
			SELECT id_topic, id_best_msg
			FROM {db_prefix}topics
			WHERE id_topic IN ({array_int:topics})',
			array(
				'topics' => $topic_ids,
			)
		);
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$best_msgs[$row['id_topic']] = $row['id_best_msg'];
		$smcFunc['db_free_result']($request);

		foreach ($context['topics'] as &$topic)
		{
			if (!empty($best_msgs[$topic['id']]))
				$topic['id_best_msg'] = $best_msgs[$topic['id']];
		}
	}

	/**
	 * @hook integrate_message_index
	 * @param array $message_index_selects
	 */
	public static function selectBestMsg(&$message_index_selects)
	{
		$message_index_selects += array('t.id_best_msg', 't.id_board');
	}

	/**
	 * @hook integrate_messageindex_buttons
	 */
	public static function markSolvedTopics()
	{
		global $context, $settings;

		foreach ($context['topics'] as $k => $topic)
		{
			$board_id = isset($topic['id_board']) ? $topic['id_board'] : (isset($topic['board'], $topic['board']['id']) ? $topic['board']['id'] : 0);

			if (empty($_GET['board']) && !empty($board_id) && !Settings::isEnabledForBoard($board_id))
				continue;

			if (!empty($topic['id_best_msg']))
			{
				$context['topics'][$k]['first_icon'] = $context['topics'][$k]['icon'] = $context['topics'][$k]['first_post']['icon'] = 'tick-circle';
				$context['topics'][$k]['first_post']['icon_url'] = $settings[$context['icon_sources'][$topic['first_post']['icon']]] . '/post/' . $context['topics'][$k]['first_post']['icon'] . '.png';
			}
		}
	}
}
