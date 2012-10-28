<?php
/**
 * User.php is a file defines the User class, its constructors
 * ,methods and properties
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
 * @subpackage CMS
 * @author     Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @copyright  Kaushik Subramaniam, 2012
 * @license    GNU General Public License, Version 3 <http://opensource.org/licenses/gpl-license.php>
 * @version    1
 * @since      0.1
 * @link       http://hyderabadurbanlab.com
 * 
 */

/**
 * Class to handle Users
 */
class User
{
/**
* @var int The User ID from the database or new ID generated if its from a form
*/
	public $id = null;
/**
* @var string The Username associated to the ID
*/
	public $usr = null;
/**
* @var string The Password for this account
*/
	public $pass = null;
/**
* @var string The E-mail address for this account
*/
	public $email = null;
/**
* @var string The date of registration
*/
	public $dt = null;
/**
* @var string The IP of registration
*/
	public $regip = null;
/**
* @var int Access level of user - defaults to 2
*/
	public $access_level = null;
/**
* @var string Last date of login
*/
	public $last_login = null;
/**
* @var string The User's phone number
*/
	public $phone = null;
/**
* @var string The user's Address
*/
	public $address = null;
/**
* @var string The name of the User
*/
	public $name = null;

/**
* Sets the object's properties using the values in the supplied array
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param assoc The property values
*/	
	public function __construct( $data=array() ) {
		if ( isset( $data['id'] ) ) $this->id = (int)$data['id'];
		if ( isset( $data['usr'] ) ) $this->usr = $data['usr'];
		if ( isset( $data['pass'] ) ) $this->pass = $data['pass'];
		if ( isset( $data['email'] ) ) $this->email = $data['email'];
		if ( isset( $data['dt'] ) ) $this->dt = $data['dt'];
		if ( isset( $data['regip'] ) ) $this->regip = $data['regip'];
		if ( isset( $data['access_level'] ) ) $this->access_level = (int)$data['access_level'];
		if ( isset( $data['last_login'] ) ) $this->last_login = $data['last_login'];
		if ( isset( $data['phone'] ) ) $this->phone = $data['phone'];
		if ( isset( $data['address'] ) ) $this->address = $data['address'];
		if ( isset( $data['name'] ) ) $this->name = $data['name'];
	}

/**
* Returns a User object based on user ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int The User ID
* @return User|false The user object, or false if the record was not found or there was a problem
*/
	public static function getByUserId($userid) {
		$conn = new PDO(DB_DSN);
		$sql = "SELECT * FROM users where id=$userid";
		$st = $conn->prepare( $sql );
		$st->execute();
		$row = $st->fetch();
		$user = new User($row);
		return $user;
	}
}
?>
