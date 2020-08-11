<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz
 * @license MIT
 */

class BestAnswerSettings
{
	/**
	 * Registers the rest of the hooks
	 */
	public static function init()
	{
		add_integration_function('integrate_general_mod_settings', 'BestAnswerSettings::addSettings', false);
	}

	/**
	 * @hook integrate_general_mod_settings
	 * @param $config_vars
	 */
	public static function addSettings(&$config_vars)
	{
		loadLanguage('BestAnswer');

		if (!empty($config_vars))
			$config_vars[] = '';

		$config_vars[] = array('boards', 'best_answer_boards');
	}

	/**
	 * Checks if Best Answer is enabled for a specific board
	 *
	 * @param int|string $id_board
	 * @return bool
	 */
	public static function isEnabledForBoard($id_board)
	{
		return in_array($id_board, explode(',', $GLOBALS['modSettings']['best_answer_boards']));
	}
}
