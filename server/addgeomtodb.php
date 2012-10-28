<?php

/**
 * addgeomtodb.php is a file is called when the XHR call is made from the js script
 * on registered.js after a geometry is drawn on the map
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

	$host = "host=";
	$port = "port=";
	$dbname = "dbname=";
	$username = "user=";
	$password = "password=";
	
	$full = $host." ".$port." ".$dbname." ".$username." ".$password;

	$connection = pg_connect($full);

	if(!$connection)
	{
		pg_close($connection);

	}
	
	$n = $_GET['n'];
	$type = $_GET['type'];
	$category = $_GET['cat'];
	$tag = $_GET['tag'];
	if($_GET['video']) $video = $_GET['video'];
	if($_GET['photo']) $photo = $_GET['photo'];
	$lats = array();
	$lngs = array();
	
	for($i=0;$i<$n;$i++)
	{
		$latstring = "lat" . $i;
		$lngstring = "lng" . $i;
		$lats[$i] = $_GET[$latstring]; 
		$lngs[$i] = $_GET[$lngstring];
	}

	$query = "INSERT INTO ";
	
	switch($type) {
		case "point": $query .="ogrgeojsonpoint (wkb_geometry," . $category;
						if($photo) { 
							$query .= ",photo";
						}
						
						if($video) {
							$query .= ",video";					
						}
					  $query .= ") VALUES (ST_GeomFromText('POINT("; break;
					  
		case "polyline": $query .="ogrgeojson1 (wkb_geometry," . $category;  
						if($photo) { 
							$query .= ",photo";
						}
						
						if($video) {
							$query .= ",video";					
						}
					     $query .= ") VALUES (ST_GeomFromText('LINESTRING("; break;
		case "polygon": $query .="ogrgeojsonpoly (wkb_geometry," . $category; 
						if($photo) { 
							$query .= ",photo";
						}
						
						if($video) {
							$query .= ",video";					
						}
						$query .= ") VALUES (ST_GeomFromText('POLYGON(("; break;
	}
	
	for($i=0; $i<$n; $i++)
	{
		$query .= $lngs[$i];
		$query .= " ";
		$query .= $lats[$i];
		
		if($i<($n-1))
		$query .= ", ";
	}
	if($type == "polygon") {
		$query .= ", ";
		$query .= $lngs[0];
		$query .= " ";
		$query .= $lats[0];
		$query .="))',4326),'" . $tag . "'";
	}
	else $query .=")',4326),'" . $tag . "'"; 
	
	if($photo) { 
		$query .= ",'";
		$query .= $photo;
		$query .= "'";
	}
	
	if($video) {
		$query .= ",'";
		$query .= $video;
		$query .= "'";
	}
	
	$query .= ")";
	
	$result1 = pg_query($connection, $query);

	if($result1) echo "worked";
		else echo $query;
?>
