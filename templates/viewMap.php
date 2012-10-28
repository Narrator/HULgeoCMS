<?php
/**
 * viewMap.php is a template file which displays the Mapping platform. Based on
 * the action chosen, either the analytical map or the water portal map is produced
 *
 * viewMap.php also spits on javascript on the server-side (mostly from the 
 * database as client-side variables), to be executed after the page loads.
 * These variables are mostly used by functions written on /js/registered.js
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

/**
 * Including the global header
 */

	include 'include/header.php';

/**
 * Including the database connection details
 */
	include 'include/connect.php'; //remove and change to common DB connection through PDO

/**
 * All the data is first queried from the database - START
 */
	
	//Querying all linestrings from the db
	if(isset($_SESSION['id']) && isset($_SESSION['usr']) && !isset($_GET['maptype']))
	{
		$mapregistered = '<script language="javascript" src="js/registered.js"></script>';
	}
	echo '<script language="javascript">';
	$result = pg_query($connection, "SELECT ogc_fid, ST_AsGeoJSON(wkb_geometry) FROM ogrgeojson1");
	if (!$result) 
	{
		exit;
	}
	
	$i = 0;
	
	while ($row = pg_fetch_row($result)) 
	{	
		echo "\n";
		echo "polylineid[$i] = $row[0];";
		echo "\n";
		echo "geojson_Polyline[$i] = $row[1];";
		echo "\n";
		$i++;
	}		
	
	//Querying all points from the db
	$result2 = pg_query($connection, "SELECT ogc_fid, ST_AsGeoJSON(wkb_geometry) FROM ogrgeojsonpoint");
	if (!$result2) 
	{
		exit;
	}
	
	$j = 0;
	
	while ($row = pg_fetch_row($result2)) 
	{	
		echo "\n";
		echo "pointid[$j] = $row[0];";
		echo "\n";
		echo "geojson_Point[$j] = $row[1];";
		echo "\n";
		$j++;
	}

	//Querying all polygons from the db
	$result3 = pg_query($connection, "SELECT ogc_fid, ST_AsGeoJSON(wkb_geometry) FROM ogrgeojsonpoly");
	if (!$result3) 
	{
		exit;
	}
	
	$k = 0;
	
	while ($row = pg_fetch_row($result3)) 
	{	
		echo "\n";
		echo "polygonid[$k] = $row[0];";
		echo "\n";
		echo "geojson_Polygon[$k] = $row[1];";
		echo "\n";
		$k++;
	}
	
	// Also query all attribute data (7)
	$result4 = pg_query($connection, "SELECT id,category,geometry_type FROM categories");
	if (!$result4) 
	{
		exit;
	}
	
	$l = 0;
	
	while ($row = pg_fetch_row($result4)) 
	{
		echo "\n";
		echo "kcatid[$l] = $row[0];";
		echo "\n";
		echo "kcategory[$l] = \"$row[1]\";";
		echo "\n";
		echo "kcategorytype[$l] = \"$row[2]\";";
		echo "\n";
		$l++;
	}
	
	// Querying all the tag details
	$result5 = pg_query($connection, "SELECT id,tags,tagcatid FROM tags");
	if (!$result5) 
	{
		exit;
	}
	
	$l = 0;
	
	while ($row = pg_fetch_row($result5)) 
	{
		echo "\n";
		echo "ktagid[$l] = $row[0];";
		echo "\n";
		echo "ktags[$l] = \"$row[1]\";";
		echo "\n";
		echo "ktagcatid[$l] = $row[2];";
		echo "\n";
		$l++;
	}
	
	echo "\n";
	
	$pointcategories = array();
	$polylinecategories = array();
	$polygoncategories = array();
	
	
	// Querying all the column names - polyline
	$result6 = pg_query($connection, "select column_name from information_schema.columns where table_name='ogrgeojson1'");
	if (!$result6) 
	{
		exit;
	}
	
	$l = 0;
	
	while ($row = pg_fetch_row($result6)) 
	{
		if($row[0]!='ogc_fid' and $row[0]!='wkb_geometry' and $row[0]!='photo' and $row[0]!='video')
		{
			$polylinecategories[$l] = $row[0];
			$l++;
		}
	}

	// Querying all the column names - polygon
	$result7 = pg_query($connection, "select column_name::text from information_schema.columns where table_name='ogrgeojsonpoly'");
	if (!$result7) 
	{
		exit;
	}
	
	$l = 0;
	
	while ($row = pg_fetch_row($result7)) 
	{
		if($row[0]<>"ogc_fid" and $row[0]<>"wkb_geometry" and $row[0]<>"photo" and $row[0]<>"video")
		{
			$polygoncategories[$l] = $row[0];
			$l++;
		}
	}
	
	// Querying all the column names - point
	$result8 = pg_query($connection, "select column_name from information_schema.columns where table_name='ogrgeojsonpoint'");
	if (!$result8) 
	{
		exit;
	}
	
	$l = 0;
	
	while ($row = pg_fetch_row($result8)) 
	{
		if($row[0]<>'ogc_fid' and $row[0]<>'wkb_geometry' and $row[0]<>'photo' and $row[0]<>'video')
		{
			$pointcategories[$l] = $row[0];
			$l++;
		}
	}
	
	pg_close();

	$pointarray = array();
	$conn = new PDO(DB_DSN);
	$sql = "SELECT * FROM ogrgeojsonpoint";
	$st = $conn->prepare( $sql );
	$st->execute();
	while ( $row = $st->fetch() ) {
	  $pointarray[] = $row;
	}
	$conn = null;
	
	$polylinearray = array();
	$conn = new PDO(DB_DSN);
	$sql = "SELECT * FROM ogrgeojson1";
	$st = $conn->prepare( $sql );
	$st->execute();
	while ( $row = $st->fetch() ) {
	  $polylinearray[] = $row;
	}
	$conn = null;
	
	$polygonarray = array();
	$conn = new PDO(DB_DSN);
	$sql = "SELECT * FROM ogrgeojsonpoly";
	$st = $conn->prepare( $sql );
	$st->execute();
	while ( $row = $st->fetch() ) {
	  $polygonarray[] = $row;
	}
	$conn = null;
	
	$commentarray = array();
	$conn = new PDO(DB_DSN);
	$sql = "SELECT comment,userid,pub_date,pointid,polylineid,polygonid FROM comments";
	$st = $conn->prepare( $sql );
	$st->execute();
	$l=0;
	while ( $row = $st->fetch() ) {
		echo "commentarray[$l] = new Array();";
		if($row['pointid']) {
			$data = User::getByUserId($row['userid']);
			echo "commentarray[$l][".$row['pointid']."] = \"".$row['comment']."\";";
			echo "commentarray[$l]['userid'] = \"".$data->usr."\";";
			echo "commentarray[$l]['pubdate'] = \"".$row['pub_date']."\";";
		}
			else if($row['polylineid']) {
				$data = User::getByUserId($row['userid']);
				echo "commentarray[$l][".$row['polylineid']."] = \"".$row['comment']."\";";
				echo "commentarray[$l]['userid'] = \"".$data->usr."\";";
				echo "commentarray[$l]['pubdate'] = \"".$row['pub_date']."\";";
			}
				else if($row['polygonid']) {
					$data = User::getByUserId($row['userid']);
					echo "commentarray[$l][".$row['polygonid']."] = \"".$row['comment']."\";";
					echo "commentarray[$l]['userid'] = \"".$data->usr."\";";
					echo "commentarray[$l]['pubdate'] = \"".$row['pub_date']."\";";
				}
		$l++;
	}
