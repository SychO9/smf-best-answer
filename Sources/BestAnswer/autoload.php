<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

add_integration_function('integrate_pre_load_theme', '\SychO\BestAnswer\BestAnswer::init', false);

/**
 * @hook integrate_autoload
 * @param array $classMap
 */
function sycho_best_answer_autoload(&$classMap)
{
	$classMap['SychO\\BestAnswer\\'] = 'BestAnswer/';
}
