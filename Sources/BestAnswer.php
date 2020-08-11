<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz
 * @license MIT
 */

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
		require_once __DIR__.'/BestAnswerSettings.php';
		require_once __DIR__.'/BestAnswerPermissions.php';
		require_once __DIR__.'/BestAnswerTopicView.php';
		require_once __DIR__.'/BestAnswerTopicListView.php';

		BestAnswerSettings::init();
		BestAnswerPermissions::init();
		BestAnswerTopicView::init();
		BestAnswerTopicListView::init();
	}
}
