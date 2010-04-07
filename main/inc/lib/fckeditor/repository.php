<?php
/**
 *	Chamilo LMS
 *
 *	For a full list of contributors, see "credits.txt".
 *	The full license can be read in "license.txt".
 *
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *
 *	See the GNU General Public License for more details.
 */

/**
 * Aditional system config settings for document repositories, the Chamilo LMS
 * @author Juan Carlos Raña
 * @since 31/December/2008
 */

require_once api_get_path(LIBRARY_PATH).'/fileUpload.lib.php';

$permissions_for_new_directories = api_get_permissions_for_new_directories();
$permissions_for_new_files = api_get_permissions_for_new_files();

if (!empty($_course['path'])) {
	require_once api_get_path(LIBRARY_PATH).'/document.lib.php';
	require_once api_get_path(LIBRARY_PATH).'/groupmanager.lib.php';

    // Get the Chamilo session properties. Before ajaximagemanager!!!
    $to_group_id = !empty($_SESSION['_gid']) ? $_SESSION['_gid'] : 0 ;
	$group_properties = GroupManager::get_group_properties($_SESSION['_gid']);
	$is_user_in_group = GroupManager::is_user_in_group($_user['user_id'],$_SESSION['_gid']);
}

$user_folder = api_get_path(SYS_PATH).'main/upload/users/'.api_get_user_id().'/my_files/';

// Sanity checks for Chamilo.

// Creation of a user owned folder if it does not exist.
if (!file_exists($user_folder)) {
	// A recursive call of mkdir function.
	@mkdir($user_folder, $permissions_for_new_directories, true);
}

// Creation of repository used by paltform administrators if it does not exist.
if (api_is_platform_admin()) {
	$homepage_folder = api_get_path(SYS_PATH).'home/default_platform_document/';
	if (!file_exists($homepage_folder)) {
		@mkdir($homepage_folder, $permissions_for_new_directories);
	}
}
$current_session_id = api_get_session_id();
// Creation in the course document repository of a shared folder if it does not exist.
if (api_is_in_course()) {
	$course_shared_folder = api_get_path(SYS_PATH).'courses/'.$_course['path'].'/document/shared_folder/';
	if (!file_exists($course_shared_folder)) {
		@mkdir($course_shared_folder, $permissions_for_new_directories);
		$doc_id = add_document($_course, '/shared_folder', 'folder', 0, 'shared_folder');
		api_item_property_update($_course, TOOL_DOCUMENT, $doc_id, 'FolderCreated', api_get_user_id(),null,null,null,null,$current_session_id);
		api_item_property_update($_course, TOOL_DOCUMENT, $doc_id, 'invisible', api_get_user_id(),null,null,null,null,$current_session_id);
	}

	// Added by Ivan Tcholakov.
	// When the current user is inside a course, his/her own hidden folder is created (if it does not exist) under shared_folder.
	if (!file_exists($course_shared_folder.'sf_user_'.api_get_user_id())) {
		//@todo call the create_unexisting_directory function and replace this code Julio Montoya
		$new_user_dir = api_get_path(SYS_PATH).'courses/'.$_course['path'].'/document/shared_folder/sf_user_'.api_get_user_id().'/';
		@mkdir($new_user_dir, $permissions_for_new_directories);
		$doc_id = add_document($_course, '/shared_folder/sf_user_'.api_get_user_id(), 'folder', 0, api_get_person_name($_user['firstName'], $_user['lastName']));
		api_item_property_update($_course, TOOL_DOCUMENT, $doc_id, 'FolderCreated', api_get_user_id(),null,null,null,null,$current_session_id);
		api_item_property_update($_course, TOOL_DOCUMENT, $doc_id, 'invisible', api_get_user_id(),null,null,null,null,$current_session_id);
	}
}
