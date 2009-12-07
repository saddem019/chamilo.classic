<?php
/* For licensing terms, see /chamilo_license.txt */
/**
* This script shows a compose area (wysiwyg editor if supported, otherwise
* a simple textarea) where the user can type a message.
* There are three modes
* - standard: type a message, select a user to send it to, press send
* - reply on message (when pressing reply when viewing a message)
* - send to specific user (when pressing send message in the who is online list)
*/
/*
==============================================================================
		INIT SECTION
==============================================================================
*/
// name of the language file that needs to be included
$language_file= array('messages','userInfo');
$cidReset=true;
require_once '../inc/global.inc.php';

api_block_anonymous_users();

if (api_get_setting('allow_message_tool')!='true'){
	api_not_allowed();
}

require_once api_get_path(LIBRARY_PATH).'text.lib.php';
require_once api_get_path(LIBRARY_PATH).'formvalidator/FormValidator.class.php';
require_once api_get_path(LIBRARY_PATH).'group_portal_manager.lib.php';
require_once api_get_path(LIBRARY_PATH).'message.lib.php';

$nameTools = api_xml_http_response_encode(get_lang('Messages'));
/*
-----------------------------------------------------------
	Constants and variables
-----------------------------------------------------------
*/
$htmlHeadXtra[]='
<script language="javascript">
function validate(form,list) {
	if(list.selectedIndex<0)
	{
    	alert("Please select someone to send the message to.")
    	return false
	}
	else
    	return true
}

</script>';
$htmlHeadXtra[] = '<script src="../inc/lib/javascript/jquery.js" type="text/javascript" language="javascript"></script>'; //jQuery

$htmlHeadXtra[] = '<script src="'.api_get_path(WEB_LIBRARY_PATH).'javascript/tag/jquery.fcbkcomplete.js" type="text/javascript" language="javascript"></script>';
$htmlHeadXtra[] = '<link href="'.api_get_path(WEB_LIBRARY_PATH).'javascript/tag/style.css" rel="stylesheet" type="text/css" />';

$htmlHeadXtra[] = '<script type="text/javascript">
$(document).ready(function (){		
		
      		$("#users").fcbkcomplete({
	            json_url: "'.api_get_path(WEB_PATH).'main/social/select_options.php?",
	            cache: false,
	            filter_case: true,
	            filter_hide: true,
				firstselected: true,
	            //onremove: "testme",
				//onselect: "testme",
	            filter_selected: true,
	            newel: true        
          	});	
});
		
var counter_image = 1;	
function remove_image_form(id_elem1) {
	var elem1 = document.getElementById(id_elem1);
	elem1.parentNode.removeChild(elem1);        
} 								
function add_image_form() {
    counter_image = counter_image + 1;														
	// Multiple filepaths for image form					
	var filepaths = document.getElementById("filepaths");		
	var elem1 = document.createElement("div");			
	elem1.setAttribute("id","filepath_"+counter_image);							
	filepaths.appendChild(elem1);	
	id_elem1 = "filepath_"+counter_image;		
	id_elem1 = "\'"+id_elem1+"\'";
	document.getElementById("filepath_"+counter_image).innerHTML = "<input type=\"file\" name=\"attach_"+counter_image+"\"  size=\"28\" />&nbsp;<input type=\"text\" name=\"legend[]\" size=\"28\" />&nbsp;<a href=\"javascript:remove_image_form("+id_elem1+")\"><img src=\"'.api_get_path(WEB_CODE_PATH).'img/delete.gif\"></a>";				
}		
		
</script>';

$nameTools = get_lang('ComposeMessage');

/*
==============================================================================
		FUNCTIONS
==============================================================================
*/

/**
* Shows the compose area + a list of users to select from.
*/
function show_compose_to_any ($user_id) {
	$online_user_list = MessageManager::get_online_user_list($user_id);
	$default['user_list'] = 0;
	$online_user_list=null;
	manage_form($default, $online_user_list);
}

function show_compose_reply_to_message ($message_id, $receiver_id) {
	global $charset;
	$table_message = Database::get_main_table(TABLE_MESSAGE);
	$query = "SELECT user_sender_id FROM $table_message WHERE user_receiver_id=".intval($receiver_id)." AND id='".intval($message_id)."';";
	$result = Database::query($query,__FILE__,__LINE__);
	$row = Database::fetch_array($result,'ASSOC');
	if (!isset($row['user_sender_id'])) {
		echo get_lang('InvalidMessageId');
		die();
	}
	
	$pre_html = '<div class="row">
				<div class="label">'.get_lang('SendMessageTo').'</div>
				<div class="formw">';
	$post = '</div></div>';									
	$multi_select = '<select id="users" name="users">
					 </select>';			
	echo $pre_html.'<strong>'.GetFullUserName($row['user_sender_id']).'</strong>'.$post;
	//echo get_lang('To').':&nbsp;<strong>'.	GetFullUserName($row['user_sender_id']).'</strong>';
	//$default['title'] = get_lang('EnterTitle');
	$default['users'] = array($row['user_sender_id']);
	manage_form($default);
}

