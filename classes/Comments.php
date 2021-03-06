<?php
/**
 * Comments.php is a file defines the Comment class, its constructors
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
 * Class to handle article/geometry comments
 */

class Comment
{
/**
* @var int The comment ID from the database or new ID generated if its from a form
*/
	public $id = null;
/**
* @var string The comment string
*/
	public $comment = null;
/**
* @var int The publication date of the comment
*/
	public $pub_date = null;
/**
* @var int The User ID who commented
*/
	public $userid = null;
/**
* @var int The article ID to which the comment is associated
*/
	public $articleid = null;
/**
* @var int The Point ID to which the comment is associated
*/
	public $pointid = null;
/**
* @var int The  Polyline ID to which the comment is associated
*/
	public $polylineid = null;
/**
* @var int The  Polygon ID to which the comment is associated
*/
	public $polygonid = null;
	

/**
* Sets the object's properties using the values in the supplied array
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param assoc The property values
*/
	public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['pub_date'] ) )$this->pub_date = $data['pub_date']; else $this->pub_date=null; 
	if ( isset( $data['articleid'] ) ) $this->articleid = (int) $data['articleid'];
    if ( isset( $data['comment'] ) ) $this->comment = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['comment'] );
    if ( isset( $data['userid'] ) ) $this->userid = $data['userid']; else $this->userid = $_SESSION['id'];
    if ( isset( $data['pointid'] ) ) $this->pointid = $data['pointid'];
	if ( isset( $data['pointid'] ) ) $this->pointid = $data['polygonid'];
	if ( isset( $data['pointid'] ) ) $this->pointid = $data['polylineid'];
  }
 
/**
* Sets the object's properties using the edit form post values in the supplied array
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param assoc The form post values
*/
  	public function storeFormValues ( $params ) {
		// Store all the parameters
		$this->__construct( $params );
	}

/**
* Returns a Comment object matching the given comment ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int The comment ID
* @return Comment|false The comment object, or false if the record was not found or there was a problem
*/	
	public static function getById( $id ) {
		$conn = new PDO(DB_DSN);
		$sql = "SELECT * FROM comments WHERE id = :id";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		$row = $st->fetch();
		$conn = null;
		if ( $row ) return new Comment( $row );
    }

/**
* Returns all Comment objects matching the given Article ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int The articleid
* @return Comments|false Returns all comments associated to the article ID or false
*/		
    public static function getByArticleId( $numRows=1000000,$articleid=null, $order="pub_date DESC" ) {
		$conn = new PDO(DB_DSN);
		$articleClause = $articleid ? "WHERE articleid = :articleid" : "";
		$sql = "SELECT * FROM comments $articleClause
				ORDER BY " . $order . " LIMIT :numRows";
	 
		$st = $conn->prepare( $sql );
		$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
		if ( $articleid ) $st->bindValue( ":articleid", $articleid, PDO::PARAM_INT );
		$st->execute();
		$list = array();
	 
		while ( $row = $st->fetch() ) {
		  $comment = new Comment( $row );
		  $list[] = $comment;
		}
	 
		// Now get the total number of comments that matched the criteria
		$sql = "SELECT COUNT(*) AS totalRows FROM comments WHERE articleid = $articleid";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
	}
/**
* Returns all Comment objects matching the given Geometry ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int The Geometry ID
* @param string The type of geometry
* @return Comments|false Returns all comments associated to the Geometry ID or false
*/	
	public static function getByGeometryId( $numRows=1000000,$geometryid=null,$geometrytype=null, $order="pub_date DESC" ) {
		
		switch($geometrytype){
			case 'point': $geometryClause = "WHERE pointid = :pointid";break;
			case 'polyline': $geometryClause = "WHERE polyline = :polylineid";break;
			case 'polygon': $geometryClause = "WHERE polygonid = :polygonid";break;
		}
		$conn = new PDO(DB_DSN);
		
		$sql = "SELECT * FROM comments $geometryClause
				ORDER BY " . $order . " LIMIT :numRows";
	 
		$st = $conn->prepare( $sql );
		$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
		if ( $pointid ) $st->bindValue( ":pointid", $pointid, PDO::PARAM_INT );
			else if ( $polylineid ) $st->bindValue( ":polylineid", $polylineid, PDO::PARAM_INT );
				else if ( $polygonid ) $st->bindValue( ":polygonid", $polygonid, PDO::PARAM_INT );
		$st->execute();
		$list = array();
	 
		while ( $row = $st->fetch() ) {
		  $article = new Comment( $row );
		  $list[] = $article;
		}
	 
		// Now get the total number of comments that matched the criteria
		$sql = "SELECT COUNT(*) AS totalRows FROM comments";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
	}

/**
* Returns all Comment titles based on Category ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int The category ID
* @return Comments|false Returns all comments associated to the article ID or false
*/		
	public static function getList( $numRows=1000000,$categoryid=null, $order="pub_date DESC" ) {
		$conn = new PDO(DB_DSN);
		$categoryClause = $categoryid ? "WHERE categoryid = :categoryid" : "";
		$sql = "SELECT * FROM articles $categoryClause
				ORDER BY " . $order . " LIMIT :numRows";
	 
		$st = $conn->prepare( $sql );
		$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
		if ( $categoryid ) $st->bindValue( ":categoryid", $categoryid, PDO::PARAM_INT );
		$st->execute();
		$list = array();
	 
		while ( $row = $st->fetch() ) {
		  $article = new Article( $row );
		  $list[] = $article;
		}
	 
		// Now get the total number of articles that matched the criteria
		$sql = "SELECT COUNT(*) AS totalRows FROM articles";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
    }

/**
* Inserts a new Comment 
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/		
	public function insert() {
 
		// Does the Comment object already have an ID?
		if ( !is_null( $this->id ) ) trigger_error ( "Comment::insert(): Attempt to insert a Comment object that already has its ID property set (to $this->id).", E_USER_ERROR );
	 
		//What type of a comment is it?
		if($this->articleid) $type = "articleid";
			else if($this->pointid) $type = "pointid";
				else if($this->polylineid) $type = "polylineid";
					else if($this->polygonid) $type = "polygonid";
		
		// Insert the Comment
		$conn = new PDO(DB_DSN);
		$sql = "INSERT INTO comments (comment,pub_date,userid,$type) VALUES ( :comment,:pub_date,:userid,:type)";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":comment", $this->comment, PDO::PARAM_STR );
		$st->bindValue( ":pub_date", 'NOW()', PDO::PARAM_STR );
		$st->bindValue( ":userid", $this->userid, PDO::PARAM_INT );
		
		switch($type){
			case 'articleid':$st->bindValue( ":type", $this->articleid, PDO::PARAM_INT );break;
			case 'pointid':$st->bindValue( ":type", $this->pointid, PDO::PARAM_INT );break;
			case 'polylineid':$st->bindValue( ":type", $this->polylineid, PDO::PARAM_INT );break;
			case 'polygonid':$st->bindValue( ":type", $this->polygonid, PDO::PARAM_INT );break;
		}
             
		$st->execute();
		$this->id = $conn->lastInsertId();
		$conn = null;
	}
/**
* Deletes a comment
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/	
	public function delete() {
		// Does the Article object have an ID?
		if ( is_null( $this->id ) ) trigger_error ( "Comment::delete(): Attempt to delete a comment object that does not have its ID property set.", E_USER_ERROR );
	 
		// Delete the Article
		$conn = new PDO(DB_DSN);
		$st = $conn->prepare ( "DELETE FROM comments WHERE id = :id" );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}
}
?>
