<?php

/**
 * @package BestAnswer
 * @author Sami "SychO" Mazouz (sychocouldy@gmail.com)
 * @license MIT
 */

namespace SychO\BestAnswer;

class Permissions
{
	/**
	 * @hook integrate_load_permissions
	 * @param array $permissionGroups
	 * @param array $permissionList
	 * @param array $leftPermissionGroups
	 * @param array $hiddenPermissions
	 * @param array $relabelPermissions
	 */
	public static function addPermissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
	{
		loadLanguage('BestAnswer');

		$permissionList['board']['mark_best_answer'] = array(true, 'topic');
	}

	/**
	 * @hook integrate_helpadmin
	 */
	public static function loadHelpText()
	{
		loadLanguage('BestAnswer');
	}
}
