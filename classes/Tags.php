<?php
/**
 * Tags.php is a file defines the Tag class, its constructors
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
 * Class to handle article/geometry tags
 */
class Tags 
{
/**
* @var int The Tag ID from the database or new ID generated if its from a form
*/
	public $id = null;
/**
* @var string The tag name
*/
	public $tag = null;
/**
* @var string The article IDs as a comma-seperated string value
*/
	public $articleids = null;
	
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
		if ( isset( $data['tag'] ) ) $this->tag = $data['tag'];  
		if ( isset( $data['articleids'] ) ) $this->articleids = $data['articleids'];
	}

/**
* Returns all tags in the database
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/	
	public function getAllTags() {
		$conn = new PDO(DB_DSN);
		$sql = "SELECT id,tag,articleids FROM articletags";
		$st = $conn->prepare( $sql );
		$st->execute();
		$tagarray = array();
		
		while($row = $st->fetch()){
			$tag = new Tags($row);
			$tagarray[] = $tag;
		}
		$sql = "SELECT COUNT(*) AS totalRows FROM articletags";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		
		return ( array ( "results" => $tagarray, "totalRows" => $totalRows));
	}

/**
* Returns all tag properties based on tag ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param	int	The Tag ID
*/		
	public function getAllByTagId ($tagid) {
		$conn = new PDO(DB_DSN);
		$sql = "SELECT id,tag,articleids FROM articletags where id=$tagid";
		$st = $conn->prepare( $sql );
		$st->execute();
		
		$row = $st->fetch();
		$tag = new Tags($row);
		return $tag;
	}
/**
* Returns tag name based on tag id
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param	int	The Tag ID
*/	
	public function getByTagId ($tagid) {
		$conn = new PDO(DB_DSN);
		$sql = "SELECT tag FROM articletags where id=$tagid";
		$st = $conn->prepare( $sql );
		$st->execute();
		$row = $st->fetch();
		$tagarray = $row['tag'];
		return $tagarray;
	}
/**
* Returns tag ID based on Tag name
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param	string	The tag name
*/	
	public function getIdByTag ($tag) {
		$conn = new PDO(DB_DSN);
		$sql = "SELECT id FROM articletags WHERE tag='$tag'";
		$st = $conn->prepare( $sql );
		$st->execute();
		$row = $st->fetch();
		$tagid = $row['id'];
		return $tagid;
	}
/**
* Inserts all tags in the database associated to an article ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/	
	public function insert() {
		// Does the Article object already have an ID?
		if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );
	 
		$articleidarr = $this->articleids;
		// Insert the Article
		$conn = new PDO(DB_DSN);
		$sql = "INSERT INTO articletags (tag,articleids ) VALUES (:tag,$articleidarr)";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":tag", $this->tag, PDO::PARAM_STR );
		$st->execute();
		$this->id = $conn->lastInsertId();
		$conn = null;
	}

/**
* Updates tag information when article is edited.
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/	
	public function update() {

		$articleidarr = $this->articleids;
		// Update the Article
		$conn = new PDO(DB_DSN);
		$sql = "UPDATE articletags SET articleids=$articleidarr WHERE tag = :tag";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":tag", $this->tag, PDO::PARAM_STR );

		$st->execute();
		$conn = null;
	}
}
?>
