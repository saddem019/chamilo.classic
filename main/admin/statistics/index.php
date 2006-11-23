<?php
// $Id: index.php 8216 2006-11-3 18:03:15 NushiFirefox $
/*
==============================================================================
	Dokeos - elearning and course management software

	Copyright (c) 2006 Bart Mollet <bart.mollet@hogent.be>

	For a full list of contributors, see "credits.txt".
	The full license can be read in "license.txt".

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	See the GNU General Public License for more details.

	Contact: Dokeos, 181 rue Royale, B-1000 Brussels, Belgium, info@dokeos.com
==============================================================================
*/
/**
==============================================================================
* This tool allows the use statistics
* @package dokeos.statistics
==============================================================================
*/


 Header("Cache-Control: must-revalidate");

 $offset = 60 * 3;
 $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
 Header($ExpStr);

$langFile=array('admin','tracking');
$cidReset = true;

include('../../inc/global.inc.php');
api_protect_admin_script();
require_once ('statistics.lib.php');

$interbreadcrumb[] = array ("url" => "../index.php", "name" => get_lang('AdministrationTools'));



$tool_name = get_lang('ToolName');
Display::display_header($tool_name);
api_display_tool_title($tool_name);

?>
<style type="text/css">
<!--
.statTable {
	border: 1px solid #999999;
	margin-bottom: 20px;
}
.statHeader{
	background-color: #eeeeee;
	border-bottom-width: 1px;
	border-bottom-style: dashed;
	border-bottom-color: #666666;
	font-weight: bold;
}
.statFooter{
	background-color: #eeeeee;
	border-top-width: 1px;
	border-top-style: dashed;
	border-top-color: #666666;
	font-weight: bold;
}
acronym{
	 cursor: help;
}
-->
</style>
<?php

$strCourse  = get_lang('Courses');
$strUsers = get_lang('Users');


$tools[$strCourse]['action=courses'] = get_lang('CountCours');
$tools[$strCourse]['action=tools'] = get_lang('PlatformToolAccess');
$tools[$strCourse]['action=courselastvisit'] = get_lang('Statistics_final_visit');


$tools[$strUsers]['action=users'] = get_lang('CountUsers');
$tools[$strUsers]['action=logins&amp;type=month'] = get_lang('Logins').' ('.get_lang('PeriodMonth').')';
$tools[$strUsers]['action=logins&amp;type=day'] = get_lang('Logins').' ('.get_lang('PeriodDay').')';
$tools[$strUsers]['action=logins&amp;type=hour'] = get_lang('Logins').' ('.get_lang('PeriodHour').')';
$tools[$strUsers]['action=recentlogins'] = get_lang('Statistics_total_active_users');
$tools[$strUsers]['action=pictures'] = get_lang('CountUsers').' ('.get_lang('UserPicture').')';



echo '<table><tr>';
foreach($tools as $section => $items)
{
	echo '<td valign="top">';
	echo '<b>'.$section.'</b>';
	echo '<ul>';
	foreach($items as $key => $value)
	{
			echo '<li><a href="index.php?'.$key.'">'.$value.'</a></li>';
	}
	echo '</ul>';
	echo '</td>';
}
echo '</tr></table>';
$faculties = statistics::get_faculties();
echo '<br/><br/>';
switch($_GET['action'])
{
	case 'courses':
		// total amount of courses
		foreach($faculties as $code => $name)
		{
			$courses[$name] = statistics::count_courses($code);
		}
		// curriculum-course for each department
		statistics::print_stats(get_lang('CountCours'),$courses);

		break;
	case 'users':
		// total amount of users
		statistics::print_stats(
				get_lang('NumberOfUsers'),
				array(
					get_lang('Teachers') => statistics::count_users(1,null,$_GET['count_invisible_courses']),
					get_lang('Students') => statistics::count_users(5,null,$_GET['count_invisible_courses'])
				)
			);
		foreach($faculties as $code => $name)
		{
			$name = str_replace(get_lang('Department'),"",$name);
			$teachers[$name] = statistics::count_users(1,$code,$_GET['count_invisible_courses']);
			$students[$name] = statistics::count_users(5,$code,$_GET['count_invisible_courses']);
		}
		// docents for each departerment
		statistics::print_stats(get_lang('Teachers'),$teachers);
		// students for each departement
		statistics::print_stats(get_lang('Students'),$students);

		break;
	case 'logins':
		statistics::print_login_stats($_GET['type']);
		break;
	case 'tools':
		statistics::print_tool_stats();
		break;
	case 'accessoldcourses':
		statistics::print_access_to_old_courses_stats();
		break;
	case 'courselastvisit':
		statistics::print_course_last_visit();
		break;
	case 'recentlogins':
		statistics::print_recent_login_stats();
		break;
	case 'pictures':
		statistics::print_user_pictures_stats();
		break;
	case 'curriculum_courses':
		statistics::print_curriculum_courses_stats_by_year();
		statistics::print_curriculum_courses_stats_by_category();
		break;
}

Display::display_footer();
?>