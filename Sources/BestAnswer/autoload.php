<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

/**
 * This is done this way to register non permanent hooks (i.e not saved in the database)
 * for easier maintenance as there are many hooks involved.
 */
function sycho_best_answer_boot()
{
	add_integration_function('integrate_autoload', 'sycho_best_answer_autoload', false);
	add_integration_function('integrate_pre_load_theme', '\SychO\BestAnswer\BestAnswer::init', false);
}

/**
 * @hook integrate_autoload
 * @param array $classMap
 */
function sycho_best_answer_autoload(&$classMap)
{
	$classMap['SychO\\BestAnswer\\'] = 'BestAnswer/';
}
