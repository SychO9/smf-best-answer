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
	const VERSION = '0.1.0';

	/**
	 * Registers the rest of the hooks
	 * --------------------------
	 * Easier this way than having to register the hooks in the database,
	 * This way the mod only relies on a couple of hooks to be registered in the database.
	 */
	public static function init()
	{
		Settings::init();
		Permissions::init();
		TopicView::init();
		TopicListView::init();

		add_integration_function('integrate_credits', '\SychO\BestAnswer\BestAnswer::addCredits', false);
	}

	public static function addCredits()
	{
		$GLOBALS['context']['copyrights']['mods'][] = '<a href="https://github.com/SychO9/smf-best-answer">Best Answer v'.self::VERSION.'</a> by <a href="https://github.com/SychO9">SychO</a> &copy; 2020 | Licensed under <a href="http://en.wikipedia.org/wiki/MIT_License" target="_blank" rel="noopener">The MIT License (MIT)</a>';
	}
}
