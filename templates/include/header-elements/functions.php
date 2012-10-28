<?php

/**
 *
 * functions.php sends a mail to a person when they register on the website
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
 * @package    miscPackage
 * @author     Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @copyright  Kaushik Subramaniam, 2012
 * @license    GNU General Public License, Version 3 <http://opensource.org/licenses/gpl-license.php>
 * @version    1
 * @since      0.1
 * @link       http://hyderabadurbanlab.com
 * 
 */

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');


/*
 * checkEmail accepts the e-mail message and strips of all the alphanumerics, making it suitable for
 * sending using the mail() function in PHP
 *
 * @package	miscPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	string	$str	The e-mail message
 * @return	string	preg_matched E-mail message
 *
 */
function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}
/*
 * send_mail sends the e-mail message after registration
 *
 * @package	miscPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	string	$from	From e-mail address
 * @param	string	$to	To address
 * @param	string	$subject	Subject of E-mail message
 * @param	string	$body	Body of the message
 *
 */
function send_mail($from,$to,$subject,$body)
{
	$headers = '';
	$headers .= "From: $from\n";
	$headers .= "Reply-to: $from\n";
	$headers .= "Return-Path: $from\n";
	$headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Date: " . date('r', time()) . "\n";

	mail($to,$subject,$body,$headers);
}
?>
