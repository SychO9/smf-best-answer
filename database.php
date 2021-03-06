<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

// If SSI.php is in the same place as this file, and SMF isn't defined, this is being run standalone.
if (file_exists(__DIR__.'/SSI.php') && !defined('SMF'))
	require_once(__DIR__.'/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

if (version_compare(PHP_VERSION, '7.2.99', '<='))
	die('This mod is not compatible with PHP versions lower than or equal to 7.2');

db_extend('packages');

// Add the 'id_best_msg' column to smf_topics table
$smcFunc['db_add_column']('{db_prefix}topics', array(
	'name' 		=> 'id_best_msg',
	'type' 		=> 'int',
	'size' 		=> 10,
	'null' 		=> false,
	'default' 	=> 0,
	'unsigned' 	=> true,
), '', 'update');

// Add an index for better performance
$smcFunc['db_add_index'] ('{db_prefix}topics', array(
	'type' 		=> 'unique',
	'name' 		=> 'idx_best_message',
	'columns' 	=> array('id_best_msg', 'id_topic'),
), '', 'update');

// Insert default permission
$smcFunc['db_insert']('ignore',
	'{db_prefix}board_permissions',
	array('permission' => 'string', 'id_group' => 'int', 'id_profile' => 'int', 'add_deny' => 'int'),
	array(
		array('mark_best_answer_own', 0, 1, 1)
	),
	array('permission', 'id_group', 'id_profile')
);
