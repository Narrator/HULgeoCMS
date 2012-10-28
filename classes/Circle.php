<?php
/**
 * Circle.php is the class which handles a particular type of feature 
 * called Circles, which is a boundary-type in Hyderabad.
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
 * @package    classPackage
 * @subpackage Geometry
 * @author     Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @copyright  Kaushik Subramaniam, 2012
 * @license    GNU General Public License, Version 3 <http://opensource.org/licenses/gpl-license.php>
 * @version    1
 * @since      0.1
 * @link       http://hyderabadurbanlab.com
 * 
 */

/**
 * Class to handle Circle-type geometries
 */
	class Circle
	{
/**
* @var int The geometry ID for the geometry
*/
		public $gid = null;
/**
* @var int The ID specific to the circle - given by data
*/
		public $circle_id = null;
/**
* @var string The name of the Circle
*/
		public $circlename = null;
/**
* @var int The Zone ID in which the Circle is present 
*/
		public $zone_id = null;
/**
* @var string The name of the zone 
*/
		public $zonename = null;
/**
* @var string The circle address
*/
		public $circleadd = null;
/**
* @var string The circle phone number
*/
		public $circlphone = null;
/**
* @var string Name of the Zonal commissioner
*/
		public $zonalcom = null;
/**
* @var string The phone number of the Zonal Commissioner
*/
		public $zcnumber = null;
/**
* @var string The e-mail address of the Zonal Commissioner
*/
		public $zcemail = null;
/**
* @var string The geometry
*/
		public $geom = null;


/**
* Sets the object's properties using the values in the supplied array
*
* @package	classPackage
* @subpackage Geometry
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param assoc The property values
*/
		
		public function __construct( $data=array() ) {
			if ( isset( $data['gid'] ) ) $this->gid = (int) $data['gid'];
			if ( isset( $data['circle id'] ) ) $this->circle_id = (int) $data['circle id'];
			if ( isset( $data['circlename'] ) ) $this->circlename = $data['circlename'];
			if ( isset( $data['zone id'] ) ) $this->zone_id = (int) $data['zone id'];
			if ( isset( $data['zonename'] ) ) $this->zonename = $data['zonename'];
			if ( isset( $data['circleadd'] ) ) $this->circleadd = $data['circleadd'];
			if ( isset( $data['circlphone'] ) ) $this->circlphone = $data['circlphone'];
			if ( isset( $data['zonalcom'] ) ) $this->zonalcom = $data['zonalcom'];
			if ( isset( $data['zcnumber'] ) ) $this->zcnumber = $data['zcnumber'];
			if ( isset( $data['zcemail'] ) ) $this->zcemail = $data['zcemail'];
			if ( isset( $data['geom'] ) ) $this->geom = $data['geom'];
		}
	}
?>
