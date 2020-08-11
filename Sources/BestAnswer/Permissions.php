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
	 * Registers the rest of the hooks
	 */
	public static function init()
	{
		add_integration_function('integrate_load_permissions', '\SychO\BestAnswer\Permissions::addPermissions', false);
		add_integration_function('integrate_helpadmin', '\SychO\BestAnswer\Permissions::loadHelpText', false);
	}

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
