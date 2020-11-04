<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

namespace SychO\BestAnswer;

class Actions
{
	/**
	 * @var Policy
	 */
	protected static $policy;

	/**
	 * @hook integrate_display_topic
	 */
	public static function createPolicy()
	{
		self::$policy = new Policy($GLOBALS['context']['topicinfo']);
	}

	/**
	 * @hook integrate_prepare_display_context
	 * @param array $output
	 * @param array $message
	 * @param int $counter
	 */
	public static function addPostQuickButton(&$output, &$message, $counter)
	{
		global $txt, $scripturl, $context;

		if ($output['id'] === $context['topicinfo']['id_first_msg'])
			return;

		$button = array(
			'show' => self::$policy->canMarkBestAnswer()
		);

		if ($context['topicinfo']['id_best_msg'] !== $output['id'])
			$button += array(
				'label' => $txt['mark_as_best'],
				'href' => "$scripturl?topic={$context['current_topic']};best_answer={$output['id']};{$context['session_var']}={$context['session_id']}",
				'icon' => 'valid',
			);
		else
			$button += array(
				'label' => $txt['unmark_as_best'],
				'href' => "$scripturl?topic={$context['current_topic']};unmark_best_answer;{$context['session_var']}={$context['session_id']}",
				'icon' => 'remove_button',
			);

		$output['quickbuttons']['more']['mark_as_best'] = $button;
	}

	/**
	 * @hook integrate_best_answer_quickbuttons
	 * @param array $list_items
	 */
	public static function addBestAnswerQuickButton(&$list_items)
	{
		global $txt, $context, $scripturl;

		$list_items['mark_as_best'] = array(
			'label' => $txt['unmark_as_best'],
			'href' => "$scripturl?topic={$context['current_topic']};unmark_best_answer;{$context['session_var']}={$context['session_id']}",
			'icon' => 'remove_button',
			'show' => self::$policy->canMarkBestAnswer(),
		);
	}

	/**
	 * Marks a message as the best answer of a topic,
	 * Then redirects to the topic.
	 *
	 * @hook integrate_display_topic
	 */
	public static function markBestAnswer()
	{
		global $smcFunc, $context;

		if (empty($_GET['best_answer']) || $_GET['best_answer'] == $context['topicinfo']['id_first_msg'])
			return;

		checkSession('get');

		self::$policy->assertCanMarkBestAnswer();

		// Make sure the msg id supplied actually belongs to this topic
		$request = $smcFunc['db_query']('', '
			SELECT id_msg, id_member
			FROM {db_prefix}messages
			WHERE id_topic = {int:current_topic}
				AND id_msg = {int:best_answer}',
			array(
				'best_answer' => (int) $_GET['best_answer'],
				'current_topic' => (int) $context['current_topic'],
			)
		);
		list($id_msg, $id_member) = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);

		// So you're trying to do some sneaky things huh.. well not today.
		if (empty($id_msg))
			return;

		$smcFunc['db_query']('' , '
			UPDATE {db_prefix}topics
			SET id_best_msg = {int:best_answer}
			WHERE id_topic = {int:current_topic}',
			array(
				'best_answer' => (int) $_GET['best_answer'],
				'current_topic' => (int) $context['current_topic'],
			)
		);

		// Integrate with other mods
		call_integration_hook('integrate_sycho_best_answer', array($id_msg, $id_member));

		redirectexit("topic={$context['current_topic']}");
	}

	/**
	 * Unmarks the best answer of a topic
	 *
	 * @hook integrate_display_topic
	 */
	public static function unmarkBestAnswer()
	{
		global $context, $smcFunc;

		if (!isset($_GET['unmark_best_answer']))
			return;

		checkSession('get');

		self::$policy->assertCanMarkBestAnswer();

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}topics
			SET id_best_msg = {int:best_answer}
			WHERE id_topic = {int:current_topic}',
			array(
				'best_answer' => 0,
				'current_topic' => (int) $context['current_topic'],
			)
		);

		redirectexit("topic={$context['current_topic']}");
	}
}
