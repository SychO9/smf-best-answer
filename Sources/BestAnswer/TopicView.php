<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

namespace SychO\BestAnswer;

class TopicView
{
	/**
	 * @hook integrate_display_topic
	 * @param $topic_selects
	 * @param $topic_tables
	 * @param $topic_parameters
	 */
	public static function selectBestMsg(&$topic_selects, &$topic_tables, &$topic_parameters)
	{
		// Load the language and template files
		loadLanguage('BestAnswer');
		loadTemplate('BestAnswer');

		$topic_selects[] = 't.id_best_msg';
		$topic_selects[] = 't.id_board';
	}

	/**
	 * @hook integrate_prepare_display_context
	 * @param $output
	 * @param $message
	 * @param $counter
	 */
	public static function prepareMsgContext(&$output, &$message, $counter)
	{
		global $context, $settings;

		$output['css_class'] .= " class_msg_{$output['id']}";

		if ($output['id'] == $context['topicinfo']['id_first_msg'] && !empty($context['best_answer']))
		{
			$output['icon'] = 'tick-circle';
			$output['icon_url'] = $settings[$context['icon_sources'][$message['icon']]] . '/post/' . $output['icon'] . '.png';
		}
	}

	/**
	 * @hook integrate_display_message_list
	 * @param $messages
	 * @param $posters
	 */
	public static function loadBestAnswer(&$messages, &$posters)
	{
		global $smcFunc, $context, $scripturl;

		if (empty($context['topicinfo']['id_best_msg']) || !Settings::isEnabledForBoard($context['topicinfo']['id_board']))
			return;

		$request = $smcFunc['db_query']('', '
			SELECT id_msg, subject, body, id_member, likes, poster_time, modified_time
			FROM {db_prefix}messages
			WHERE id_msg = {int:id_best_msg}',
			array(
				'id_best_msg' => $context['topicinfo']['id_best_msg'],
			)
		);
		$context['best_answer'] = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);

		$context['best_answer']['body'] = parse_bbc($context['best_answer']['body']);
		$context['best_answer']['href'] = $scripturl.'?msg='.$context['best_answer']['id_msg'];

		// Make sure the poster is loaded later on
		if (!in_array($context['best_answer']['id_member'], $posters))
			$posters[] = $context['best_answer']['id_member'];
	}

	/**
	 * @hook integrate_load_theme
	 */
	public static function addMinimalCss()
	{
		addInlineCss('.windowbg.best_answer {background-color: #cfc}');
	}

	/**
	 * @hook integrate_buffer
	 * @param $buffer
	 * @return string
	 */
	public static function buffer($buffer)
	{
		global $context;

		if (!empty($context['best_answer']))
		{
			$buffer = self::markBestAnswer($buffer);
			$buffer = self::injectBestAnswer($buffer);
		}

		return $buffer;
	}

	/**
	 * @param string $buffer
	 * @return string
	 */
	public static function markBestAnswer($buffer)
	{
		global $context;

		if (empty($context['topicinfo']))
			return $buffer;

		$buffer = preg_replace(
			'/(<a href="[^"]+\?msg='.$context['topicinfo']['id_best_msg'].'" rel="nofollow" title[^>]+>(?:<strong>[^<]+<\/strong>|)[^<]+<\/a>)/m',
			"$1     ".template_best_answer_marker(),
			$buffer
		);

		return $buffer;
	}

	/**
	 * @param string $buffer
	 * @return string
	 */
	public static function injectBestAnswer($buffer)
	{
		global $context, $memberContext, $modSettings;

		if (strpos($buffer, '<hr class="post_separator">') === false)
			return $buffer;

		$context['best_answer']['member'] = &$memberContext[$context['best_answer']['id_member']];

		// Are likes enable?
		if (!empty($modSettings['enable_likes']))
			$context['best_answer']['likes_array'] = array(
				'count' => $context['best_answer']['likes'],
				'you' => in_array($context['best_answer']['id_msg'], $context['my_likes']),
				'can_like' => !$context['user']['is_guest'] && $context['best_answer']['id_member'] != $context['user']['id'] && !empty($context['can_like']),
			);

		$buffer = preg_replace(
			'/<div class="[^"]*class_msg_'.$context['topicinfo']['id_first_msg'].'[^0-9]+[^"]*">((?!<hr class="post_separator">).)*<hr class="post_separator">/ms',
			'$0 '.template_best_answer_single_post($context['best_answer']),
			$buffer
		);

		return $buffer;
	}
}
