<?php

/**
 * geomdata.php is a file is called when the XHR call is made from the js script
 * on registered.js after a geometry is drawn on the map
 *
 * IMPORTANT: This file needs to be deprecated. Call to this file is unnecessarily 
 * made.
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
 * @package    JSPackage
 * @author     Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @copyright  Kaushik Subramaniam, 2012
 * @license    GNU General Public License, Version 3 <http://opensource.org/licenses/gpl-license.php>
 * @version    1
 * @since      0.1
 * @link       http://hyderabadurbanlab.com
 * 
 */
	/*$send = '"';
	$send .= $_POST['categories'];
	$send .= '","';
	$send .= $_POST['tags'];
	if($_POST['videolink'])
	{
		$send .= '","';
		$send .= $_POST['videolink'];
	}
	$send .= '"';
	print $send;*/
	
	$send = array();
	$send[0] = $_POST['categories'];
	$send[1] = $_POST['tags'];
	if($_POST['videolink'])
		$send[2] = $_POST['videolink'];
	$return = $send[0].'<|>'.$send[1];
	if($send[2]) $return .= '<|>'.$send[2];
	
	print $return;
?>
