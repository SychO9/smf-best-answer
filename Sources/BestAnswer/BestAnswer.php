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
	}
}
