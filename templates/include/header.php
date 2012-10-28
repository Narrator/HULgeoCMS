<?php

/**
 * header.php is a template file which displays the main header for the whole website
 *
 * Template files generally use inline PHP within a HTMl document to display the 
 * array/data passed from index.php (Based on action chosen and methods in the 
 * respective classes)
 * 
 * Copyright (C) 2012 Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 * 
 * HUL crowd-sourced mapping platform Version 1
 *
 * @package    TemplatePackage
 * @author     Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @copyright  Kaushik Subramaniam, 2012
 * @license    GNU General Public License, Version 3 <http://opensource.org/licenses/gpl-license.php>
 * @version    1
 * @since      0.1
 * @link       http://hyderabadurbanlab.com
 * 
 */
session_name('tvLogin');
// Starting the session

session_set_cookie_params(24*60*60);
// Making the cookie live for 1 day

define('INCLUDE_CHECK',true);

/*
 * Including functions.php which has a mailing function used by the registration form
 */
require 'header-elements/functions.php';

// Those two files can be included only if INCLUDE_CHECK is defined

if(isset($_SESSION['id']) && !isset($_COOKIE['tvRemember']) && !isset($_SESSION['rememberMe']))
{
	// If you are logged in, but you don't have the tvRemember cookie (browser restart)
	// and you have not checked the rememberMe checkbox:

	$_SESSION = array();
	session_destroy();
	
	// Destroy the session
}


if(isset($_GET['logoff']))
{
	$_SESSION = array(); //do something with the array
	session_destroy();
	header('Location: http://www.hyderabadurbanlab.com/index.php');
	exit;
}

if(isset($_POST['submit']))
{
	if($_POST['submit']=='Login'){
	// Checking whether the Login form has been submitted
	
	$err = array();
	// Will hold our errors
	
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		$_POST['username'] = pg_escape_string($_POST['username']);
		$_POST['password'] = pg_escape_string($_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		// Escaping all input data 
		
			$conn = new PDO(DB_DSN);
			$sql = "SELECT * FROM users WHERE usr=:user AND pass=:pass";
			$st = $conn->prepare ( $sql );
			$st->bindValue( ":user", $_POST['username'], PDO::PARAM_STR );
			$st->bindValue( ":pass", md5($_POST['password']), PDO::PARAM_STR );
			$st->execute();
			$row = $st->fetch();
			$conn = null;
			
			if($row['usr'])
			{
				$conn = new PDO(DB_DSN);
				$sql = "UPDATE users SET last_login=:lastlogin WHERE id=:id";
				$st = $conn->prepare ( $sql );
				$st->bindValue( ":id", $row['id'], PDO::PARAM_INT );
				$st->bindValue( ":lastlogin", 'NOW()', PDO::PARAM_INT );
				$st->execute();
				$conn = null;
				// If everything is OK login //KAUSHIK - add other session variables here
				
				$_SESSION['usr']=$row['usr'];
				$_SESSION['id'] = $row['id'];
				$_SESSION['access_level'] = $row['access_level'];
				$_SESSION['name'] = $row['name'];
				$_SESSION['phone'] = $row['phone'];
				$_SESSION['address'] = $row['address'];
				$_SESSION['last_login'] = $row['last_login'];
				$_SESSION['reg_date'] = $row['dt'];
				$_SESSION['email'] = $row['email'];
				
				// Store some data in the session
				
				setcookie('tvRemember',$_POST['rememberMe']);
			}
			else $err[]='Wrong username and/or password!';
	}
	
	if($err)
	$_SESSION['msg']['login-err'] = implode('<br />',$err);
	// Save the error messages in the session

	header('Location: '.$_SERVER['REQUEST_URI']);
	exit;
	}

}
else if(isset($_POST['submit']))
{
if($_POST['submit']=='Register')
{
	// If the Register form has been submitted
	
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>32)
	{
		$err[]='Your username must be between 3 and 32 characters!';
	}
	
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	{
		$err[]='Your username contains invalid characters!';
	}
	
	if(!checkEmail($_POST['email']))
	{
		$err[]='Your email is not valid!';
	}
	if(!$_POST['name'])
	{
		$err[]='Name is a required field!';
	}	
	if(!$_POST['phone'])
	{
		$err[]='Phone no. is a requied field!';
	}
	if(!$_POST['address'])
	{
		$err[]='Address is a required field!';
	}
	
	if(!count($err))
	{
		// If there are no errors
		
		$pass = substr(md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
		// Generate a random password
		
		$_POST['email'] = pg_escape_string($_POST['email']);
		$_POST['username'] = pg_escape_string($_POST['username']);
		// Escape the input data - KAUSHIK ^_
		
		$conn = new PDO(DB_DSN);
		$sql = "INSERT INTO users(usr,pass,email,regip,dt,access_level,name,phone,address) VALUES(:user, :pass, :email, :regip, :date, :accesslevel, :name, :phone, :address)";		
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":user", $_POST['username'], PDO::PARAM_STR );
		$st->bindValue( ":pass", md5($pass), PDO::PARAM_STR );
		$st->bindValue( ":email", $_POST['email'], PDO::PARAM_STR );
		$st->bindValue( ":name", $_POST['name'], PDO::PARAM_STR );
		$st->bindValue( ":phone", $_POST['phone'], PDO::PARAM_STR );
		$st->bindValue( ":address", $_POST['address'], PDO::PARAM_STR );
		$st->bindValue( ":regip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR );
		$st->bindValue( ":date", 'NOW()', PDO::PARAM_INT );
		$st->bindValue( ":accesslevel", 2, PDO::PARAM_INT );
		$st->execute();
		$count = $st->rowCount();
		if($count ==1)
		{
			send_mail(	'admin@hyderabadurbanlab.com',
						$_POST['email'],
						'Hyderabad Urban Lab - Your New Password',
						'Your password is: '.$pass);

			$_SESSION['msg']['reg-success']='We sent you an email with your new password!';
		}
		else $err[]='This username is already taken!';
		$conn= null;
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
	
	header('Location: '.$_SERVER['REQUEST_URI']);
	exit;
	}
}

$script = '';

if(isset($_SESSION['msg']))
{
	// The script below shows the sliding panel on page load
	
	$script = '
	<script type="text/javascript">
	
		$(function(){
		
			$("div#panel").show();
			$("#toggle a").toggle();
		});
	
	</script>';
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HUL Water portal - Beta Version</title>
    
    <link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="js/pkgs/slider/css/slide.css" media="screen" />
    
    <script type="text/javascript" src="http://www.hyderabadurbanlab.com/js/pkgs/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="js/pkgs/jquery-1.7.2.min.js"></script>
    
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="js/pkgs/slider/pngfix/supersleight-min.js"></script>
    <![endif]-->
    
    <script src="js/pkgs/slider/slide.js" type="text/javascript"></script>
	<?php if(isset($mapinclude)) { 
		echo $mapinclude; 
	}
	?>
	<?php echo $script; ?>
	
</head>
<body style="background:#25383C;">
<?php
/*
 * Including panel.php which produces the top panel
 */ 
	include 'header-elements/panel.php'
?>
<div id="pageContent-wrapper"> 
<div class="pageContent">

    <div id="main">
<?php 
/*
 * Including navigation.php which produces the top menu
 */

include 'header-elements/navigation.php'

?>
		<div class="container">
		<div class="main-container">