function show_compose_to_user ($receiver_id) {
	global $charset;
	echo get_lang('To').':&nbsp;<strong>'.	GetFullUserName($receiver_id).'</strong>';
	$default['title'] = api_xml_http_response_encode(get_lang('EnterTitle'));	
	$default['users'] = array($receiver_id);
	manage_form($default);
}

function manage_form ($default, $select_from_user_list = null) {
	
	global $charset;
	$table_message = Database::get_main_table(TABLE_MESSAGE);
	
	$group_id = intval($_REQUEST['group_id']);
	$message_id = intval($_GET['message_id']);

	$form = new FormValidator('compose_message',null,null,null,array('enctype'=>'multipart/form-data'));	
	if (empty($group_id)) {		
		if (isset($select_from_user_list)) {
			$form->add_textfield('id_text_name', get_lang('SendMessageTo'),true,array('size' => 40,'id'=>'id_text_name','onkeyup'=>'send_request_and_search()','autocomplete'=>'off','style'=>'padding:0px'));
			$form->addRule('id_text_name', get_lang('ThisFieldIsRequired'), 'required');
			$form->addElement('html','<div id="id_div_search" style="padding:0px" class="message-select-box" >&nbsp;</div>');
			$form->addElement('hidden','user_list',0,array('id'=>'user_list'));
		} else {
			if (empty($default['users'])) { 				
				//the magic should be here		
				$pre_html = '<div class="row">
							<div class="label">'.get_lang('SendMessageTo').'</div>
							<div class="formw">';
				$post = '</div></div>';									
				$multi_select = '<select id="users" name="users">
	      						 </select>';			
				$form->addElement('html',$pre_html.$multi_select.$post );
		
			} else {
				$form->addElement('hidden','hidden_user',$default['users'][0],array('id'=>'hidden_user'));
			}
		}
	} else {		
		$group_info = GroupPortalManager::get_group_data($group_id);
		$form->addElement('html','<div class="row"><div class="label">'.get_lang('ToGroup').'</div><div class="formw">'.api_xml_http_response_encode($group_info['name']).'</div></div>');		
		$form->addElement('hidden','group_id',$group_id);
		$form->addElement('hidden','parent_id',$message_id);		
	}
	$form->add_textfield('title', get_lang('Title'),true ,array('size' => 75));
		
	//$form->add_html_editor('content', '', false, false, array('ToolbarSet' => 'Messages', 'Width' => '95%', 'Height' => '250'));
	$form->addElement('textarea','content', get_lang('Message'), array('cols' => 75,'rows'=>5));
	
	if (isset($_GET['re_id'])) {
		$form->addElement('hidden','re_id',Security::remove_XSS($_GET['re_id']));
		$form->addElement('hidden','save_form','save_form');
	}	
	if (empty($group_id)) {
		$form->addElement('html','<div class="row"><div class="label">'.get_lang('FilesAttachment').'</div><div class="formw">
				<span id="filepaths">
				<div id="filepath_1">
				<input type="file" name="attach_1"  size="28" />
				<input type="text" name="legend[]" size="28" />
				</div></span></div></div>');
		$form->addElement('html','<div class="row"><div class="formw"><a href="javascript://" onclick="return add_image_form()">'.get_lang('AddOneMoreFile').'</a>&nbsp;('.get_lang('MaximunFileSizeXMB').')</div></div>');
	}
		
	$form->addElement('style_submit_button','compose',api_xml_http_response_encode(get_lang('SendMessage')),'class="save"');
	$form->setRequiredNote('<span class="form_required">*</span> <small>'.get_lang('ThisFieldIsRequired').'</small>');	
	if (!empty($group_id) && !empty($message_id)) {
		$message_info = MessageManager::get_message_by_id($message_id);		
		$default['title']=get_lang('Re:').api_html_entity_decode($message_info['title'],ENT_QUOTES,$charset);		
	}		
	$form->setDefaults($default);
	if ($form->validate()) {
		$values 		= $default;		
		$user_list		= $values['users'];
		$file_comments	= $_POST['legend'];
		$title 			= $values['title'];
		$content 		= $values['content'];		
		$group_id		= $values['group_id'];
		$parent_id 		= $values['parent_id'];
		
		if (is_array($user_list) && count($user_list)> 0) {
			//all is well, send the message
			foreach ($user_list as $user) {				
				MessageManager::send_message($user, $title, $content, $_FILES, $file_comments, $group_id, $parent_id);
				MessageManager::display_success_message($user);	
			}			
		} 		
	} else {
		$form->display();
	}
}
/*
==============================================================================
		MAIN SECTION
==============================================================================
*/
if ($_GET['f']=='social') {
	$this_section = SECTION_SOCIAL;
	$interbreadcrumb[]= array ('url' => '#','name' => get_lang('Profile'));
	$interbreadcrumb[]= array ('url' => 'outbox.php','name' => get_lang('Inbox'));	
} else {
	$this_section = SECTION_MYPROFILE;
	$interbreadcrumb[]= array ('url' => '#','name' => get_lang('Profile'));
	$interbreadcrumb[]= array ('url' => 'outbox.php','name' => get_lang('Inbox'));
}

Display::display_header('');


$group_id = intval($_REQUEST['group_id']);


if ($group_id != 0) {
	echo '<div class=actions>';
	echo '<a href="'.api_get_path(WEB_PATH).'main/social/groups.php?id='.$group_id.'">'.Display::return_icon('back.png',api_xml_http_response_encode(get_lang('ComposeMessage'))).api_xml_http_response_encode(get_lang('BackToGroup')).'</a>';
	echo '<a href="'.api_get_path(WEB_PATH).'main/messages/new_message.php?group_id='.$group_id.'">'.Display::return_icon('message_new.png',api_xml_http_response_encode(get_lang('ComposeMessage'))).api_xml_http_response_encode(get_lang('ComposeMessage')).'</a>';
	echo '</div>';
} else {	
	if ($_GET['f']=='social') {
		require_once api_get_path(LIBRARY_PATH).'social.lib.php';
		SocialManager::show_social_menu();
		echo '<div class="actions-title">';
		echo get_lang('Messages');
		echo '</div>';
		$social_parameter = '?f=social';
	} else {
		echo '<div class=actions>';
		if (api_get_setting('allow_social_tool') == 'true' && api_get_setting('allow_message_tool') == 'true') {
			echo '<a href="'.api_get_path(WEB_PATH).'main/social/profile.php">'.Display::return_icon('shared_profile.png', get_lang('ViewSharedProfile')).'&nbsp;'.get_lang('ViewSharedProfile').'</a>';
		}
		if (api_get_setting('allow_message_tool') == 'true') {
			echo '<a href="'.api_get_path(WEB_PATH).'main/messages/inbox.php">'.Display::return_icon('inbox.png').' '.get_lang('Messages').'</a>';
		}	
		echo '<a href="'.api_get_path(WEB_PATH).'main/auth/profile.php?type=reduced">'.Display::return_icon('edit.gif', get_lang('EditNormalProfile')).'&nbsp;'.get_lang('EditNormalProfile').'</a>';

		echo '</div>';
	}
	
}

	


echo '<div id="inbox-wrapper" >';
	//LEFT COLUMN
	echo '<div id="inbox-menu" class="actions" >';	
		echo '<ul>';
			echo '<li><a href="'.api_get_path(WEB_PATH).'main/messages/inbox.php'.$social_parameter.'">'.Display::return_icon('inbox.png',get_lang('Inbox')).get_lang('Inbox').'</a>'.'</li>';
			echo '<li><a href="'.api_get_path(WEB_PATH).'main/messages/new_message.php'.$social_parameter.'">'.Display::return_icon('message_new.png',get_lang('ComposeMessage')).get_lang('ComposeMessage').'</a>'.'</li>';
			echo '<li><a href="'.api_get_path(WEB_PATH).'main/messages/outbox.php'.$social_parameter.'">'.Display::return_icon('outbox.png',get_lang('Outbox')).get_lang('Outbox').'</a>'.'</li>';
		echo '</ul>';	
	echo '</div>';

	echo '<div id="inbox">';
	
		//MAIN CONTENT
		
		if (!isset($_POST['compose'])) {					
			if(isset($_GET['re_id'])) {
				show_compose_reply_to_message($_GET['re_id'], api_get_user_id());
			} elseif(isset($_GET['send_to_user'])) {
				show_compose_to_user($_GET['send_to_user']);
			} else {
				show_compose_to_any($_user['user_id']);
		  	}		  	
		} else {				
				
			$restrict = false;			
			if (isset($_POST['users'])) {
				$restrict = true;
			} elseif (isset($_POST['group_id'])) {
				$restrict = true;
			} elseif(isset($_POST['hidden_user'])) {
				$restrict = true;
			}
			
			$default['title']	= $_POST['title'];
			$default['content'] = $_POST['content'];	
				
			// comes from a reply button
			if (isset($_GET['re_id'])) {										
				manage_form($default);
			} else {
				// post	
				if ($restrict) {						
					if (!isset($_POST['group_id'])) {
						$default['users']	 = $_POST['users'];
					} else {
						$default['group_id'] = $_POST['group_id'];
					}
					if (isset($_POST['hidden_user'])) {
						$default['users']	 = array($_POST['hidden_user']);
					}					
					manage_form($default);
				} else {
					Display::display_error_message(get_lang('ErrorSendingMessage'));
				}
			}
		}
	echo '</div>';

echo '</div>';

/*
==============================================================================
		FOOTER
==============================================================================
*/
Display::display_footer();

?>