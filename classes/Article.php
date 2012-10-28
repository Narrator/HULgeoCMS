<?php
/**
 * Article.php is a file defines the Article class, its constructors
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
 * Class to handle articles
 */
class Article
{
 
/**
* @var int The article ID from the database or new ID generated if its from a form
*/
  public $id = null;
 
/**
* @var int When the article was published
*/
  public $pub_date = null;
  
/**
* @var int The article category ID
*/
  public $categoryid = null;
/**
* @var string Full title of the article
*/
  public $title = null;
 
/**
* @var string A short summary of the article
*/
  public $summary = null;
 
/**
* @var string The HTML content of the article
*/
  public $content = null;

/**
* @var int User id associated to each article
*/
  public $userid = null;

/**
* @var string Last editted date of article
*/
  public $edit_date = null;

/**
* @var int User id of user who last editted the article
*/
  public $last_edit_by = null;

/**
* @var array Array containing all tags associated to the article
*/
  public $tagidarray = array();

/**
* @var array Array containing all tag ids associated to the article
*/
  public $tagids = array();

/**
* @var string String containing all tag values from the form
*/
  public $tags = null; //This ONLY recieves from the form to store as tag ids. 
 
/**
* Sets the object's properties using the values in the supplied array
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*
* @param assoc The property values - either from form or from database
*/
  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['pub_date'] ) )$this->pub_date = $data['pub_date']; else $this->pub_date=null; 
	if ( isset( $data['categoryid'] ) ) $this->categoryid = (int) $data['categoryid'];
    if ( isset( $data['title'] ) ) $this->title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title'] );
    if ( isset( $data['summary'] ) ) $this->summary = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary'] );
    if ( isset( $data['content'] ) ) $this->content = $data['content'];
	if ( isset( $data['userid'] ) ) $this->userid = $data['userid']; else if($_SESSION['id']) $this->userid = $_SESSION['id'];
	if ( isset( $data['last_edit_by'] ) ) $this->last_edit_by = $data['last_edit_by']; else if($_SESSION['id']) $this->last_edit_by = $_SESSION['id'];
	if ( isset( $data['edit_date'] ) ) $this->edit_date = $data['edit_date'];
	if( isset( $data['tagidarray'])) {
		$tagids = trim($data['tagidarray'],"{}");
		$tagidArray = explode(",", $tagids);
		$this->tagids = $tagidArray;
		$l=0;
		foreach($tagidArray as $tagid){
			$this->tagidarray[$l] = Tags::getByTagId($tagid);
			$l++;
		}
	}
	if( isset( $data['tags'])) {
	
		$tagsarray = array();
		$tagstrimmed = trim($data['tags'], " ");
		$tagsholder = explode(",", $tagstrimmed);
		$alltags = Tags::getAllTags();
		$results['tags'] = $alltags['results'];
		
		foreach($tagsholder as $tag)
		{
			$availability = false;
			$check = false;
			foreach($results['tags'] as $alltag)
			{
				if($alltag->tag == $tag && $availability==false)
				{
					if($alltag->articleids){
						$articleids = trim($alltag->articleids,"{}");
						$articleidarray = explode(",",$articleids);	
						foreach($articleidarray as $articleid)
						{
							if($articleid == $this->id) $check = true;
						}
					}
					else {
						$articleidarray=array();
					}

					if($check == false) array_push($articleidarray,$this->id);
					$articlestring = "'{";
					foreach($articleidarray as $articleid) {
						$articlestring .= $articleid.",";
					}
					$articlestringtrimmed = mb_substr($articlestring, 0, -1);
					$articlestringtrimmed .= "}'";
					$alltag->articleids = $articlestringtrimmed;
					$alltag->update();
					$availability = true;
				}
			}
			
			if(!$availability) {
				$insertTag = new Tags;
				$insertTag->tag = $tag;
				$insertTag->articleids = "'{".$this->id."}'"; 
				$insertTag->insert();
			}
		}
	
		$l = 0;
		foreach($tagsholder as $tag) {
			$tagsarray[$l] = Tags::getIdByTag($tag);
			$l++;
		}

		$tagstring = "'{";
		foreach($tagsarray as $tag) {
			$tagstring .= $tag.",";
		}
		$tagstringtrimmed = mb_substr($tagstring, 0, -1);
		$tagstringtrimmed .= "}'";
		$this->tags = $tagstringtrimmed;
	}
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
* Returns an Article object matching the given article ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int The article ID
* @return Article|false The article object, or false if the record was not found or there was a problem
*/
 
  public static function getById( $id ) {
    $conn = new PDO(DB_DSN);
    $sql = "SELECT * FROM articles WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Article( $row );
  }
 
 
/**
* Returns all (or a range of) Article objects in the DB
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int Optional The number of rows to return (default=all)
* @param string Optional column by which to order the articles (default="publicationDate DESC")
* @return Array|false A two-element array : results => array, a list of Article objects; totalRows => Total number of articles
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
* Inserts the current Article object into the database, and sets its ID property.
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/
 
  public function insert() {
 
    // Does the Article object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );
 
	$tagsall = $this->tags;
    // Insert the Article
    $conn = new PDO(DB_DSN);
    $sql = "INSERT INTO articles ( pub_date, title, summary, content,userid,edit_date,last_edit_by,categoryid,tagidarray ) VALUES ( NOW(), :title, :summary, :content, :userid, NOW(), :last_edit_by, :categoryid, $tagsall )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
	$st->bindValue( ":userid", $this->userid, PDO::PARAM_INT );
	$st->bindValue( ":last_edit_by", $this->last_edit_by, PDO::PARAM_INT); 
	$st->bindValue( ":categoryid", $this->categoryid, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }
 
 
/**
* Updates the current Article object in the database.
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/

  public function update() {
 
    // Does the Article object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );

	$tagsall = $this->tags;
    // Update the Article
    $conn = new PDO(DB_DSN);
    $sql = "UPDATE articles SET edit_date=NOW(),categoryid=:categoryid, last_edit_by=:last_edit_by, title=:title, summary=:summary, content=:content,tagidarray=$tagsall WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":last_edit_by", $this->last_edit_by, PDO::PARAM_INT );
    $st->bindValue( ":categoryid", $this->categoryid, PDO::PARAM_INT );
	$st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
/**
* Deletes the current Article object from the database.
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/
 
  public function delete() {
 
    // Does the Article object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Article
    $conn = new PDO(DB_DSN);
    $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>
