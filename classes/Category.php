<?php
/**
 * Category.php is a file defines the Catgeory class, its constructors
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
 * Class to handle article categories
 */
 
class Category
{
/**
* @var int The category ID from the database
*/
  public $id = null;
 
/**
* @var string Name of the category
*/
  public $name = null;
 
/**
* @var string A short description of the category
*/
  public $description = null;
 
 
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
    if ( isset( $data['name'] ) ) $this->name = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['name'] );
    if ( isset( $data['description'] ) ) $this->description = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['description'] );
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
* Returns a Category object matching the given category ID
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int The category ID
* @return Category|false The category object, or false if the record was not found or there was a problem
*/
 
  public static function getById( $id ) {
    $conn = new PDO( DB_DSN );
    $sql = "SELECT * FROM articlecategories WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Category( $row );
  }
 
 
/**
* Returns all (or a range of) Category objects in the DB
*
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
* @param int Optional The number of rows to return (default=all)
* @param string Optional column by which to order the categories (default="name ASC")
* @return Array|false A two-element array : results => array, a list of Category objects; totalRows => Total number of categories
*/
 
  public static function getList( $numRows=1000000, $order="name ASC" ) {
    $conn = new PDO( DB_DSN );
    $sql = "SELECT * FROM articlecategories
            ORDER BY " . $order . " LIMIT :numRows";
 
    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();
 
    while ( $row = $st->fetch() ) {
      $category = new Category( $row );
      $list[] = $category;
    }
 
    // Now get the total number of categories that matched the criteria
    $sql = "SELECT COUNT(*) AS totalRows FROM articlecategories";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }
 
 
/**
* Inserts the current Category object into the database, and sets its ID property.
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/
 
  public function insert() {
 
    // Does the Category object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Category::insert(): Attempt to insert a Category object that already has its ID property set (to $this->id).", E_USER_ERROR );
 
    // Insert the Category
    $conn = new PDO( DB_DSN );
    $sql = "INSERT INTO articlecategories ( name, description ) VALUES ( :name, :description )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }
 
 
/**
* Updates the current Category object in the database.
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/
 
  public function update() {
 
    // Does the Category object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Category::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR );
    
    // Update the Category
    $conn = new PDO( DB_DSN );
    $sql = "UPDATE articlecategories SET name=:name, description=:description WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
/**
* Deletes the current Category object from the database.
* @package	classPackage
* @subpackage CMS
* @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
*/
 
  public function delete() {
 
    // Does the Category object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Category::delete(): Attempt to delete a Category object that does not have its ID property set.", E_USER_ERROR );
 
    // Delete the Category
    $conn = new PDO( DB_DSN );
    $st = $conn->prepare ( "DELETE FROM articlecategories WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>
