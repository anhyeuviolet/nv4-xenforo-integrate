<?php

/**
 * @Project NUKEVIET 3.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 tdweb.vn All rights reserved
 * @Createdate  2, 9, 2014 20:21
 */
if (! defined ( 'NV_IS_MOD_USER' ))
	die ( 'Stop!!!' );

define ( 'IN_XENFORO', true );

if (file_exists ( NV_ROOTDIR . '/' . DIR_FORUM . '/library/XenForo/Autoloader.php' )) {
	$error = "";
	
	global $db, $config, $client_info, $nv_Request;
	
	if (empty ( $nv_username )) {
		$nv_username = $nv_Request->get_title ( 'nv_login', 'post', '' );
	}
	if (empty ( $nv_password )) {
		$nv_password = $nv_Request->get_title ( 'nv_password', 'post', '' );
	}
	if (empty ( $nv_redirect )) {
		$nv_redirect = $nv_Request->get_title ( 'nv_redirect', 'post,get', '' );
	}
	
	$xenforo_root_path = NV_ROOTDIR . '/' . DIR_FORUM . '/';
	
	require ($xenforo_root_path . 'library/XenForo/Autoloader.php');
	
	XenForo_Autoloader::getInstance ()->setupAutoloader ( $xenforo_root_path . '/library' );
	XenForo_Application::initialize ( $xenforo_root_path . '/library', $xenforo_root_path );
	XenForo_Session::startPublicSession ();
	XenForo_Application::disablePhpErrorHandler ();
	XenForo_Application::setDebugMode ( true );
	
	require ($xenforo_root_path . 'nukeviet/function.php');
	$data ['login'] = $nv_username;
	$data ['password'] = $nv_password;
	$data ['remember'] = "1";
	$data ['ip'] = $client_info ['ip'];
	$data ['getUserParams'] = $client_info;
	$session = new Session ();
	$validate = $session->login ( $data );
	$row = XenForo_Visitor::getInstance ();
	
	if ($row ['user_state'] == 'valid' and is_null ( $validate )) {
		$user_info = array ();
		$password_crypt = $crypt->hash ( $nv_password );
		
		$user_info ['active'] = 1;
		if ($row ['is_banned'] == 1) {
			$user_info ['active'] = 0;
		}
		
		$user_info ['userid'] = intval ( $row ['user_id'] );
		$user_info ['username'] = $row ['username'];
		$user_info ['email'] = $row ['email'];
		$user_info ['full_name'] = $row ['username'];
		$user_info ['birthday'] = 0;
		$user_info ['regdate'] = intval ( $row ['register_date'] );
		$user_info ['sig'] = $row ['signature'];
		$user_info ['view_mail'] = intval ( $row ['email_on_conversation'] );
		
		$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=" . intval ( $user_info ['userid'] );
		$numrows = $db->query ( $sql )->rowCount ();
		if ($numrows > 0) {
			$sql = "UPDATE " . NV_USERS_GLOBALTABLE . " SET 
                username = " . $db->quote ( $user_info ['username'] ) . ", 
                md5username = " . $db->quote ( md5 ( $user_info ['username'] ) ) . ", 
                password = " . $db->quote ( $password_crypt ) . ", 
                email = " . $db->quote ( $user_info ['email'] ) . ", 
                first_name = " . $db->quote ( $user_info ['full_name'] ) . ", 
                birthday=" . $user_info ['birthday'] . ", 
				sig=" . $db->quote ( $user_info ['sig'] ) . ", 
                regdate=" . $user_info ['regdate'] . ", 
                view_mail=" . $user_info ['view_mail'] . ",
                active=" . $user_info ['active'] . ",
                last_login=" . NV_CURRENTTIME . ", 
                last_ip=" . $db->quote ( $client_info ['ip'] ) . ", 
                last_agent=" . $db->quote ( NV_USER_AGENT ) . "
                WHERE userid=" . $user_info ['userid'];
		} else {
			$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " 
                (userid, username, md5username, password, email, first_name, gender, photo, birthday, sig,
                regdate, question, answer, passlostkey, 
                view_mail, remember, in_groups, active, checknum, last_login, last_ip, last_agent, last_openid) VALUES 
                (
					" . intval ( $user_info ['userid'] ) . ", 
					" . $db->quote ( $user_info ['username'] ) . ", 
					" . $db->quote ( md5 ( $user_info ['username'] ) ) . ", 
					" . $db->quote ( $password_crypt ) . ", 
					" . $db->quote ( $user_info ['email'] ) . ", 
					" . $db->quote ( $user_info ['full_name'] ) . ", 
					'', 
					'', 
					" . $user_info ['birthday'] . ", 
					" . $db->quote ( $user_info ['sig'] ) . ", 
					" . $user_info ['regdate'] . ", 
					'', '', '', 
					" . $user_info ['view_mail'] . ",
					0, 
					'', 
					" . $user_info ['active'] . ",
					'', 
					" . NV_CURRENTTIME . ", 
					" . $db->quote ( $client_info ['ip'] ) . ", 
					" . $db->quote ( NV_USER_AGENT ) . ", 
					'' 
                )";
		}
		if ($db->query ( $sql )) {
			$error = "";
		} else {
			$error = $lang_module ['error_update_users_info'];
		}
	} elseif ($row ['user_state'] == 'email_confirm') {
		$error = $lang_global ['loginincorrect'];
	} elseif ($validate->getPhraseName () == 'incorrect_password') {
		$error = $lang_global ['loginincorrect'];
	} elseif ($validate->getPhraseName () == 'requested_user_x_not_found') {
		$error = $lang_global ['loginincorrect'];
	} else {
		$error = $lang_global ['loginincorrect'];
	}
	if (empty ( $error )) {
		$user_info ['last_ip'] = $client_info ['ip'];
		$user_info ['last_agent'] = NV_USER_AGENT;
		$user_info ['last_openid'] = "";
		$user_info ['last_login'] = NV_CURRENTTIME;
		$remember = 1;
		$checknum = nv_genpass ( 10 );
		$checknum = $crypt->hash ( $checknum );
		$user = array ( //
				'userid' => $user_info ['userid'], //
				'checknum' => $checknum, //
				'current_agent' => NV_USER_AGENT, //
				'last_agent' => $user_info ['last_agent'], //
				'current_ip' => $client_info ['ip'], //
				'last_ip' => $user_info ['last_ip'], //
				'current_login' => NV_CURRENTTIME, //
				'last_login' => intval ( $user_info ['last_login'] ), //
				'last_openid' => $user_info ['last_openid'], //
				'current_openid' => '' 
		);
		
		$user = nv_base64_encode ( serialize ( $user ) );
		$opid = "";
		$db->query ( "UPDATE " . NV_USERS_GLOBALTABLE . " SET 
		checknum = " . $db->quote ( $checknum ) . ", 
		last_login = " . NV_CURRENTTIME . ", 
		last_ip = " . $db->quote ( $client_info ['ip'] ) . ", 
		last_agent = " . $db->quote ( NV_USER_AGENT ) . ", 
		last_openid = " . $db->quote ( $opid ) . ", 
		remember = " . $remember . " 
		WHERE userid=" . $user_info ['userid'] );
		
		$live_cookie_time = ($remember) ? NV_LIVE_COOKIE_TIME : 0;
		
		$nv_Request->set_Cookie ( 'nvloginhash', $user, $live_cookie_time );
		$nv_Request->set_Session ( 'nvloginhash', $user );
	}
} else {
	trigger_error ( "Error no forum Xenforo", 256 );
}