/*
 * End of all querying - END
 *
 * From here on, its transferring these PHP variables to Javascript variables
 */
	echo "var commentgeometries = $l;";
	echo "var pointphotoarray =[],";
	echo "polylinephotoarray =[],";
	echo "polygonphotoarray =[],";
	echo "pointvideoarray =[],";
	echo "polylinevideoarray =[],";
	echo "polygonvideoarray =[];";
	$conn = null;
	
	$l = 0;
	foreach($pointarray as $point)
	{
		echo "pointcattag[".$point['ogc_fid']."] = new Array();";
		echo "pointphotoarray[".$point['ogc_fid']."] = new Array();";
		echo "pointvideoarray[".$point['ogc_fid']."] = new Array();";
                if($point['photo']) { echo "pointphotoarray[".$point['ogc_fid']."][0] = \"".$point['photo']."\";"; }
                if($point['video']) { echo "pointvideoarray[".$point['ogc_fid']."][0] = \"".$point['video']."\";"; }
		echo "\n";
		$l=0;
		foreach($pointcategories as $pointcategory){
			if($point[$pointcategory])
			{
				echo "pointcattag[".$point['ogc_fid']."][$l]=\"$pointcategory\"";
				echo "\n";
				$l++;
				echo "pointcattag[".$point['ogc_fid']."][$l] = \"".$point[$pointcategory]."\";";
				echo "\n";
				$l++;
			}
		}
	}
	
	$l = 0;
	foreach($polylinearray as $polyline)
	{
		echo "polylinecattag[".$polyline['ogc_fid']."] = new Array();";
		echo "polylinephotoarray[".$polyline['ogc_fid']."] = new Array();";
		echo "polylinevideoarray[".$polyline['ogc_fid']."] = new Array();";
                if($polyline['photo']) { echo "polylinephotoarray[".$polyline['ogc_fid']."][0] = \"".$polyline['photo']."\";"; }
                if($polyline['video']) { echo "polylinevideoarray[".$polyline['ogc_fid']."][0] = \"".$polyline['video']."\";"; }
		echo "\n";
		$l=0;
		foreach($polylinecategories as $polylinecategory){

			if($polyline[$polylinecategory])
			{
				echo "polylinecattag[".$polyline['ogc_fid']."][$l]=\"$polylinecategory\";";
				echo "\n";
				$l++;
				echo "polylinecattag[".$polyline['ogc_fid']."][$l] = \"".$polyline[$polylinecategory]."\";";
				echo "\n";
				$l++;
			}
		}
	}
	
	$l = 0;
	foreach($polygonarray as $polygon)
	{
		echo "polygoncattag[".$polygon['ogc_fid']."] = new Array();";
		echo "polygonphotoarray[".$polygon['ogc_fid']."] = new Array();";
		echo "polygonvideoarray[".$polygon['ogc_fid']."] = new Array();";
                if ($polygon['photo']) { echo "polygonphotoarray[".$polygon['ogc_fid']."][0] = \"".$polygon['photo']."\";"; }
                if ($polygon['video']) { echo "polygonvideoarray[".$polygon['ogc_fid']."][0] = \"".$polygon['video']."\";";}
		echo "\n";
		$l=0;
		foreach($polygoncategories as $polygoncategory){
			
			if($polygon[$polygoncategory])
			{
				echo "polygoncattag[".$polygon['ogc_fid']."][$l]=\"$polygoncategory\";";
				echo "\n";
				$l++;
				echo "polygoncattag[".$polygon['ogc_fid']."][$l] = \"".$polygon[$polygoncategory]."\";";
				echo "\n";
				$l++;
			}
		}
	}
	echo "\n";
	
	if(isset($_GET['maptype']) && $_GET['maptype'] =='analytics'){
		$conn = new PDO(DB_DSN);
		$sql = "SELECT *,ST_AsGeoJSON(the_geom) AS geom FROM zones";
		$st = $conn->prepare( $sql );
		$st->execute();
		$Zones = array();
		while($row = $st->fetch()){
			$Zone = new Zone($row);
			$Zones[] = $Zone;
		}
		$conn = null;
		$l = 0;
		echo "var Zonearray = new Array();";
		echo "\n";
		foreach($Zones as $Zone){
			echo "Zonearray[$l] = new Zone();";
			echo "\n";
			echo "Zonearray[$l].gid = ".$Zone->gid.";";
			echo "\n";
			echo "Zonearray[$l].zone_id = ".$Zone->zone_id.";";
			echo "\n";
			echo "Zonearray[$l].zonename = \"".$Zone->zonename."\";";
			echo "\n";
			echo "Zonearray[$l].zonalcom = \"".$Zone->zonalcom."\";";
			echo "\n";
			echo "Zonearray[$l].zcnumber = \"".$Zone->zcnumber."\";";
			echo "\n";
			echo "Zonearray[$l].zcemail = \"".$Zone->zcemail."\";";
			echo "\n";
			echo "Zonearray[$l].the_geom = ".$Zone->geom.";";
			echo "\n";
			$l++;
		}


		$conn = new PDO(DB_DSN);
		$sql = "SELECT *,ST_AsGeoJSON(the_geom) AS geom FROM circles";
		$st = $conn->prepare( $sql );
		$st->execute();
		$Circles = array();
		while($row = $st->fetch()){
			$Circle = new Circle($row);
			$Circles[] = $Circle;
		}
		$conn = null;
		$l = 0;
		echo "var Circlearray = new Array();";
		echo "\n";
		foreach($Circles as $Circle){
			echo "Circlearray[$l] = new Circle();";
			echo "\n";
			echo "Circlearray[$l].gid = ".$Circle->gid.";";
			echo "\n";
			echo "Circlearray[$l].circle_id = ".$Circle->gid.";";
			echo "\n";
			echo "Circlearray[$l].circlename = \"".$Circle->circlename."\";";
			echo "\n";
			echo "Circlearray[$l].zone_id = ".$Circle->zone_id.";";
			echo "\n";
			echo "Circlearray[$l].zonename = \"".$Circle->zonename."\";";
			echo "\n";
			echo "Circlearray[$l].circleadd = \"".$Circle->circleadd."\";";
			echo "\n";
			echo "Circlearray[$l].circlphone = \"".$Circle->circlphone."\";";
			echo "\n";
			echo "Circlearray[$l].zonecom = \"".$Circle->zonalcom."\";";
			echo "\n";
			echo "Circlearray[$l].zcnumber = \"".$Circle->zcnumber."\";";
			echo "\n";
			echo "Circlearray[$l].zcemail = \"".$Circle->zcemail."\";";
			echo "\n";
			echo "Circlearray[$l].the_geom = ".$Circle->geom.";";
			echo "\n";
			$l++;
		}
		
		$conn = new PDO(DB_DSN);
		$sql = "SELECT *,ST_AsGeoJSON(the_geom) AS geom FROM wards";
		$st = $conn->prepare( $sql );
		$st->execute();
		$Wards = array();
		while($row = $st->fetch()){
			$Ward = new Ward($row);
			$Wards[] = $Ward;
		}
		
		$conn = null;
		$l = 0;
		echo "var Wardarray = new Array();";
		echo "\n";
		foreach($Wards as $Ward){
			echo "Wardarray[$l] = new Ward();";
			echo "\n";
			echo "Wardarray[$l].gid = ".$Ward->gid.";";
			echo "\n";
			echo "Wardarray[$l].ward_id = ".$Ward->ward_id.";";
			echo "\n";
			echo "Wardarray[$l].wardname = \"".$Ward->wardname."\";";
			echo "\n";
			echo "Wardarray[$l].circle_id = ".$Ward->circle_id.";";
			echo "\n";
			echo "Wardarray[$l].circlename = \"".$Ward->circlename."\";";
			echo "\n";
			echo "Wardarray[$l].zone_id = ".$Ward->zone_id.";";
			echo "\n";
			echo "Wardarray[$l].zonename = \"".$Ward->zonename."\";";
			echo "\n";
			echo "Wardarray[$l].elected = \"".$Ward->elected."\";";
			echo "\n";
			echo "Wardarray[$l].party = \"".$Ward->party."\";";
			echo "\n";
			echo "Wardarray[$l].electphone = \"".$Ward->electphone."\";";
			echo "\n";
			echo "Wardarray[$l].electemail = \"".$Ward->electemail."\";";
			echo "\n";
			echo "Wardarray[$l].circleadd = \"".$Ward->circleadd."\";";
			echo "\n";
			echo "Wardarray[$l].circlphone = \"".$Ward->circlphone."\";";
			echo "\n";
			echo "Wardarray[$l].zonalcom = \"".$Ward->zonalcom."\";";
			echo "\n";
			echo "Wardarray[$l].zcnumber = \"".$Ward->zcnumber."\";";
			echo "\n";
			echo "Wardarray[$l].zcemail = \"".$Ward->zcemail."\";";
			echo "\n";
			echo "Wardarray[$l].the_geom = ".$Ward->geom.";";
			echo "\n";
			$l++;
		}
	}
	echo '</script>';

/**
 * This is the End of all the PHP variables converted to Javascript variables
*/


?>
	<div id="map" style="position: relative; height:480px; margin-left:auto; margin-right:auto;	-moz-border-radius:20px; -khtml-border-radius: 20px;-webkit-border-radius: 20px;border-radius:20px;">
	</div>
	<div id="cwzinfo" style="margin-right:30px;" Title="Information">
	</div>

	<script language="javascript">
		var map = new L.Map('map');
		var bing = new L.BingLayer("Anqm0F_JjIZvT0P3abS6KONpaBaKuTnITRrnYuiJCE0WOhH6ZbE4DzeT6brvKVR5");
		var tilestache = new L.TileLayer('http://www.hyderabadurbanlab.com/tiles/tiles.py/hul2/{z}/{x}/{y}.png', 
		{
			attribution: 'Map data and Imagery © <a href="http://letsrob.org.com">The Spatial Reasoning Project</a>',
			maxZoom: 18,
			minZoom: 11
		});
		
		var besant = new L.LatLng(17.4099,78.4671);
		map.addLayer(bing);
		<?php if(isset($_GET['maptype']) && $_GET['maptype']=='analytics'){?>
		map.setView(besant, 11).addLayer(tilestache);
		<?php } else {?>
		map.setView(besant, 11);
		<?php } ?>

		var hulmarker = new L.LatLng(17.4099,78.4671);
		
		var marker = new L.Marker(hulmarker);
		map.addLayer(marker);
		
	<?php if(isset($_GET['maptype']) && $_GET['maptype']=='analytics'){?>
		var infobig = "";
		var ZoneGJarray = [],
		CircleGJarray = [],
		WardGJarray = [];
		
		function zoneinfo(zone) {
			var infostring = "<div class=\"right-nav\" style=\"font-size:13px;margin-top:10px;\">";
			infostring = infostring + "<b>Zone Number</b>: " + zone.zone_id + "</br><b>Zone Name</b>: " + zone.zonename + "</br><b>Zonal Commissioner</b>: " + zone.zonalcom + "</br><b>Zonal Commissioner number</b>: " + zone.zcnumber + "</br><b>Zonal Commissioner E-mail</b>: " + zone.zcemail;
			infostring = infostring + "</div>";
			return infostring;
		}
		
		function circleinfo(circle) {
			var infostring = "<div class=\"right-nav\" style=\"font-size:13px;margin-top:10px;\">";
			infostring = infostring + "<b>Circle Number</b>: " + circle.circle_id + "</br><b>Circle Name</b>: " + circle.circlename + "</br><b>Circle Address</b>: " + circle.circleadd + "</br><b>Circle Phone</b>: " + circle.circlphone;
			infostring = infostring + "</div>";
			return infostring;
		}
		
		function wardinfo(ward) {
			var infostring = "<div class=\"right-nav\" style=\"font-size:13px;margin-top:10px;\">";
			infostring = infostring + "<b>Ward Number</b>: " + ward.ward_id + "</br><b>Ward Name</b>: " + ward.wardname + "</br><b>Name of Councillor</b>: " + ward.elected + "</br><b>Party</b>: " + ward.party + "</br><b>Councillor Phone</b>: " + ward.electphone + "</br><b>Councillor E-mail</b>: " + ward.electemail;
			infostring = infostring + "</div>";
			return infostring;
		}
		
		for(var i=0; i<Zonearray.length;i++){
			infobig = zoneinfo(Zonearray[i]);
			geojson_multipolygon(Zonearray[i].the_geom,"zone",infobig);
		}
		for(var i=0; i<Circlearray.length;i++){
			infobig = circleinfo(Circlearray[i]);
			geojson_multipolygon(Circlearray[i].the_geom,"circle",infobig);
		}
		for(var i=0; i<Wardarray.length;i++){
			infobig = wardinfo(Wardarray[i]);
			geojson_multipolygon(Wardarray[i].the_geom,"ward",infobig);
		}
		
		for(var i=0; i<ZoneGJarray.length;i++){
			map.addLayer(ZoneGJarray[i]);
		}
		
		function zoomtest(e){
			switch(map.getZoom()){
				case 11: for(var i=0; i<ZoneGJarray.length;i++){
							map.addLayer(ZoneGJarray[i]);
						 }
						 for(var i=0; i<CircleGJarray.length;i++){
							map.removeLayer(CircleGJarray[i]);
						 }
						 for(var i=0; i<WardGJarray.length;i++){
							map.removeLayer(WardGJarray[i]);
						 }
						 break; 
				case 12: for(var i=0; i<ZoneGJarray.length;i++){
							map.removeLayer(ZoneGJarray[i]);
						 }
						 for(var i=0; i<CircleGJarray.length;i++){
							map.addLayer(CircleGJarray[i]);
						 }
						 for(var i=0; i<WardGJarray.length;i++){
							map.removeLayer(WardGJarray[i]);
						 }
						 break;   //overlay circle,remove zone
				case 13: for(var i=0; i<ZoneGJarray.length;i++){
							map.removeLayer(ZoneGJarray[i]);
						 }
						 for(var i=0; i<CircleGJarray.length;i++){
							map.addLayer(CircleGJarray[i]);
						 }
						 for(var i=0; i<WardGJarray.length;i++){
							map.removeLayer(WardGJarray[i]);
						 }
						 break;   //overlay circle, remove ward
				case 14: for(var i=0; i<ZoneGJarray.length;i++){
							map.removeLayer(ZoneGJarray[i]);
						 }
						 for(var i=0; i<CircleGJarray.length;i++){
							map.removeLayer(CircleGJarray[i]);
						 }
						 for(var i=0; i<WardGJarray.length;i++){
							map.addLayer(WardGJarray[i]);
						 }
						 break;   //overlay ward, remove circle
			}
		}
		
		map.on('zoomend',zoomtest);
		
		function geojson_multipolygon(geojson_poly,type,info){
			var geojsonMP = new L.GeoJSON();
			switch(type){
				case "zone":color="green";break;
				case "ward":color="red";break;
				case "circle":color="blue";break;
			}
			geojsonMP.on("featureparse", function (e){
				e.layer.setStyle({
					color:'white',
					opacity:0,
					weight:0
				});
			});
			
			geojsonMP.addGeoJSON(geojson_poly);
			
			
			geojsonMP.on('click', function(e){
				document.getElementById('cwzinfo2').innerHTML = info;
			});
			
			switch(type){
				case "zone":ZoneGJarray.push(geojsonMP);break;
				case "ward":WardGJarray.push(geojsonMP);break;
				case "circle":CircleGJarray.push(geojsonMP);break;
			}
		}
		
	<?php }?>
	
	<?php if(!isset($_GET['maptype'])) { ?>
		for(var i=0;i<pointid.length || i<polygonid.length || i<polylineid.length;i++)
		{
			if(i<pointid.length) {
				geojson_leaflet(geojson_Point[i],pointid[i]);
			}
			if(i<polylineid.length)
				geojson_leaflet(geojson_Polyline[i],polylineid[i]);
				
			if(i<polygonid.length)
				geojson_leaflet(geojson_Polygon[i],polygonid[i]);
		} 
	<?php } ?>

		function geojson_leaflet(geojson_poly,polyid,addorremove){
			
			var infoarray = new Array();
			var photolink = "";
			var videolink = "";
			var type= new String();
			var color = "";
			var weight = 0;
			var opacity = 0;
			switch(geojson_poly.type){
				case "LineString": infoarray = polylinecattag[polyid]; if(polylinephotoarray[polyid][0]) photolink=polylinephotoarray[polyid][0]; if(polylinevideoarray[polyid][0]) videolink=polylinevideoarray[polyid][0];type="polylineid";color='red';weight=3;opacity=1;break;
				case "Point": infoarray = pointcattag[polyid]; if(pointphotoarray[polyid][0]) photolink=pointphotoarray[polyid][0]; if(pointvideoarray[polyid][0]) videolink = pointvideoarray[polyid][0];type="pointid";opacity=1;break;
				case "Polygon": infoarray = polygoncattag[polyid]; if(polygonphotoarray[polyid][0]) photolink=polygonphotoarray[polyid][0]; if(polygonvideoarray[polyid][0]) videolink=polygonvideoarray[polyid][0];type="polygonid";color='green';weight=3;opacity=1;break;
			}

			if (photolink)			
			var photosholder = photolink.split(",");			
			
			var geojsonLayer = new L.GeoJSON();
			var commentstring = "";
                        var infostring = "<div class=\"right-nav\" style=\"margin-top:10px;\" >";
			geojsonLayer.on("featureparse", function (e){
				if(geojson_poly.type =="LineString" || geojson_poly.type =="Polygon"){
					e.layer.setStyle({
						color:color,
						opacity:opacity,
						weight:weight
					});
				}
			
			if(photolink) {
				infostring = infostring + "<div><h3>Photos</h3></br>";
				for(var i=0; i<1; i++) {
					infostring = infostring + "<img style=\"width:200px;height:200px;\" src =\"http://hyderabadurbanlab.com/js/pkgs/blueimp/server/php/files/" + photosholder[i]+ "\">";		
				}
				infostring = infostring + "</div>";
			}
			if(videolink) {			
				infostring = infostring + "<div><h3>Videos</h3></br>";
				infostring = infostring + "<iframe width=\"231\" height=\"130\" src=\"";
				infostring = infostring + videolink;
				infostring = infostring + "\" frameborder=\"0\" allowfullscreen></iframe>" +"</div>";
			}
					
					infostring = infostring + "<div style=\"width:300px;margin-top:10px;\"><b>Category</b>: ";
					
					for(var i=0;i<infoarray.length;i=i+2)
					{
						var j=1;
						infostring += infoarray[i]+"</br></br><b>Tags</b>:"+infoarray[j];
						j=j+2;
					
					}
					
					infostring += "</br></br><h3>View Comments</h3></br></div>";
					for(var i=0; i<commentgeometries;i++)
					{
						if(commentarray[i][polyid]){
							infostring += "<b>" + commentarray[i]['userid'] + "</b>" + " said on " + commentarray[i]['pubdate'] + ":</br>";
							var commentdisplaystring = commentarray[i][polyid];
							infostring += commentdisplaystring+"</br></br>";
						}
					}
					
		<?php if (isset($_SESSION['usr'])) { ?>
					infostring += "</br></br><h3>Add Comments</h3></br>";
			
			   commentstring = "<div>";
				commentstring += "<form action=\"index.php?action=addComment&"+type+"="+polyid+"\" method=\"post\">";
					commentstring += "<label for=\"comment\">Comment text area</label><textarea style=\"width:100%; height:100px;\" name=\"comment\" id=\"comment\" placeholder=\"Enter your comments for this article\" required maxlength=\"1000\" style=\"height: 5em;\"></textarea>";
						commentstring += "<div class=\"buttons\">";
							commentstring += "<input type=\"submit\" name=\"commentSubmitted\" value=\"Submit Comment\" /></div></form></div>";
		<?php } ?>
                                       
					infostring = infostring + commentstring;
                                        infostring = infostring + '</div>';
					
				});

			geojsonLayer.addGeoJSON(geojson_poly);
			map.addLayer(geojsonLayer);
			geojsonLayer.on('click', function(e){
				document.getElementById('cwzinfo2').innerHTML = infostring;
			});
		}
	</script>
<?php	if(isset($mapregistered) && isset($_SESSION['id']) && !isset($_GET['maptype'])) {
			include('include/map-form.html');
			echo $mapregistered;
		}
?>
	</div>		

	<div class="right-nav">
<?php if(!isset($_GET['maptype'])) { ?>
		<h1>Water Portal</h1>
		<h5>This is the map based interface</h5>
<?php } else { ?>
		<h1>Map Analytics</h1>
		<h5>Map analytics will be placed here</h5>
<?php } ?>

	</div>
        </br>
        <div  id="cwzinfo2">
        </div>
	</div>
	<!-- From here on the functions are used for the image upload UI--!>
	<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
	<script src="js/pkgs/blueimp/js/vendor/jquery.ui.widget.js"></script>
	<!-- The Templates plugin is included to render the upload/download listings -->
	<script src="js/pkgs/blueimp/JavaScript-Templates/tmpl.min.js"></script>
	<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
	<script src="js/pkgs/blueimp/JavaScript-Load-Image/load-image.min.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="js/pkgs/blueimp/js/jquery.iframe-transport.js"></script>
	<!-- The basic File Upload plugin -->
	<script src="js/pkgs/blueimp/js/jquery.fileupload.js"></script>
	<!-- The File Upload file processing plugin -->
	<script src="js/pkgs/blueimp/js/jquery.fileupload-fp.js"></script>
	<!-- The File Upload user interface plugin -->
	<script src="js/pkgs/blueimp/js/jquery.fileupload-ui.js"></script>
	<!-- The localization script -->
	<script src="js/pkgs/blueimp/js/locale.js"></script>
	<!-- The main application script -->
	<script src="js/pkgs/blueimp/js/main1.js"></script>
	<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
	<!--[if gte IE 8]><script src="js/cors/jquery.xdr-transport.js"></script><![endif]-->
<?php 
/**
 * Including the global footer
 */
	include 'include/footer.php'
?>
