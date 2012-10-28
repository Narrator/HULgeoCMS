<?php

/**
 * index.php is a file which is first accessed when someone 
 * vists the website
 *
 * index.php provides 8 switch conditions, where each condition relates
 * to a particular action index.php is expected to do. The 8 actions 
 * could be wither one of these: Create a new article, Edit an existing
 * article, Delete an article, Edit a category, View an archive format
 * of all articles, View a single article, View the map-based platform,
 * Add a comment and by default: View the homepage. If no action parameter 
 * is passed to index.php, it defaults to the homepage.
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
 * @package    IndexPackage
 * @author     Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @copyright  Kaushik Subramaniam, 2012
 * @license    GNU General Public License, Version 3 <http://opensource.org/licenses/gpl-license.php>
 * @version    1
 * @since      0.1
 * @link       http://hyderabadurbanlab.com
 * 
 */

/**
 * Including the configuration file
*/
	require 'config.php';

/**
 * Global Variable to choose the action index.php is supposed to do
 *
 * @global	string	$action
*/
	$action = isset( $_GET['action'] ) ? $_GET['action'] : "";

/**
 * Global Variable to store the username, if user is logged in
 *
 * @global	string	$username
*/
	$username = isset( $_SESSION['usr'] ) ? $_SESSION['usr'] : "";

/**
 * Global variable for empty string called $mapinclude
 *
 * $mapinclude will include Javascript files if viewMap option is chosen
 *
 * @global	string	$mapinclude
*/
	$mapinclude ="";
 
/**
 *
 * Switch statement to choose the action index.php has to do based on value of $action
 *
 * @param	string	$action
 *
*/
	switch ( $action ){
	
		case 'newArticle':
		newArticle();
		break;
		
		case 'editArticle':
		editArticle();
		break;
		
		case 'deleteArticle':
		deleteArticle();
		break;
		
		case 'editCategory':
		editCategory();
		break;
		
		case 'archive':
		archive();
		break;
		
		case 'viewArticle':
		viewArticle();
		break;
		
		case 'viewMap':
		viewMap();
		break;
		
		case 'addComment':
		addComment();
		break;

		default: homepage();
	}
 

/**
 * archive function gives out the information of an article required by an 
 * archiving format
 *
 * The function gives out the Title, Summary, Tags, Comment number, Category, 
 * publication date and author of any article based on given category id or
 * tag id or just the number of archiving items
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	int	$_GET['tagid']	Gives the tagid - based on which, elements will be archived
 * @param	int	$_GET['catgeoryid']	Gives the Category ID - based on which, elements are archived
 * @return	mixed	$results	An array containing all the results
 *
*/
	function archive(){
	
		$results = array();
		$results['tagidattributes'] = array();
		
		$tagidarray = array();
		
		$tagidget = ( isset( $_GET['tagid'] ) && $_GET['tagid'] ) ? (int)$_GET['tagid'] : null;
		$categoryid = ( isset( $_GET['categoryid'] ) && $_GET['categoryid'] ) ? (int)$_GET['categoryid'] : null;
		
		if(isset($categoryid)){
			$results['category'] = Category::getById( $categoryid );
		}
		
		$data = Article::getList( 100000, isset($results['category']) ? $results['category']->id : null );		
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		
		$data = Tags::getAllTags();
		$results['alltags'] = $data['results'];
		
		if($tagidget){
		
			$data = Tags::getAllByTagId($tagidget);
			$articlestring = $data->articleids;
			$articlestrimmed = trim($articlestring,"{}");
			$articlearray = array();
			$articleidarray = explode(",",$articlestrimmed);
			
			foreach($articleidarray as $articleid){
				$articlearray[] = Article::getById($articleid);
			}
			
			$results['articles'] = $articlearray;
		
		}
		
		foreach ( $results['articles'] as $article) {
		
			$data = Comment::getByArticleId(10000, (int)$article->id );
			$results['commentRows'][$article->id] = $data['totalRows'];
			$results['comments'][$article->id] = $data['results'];

			foreach($results['comments'][$article->id] as $comment){
				$results['commentuser'][$comment->id] = User::getByUserId((int)$comment->userid);
			}

			$data = User::getByUserId((int)$article->userid);
			$results['useridattributes'][$article->id] = $data;

			foreach($article->tagids as $tagid){
			
				if(empty($tagidarray)){
					array_push($tagidarray,$tagid);
				}
				
				else {
				
					foreach($tagidarray as $tagids){
						if($tagid == $tagids) {
							$checktag = 1;
						}
					}
					
					if(!isset($checktag)){
						array_push($tagidarray,$tagid);
					}
				}
			}
		}
		
		foreach($tagidarray as $tagid){
			foreach($results['alltags'] as $tagidattributes){		
				if($tagid == $tagidattributes->id){
					$results['tagidattributes'][] = $tagidattributes;
				}
			}
		}

		$data = Category::getList();
		$results['categories'] = array();
		
		foreach ( $data['results'] as $category ) $results['categories'][$category->id] = $category;
		
		$results['pageHeading'] = $results['category'] ?  $results['category']->name : "Tags";
		$results['pageTitle'] = $results['pageHeading'] . " | Widget News";
		
		require( TEMPLATE_PATH . "/archive.php" );
	}
/**
 * viewArticle accepts an article id and puts out information related to the article
 *
 * The function gives out the Title, Summary, Full-text, Tags, Comment number, Category, 
 * publication date and author of any article based on given article id
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	int	$_GET['articleid']	Gives the articleid - based on which articles will be pulled
 * @return	mixed	$results	An array containing all the results
 *
*/
	function viewArticle() {
		if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
			homepage();
			return;
		}

		$results = array();
		$results['article'] = Article::getById( (int)$_GET["articleId"] );
		
		$data = Comment::getByArticleId(1000000,(int)$_GET["articleId"]);
		$results['comments'] = $data['results'];
		$results['commentRows'] = $data['totalRows'];

		$data = User::getByUserId($results['article']->userid);
		$results['useridattributes'] = $data;

		$data = User::getByUserId($results['article']->last_edit_by);
		$results['lastidattributes'] = $data;

		$results['category'] = Category::getById( $results['article']->categoryid );
		$results['pageTitle'] = $results['article']->title . " | Hyderabad Urban Lab";
		
		require( TEMPLATE_PATH . "/viewArticle.php" );
	}

/**
 * homepage function gives out the information of an article required by an 
 * archiving format, but pulls out the 5 latest articles, irrespective of
 * category
 *
 * The function gives out the Title, Summary, Tags, Comment number, Category, 
 * publication date and author of any article based on the publication date
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	int	$_GET['tagid']	Gives the tagid - based on which, elements will be archived
 * @param	int	$_GET['catgeoryid']	Gives the Category ID - based on which, elements are archived
 * @return	mixed	$results	An array containing all the results
 *
*/
	function homepage() {
		$results = array();
		$data = Article::getList(100000);
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['tagidattributes'] = array();
		$data = Tags::getAllTags();
		$results['alltags'] = $data['results'];

		foreach ( $results['articles'] as $article){
		
			$data = Comment::getByArticleId(10000, (int)$article->id );
			$results['commentRows'][$article->id] = $data['totalRows'];
			$results['comments'][$article->id] = $data['results'];

			foreach($results['comments'][$article->id] as $comment){
				$results['commentuser'][$comment->id] = User::getByUserId((int)$comment->userid);
			}

			$data = User::getByUserId((int)$article->userid);
			$results['useridattributes'][$article->id] = $data;
			
		}
		
		foreach($results['alltags'] as $tagidattributes){		
			$results['tagidattributes'][] = $tagidattributes;	
		}

		$data = Category::getList();
		$results['categories'] = array();
		foreach ( $data['results'] as $category ) $results['categories'][$category->id] = $category;
		$results['pageTitle'] = "Hyderabad Urban Lab - Homepage";
		
		require( TEMPLATE_PATH . "/homepage.php" );
	} 
/**

 * newArticle function is called when the user wants to create a new Article
 * 
 *
 * The function gives either puts out a form for users to enter details of
 * of the article, or saves changes based on entered details in the form
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	mixed	$_POST['saveChanges']	Contains article information entered in the new article form
 * @param	mixed	$_POST['cancel']	If cancel button is clicked on the form
 * @return	mixed	$results	An array containing all the results
 *
*/
	function newArticle(){

		$results = array();
		$results['pageTitle'] = "New Article";
		$results['formAction'] = "newArticle";

		if ( isset( $_POST['saveChanges'] ) ){
		
			// User has posted the article edit form: save the new article
			$article = new Article;
			$article->storeFormValues($_POST);
			$article->insert();
			header( "Location: index.php?status=changesSaved" );

		} elseif ( isset( $_POST['cancel'] ) ){
		
			// User has cancelled their edits: return to the article list
			header( "Location: index.php" );
		} else {
		
			// User has not posted the article edit form yet: display the form
			$results['article'] = new Article;
			$data = Category::getList();
			$results['categories'] = $data['results'];
			require( TEMPLATE_PATH . "/editArticle.php" );
		}
	}

/**
 * editArticle function is called when user wants to edit an article
 *
 * The function puts out a form for users to edit an already existing
 * article based on it's id
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	mixed	$_POST['saveChanges']	Contains article information entered in the new article form
 * @param	mixed	$_POST['cancel']	If cancel button is clicked on the form
 * @param	int	$_POST['articleId']	Article id to be edited
 * @return	mixed	$results	An array containing all the results
 *
*/
	function editArticle(){

		$results = array();
		$results['pageTitle'] = "Edit Article";
		$results['formAction'] = "editArticle";

		if ( isset( $_POST['saveChanges'] ) ){
		
			// User has posted the article edit form: save the article changes

			if ( !$article = Article::getById( (int)$_POST['articleId'] ) ){
				header( "Location: index.php?error=articleNotFound" );
				return;
			}

			$article->storeFormValues( $_POST );
			$article->update();
			header( "Location: index.php?status=changesSaved" );

		} elseif ( isset( $_POST['cancel'] ) ){
		
			// User has cancelled their edits: return to the article list
			header( "Location: index.php" );
		} else {
		
			// User has not posted the article edit form yet: display the form
			$results['article'] = Article::getById( (int)$_GET['articleId'] );
			$data = Category::getList();
			$results['categories'] = $data['results'];
			require( TEMPLATE_PATH . "/editArticle.php" );
		}
	}

/**
 * deleteArticle function is called when user wants to delete an article
 *
 * The function deletes an article based on the article id
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	int	$_GET['articleId']	Contains article id
 *
*/
	function deleteArticle() {
	
		if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
			header( "Location: index.php?error=articleNotFound" );
			return;
		}

		$article->delete();
		header( "Location: index.php?status=articleDeleted" );
	}

/**
 * editCategory function is called when user wants to edit a category
 *
 * The function lets a user edit a category
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	mixed	$_POST['saveChanges']	Contains category information entered in the edit category form
 * @param	mixed	$_POST['cancel']	If cancel button is clicked on the form
 * @param	int	$_POST['categoryid']	Category id to be edited
 * @return	mixed	$results	An array containing all the results
 *
*/
	function editCategory() {

		$results = array();
		$results['pageTitle'] = "Edit Article Category";
		$results['formAction'] = "editCategory";

		if ( isset( $_POST['saveChanges'] ) ){
		
			// User has posted the category edit form: save the category changes
			if ( !$category = Category::getById( (int)$_POST['categoryid'] ) ){
				header( "Location: index.php?action=listCategories&error=categoryNotFound" );
				return;
			}

			$category->storeFormValues( $_POST );
			$category->update();
			header( "Location: index.php?action=listCategories&status=changesSaved" );
			
		} elseif ( isset( $_POST['cancel'] ) ){
		
			// User has cancelled their edits: return to the category list
			header( "Location: index.php?action=listCategories" );
		} else {
		
			// User has not posted the category edit form yet: display the form
			$results['category'] = Category::getById( (int)$_GET['categoryid'] );
			require( TEMPLATE_PATH . "/editCategory.php" );
		}  
	}

/**
 * viewMap function is called to access the mapping platform
 *
 * The function includes all the js/css dependencies needed by viewMap.php
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 *
*/
	function viewMap() {

		$mapinclude = '<link rel="stylesheet" href="css/addgeometry.css" />
	
		
		<link rel="stylesheet" href="js/pkgs/leaflet/css/leaflet.css" />
		<!--[if lte IE 8]>
			<link rel="stylesheet" href="js/pkgs/leaflet/css/leaflet.ie.css" />
		<![endif]-->
		<link rel="stylesheet" href="js/pkgs/leaflet/css/leaflet.draw.css" />
		
		<link type="text/css" href="js/pkgs/jquery-ui/css/ui-darkness/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
		<!-- Generic page styles -->
		<link rel="stylesheet" href="js/pkgs/blueimp/css/style.css">
		<!-- Bootstrap CSS Toolkit styles -->
		<link rel="stylesheet" href="js/pkgs/blueimp/css/bootstrap.min.css">
		<!-- Bootstrap styles for responsive website layout, supporting different screen sizes -->
		<link rel="stylesheet" href="js/pkgs/blueimp/css/bootstrap-responsive.min.css">
		<!-- Bootstrap CSS fixes for IE6 -->
		<!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
		<!-- Bootstrap Image Gallery styles -->
		<link rel="stylesheet" href="js/pkgs/blueimp/css/bootstrap-image-gallery.min.css">
		<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
		<link rel="stylesheet" href="js/pkgs/blueimp/css/jquery.fileupload-ui.css">
		<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
		
		<style>
			label.error { float:right;margin-top:7px;font-size: 10px; width: 250px; display: inline; color: red;}
		</style>

		<script type="text/javascript" src="js/pkgs/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script>
		<script type="text/javascript" src="js/pkgs/jquery.validate.min.js"></script>
		<script type="text/javascript" src="js/pkgs/leaflet/leaflet-src.js"></script>
		<script type="text/javascript" src="js/pkgs/leaflet/leaflet.draw.js"></script>
		<script type="text/javascript" src="js/pkgs/TileLayer.Bing.js"></script>

		<script type="text/javascript" src="js/globals.js"></script>';
		
		require( TEMPLATE_PATH . "/viewMap.php");
	}

/**
 * addComment function is called to add a new comment
 *
 * The function adds comments to both articles and geometries on the map
 *
 * @package	IndexPackage
 * @author	Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @param	mixed	$_POST['commentSubmitted']	generated when comment is submitted from the form
 * @param	int	$_GET['articleid']	Id of article
 * @param	int	$_GET['pointid']	Id of point
 * @param	int	$_GET['polygonid']	Id of polygon
 * @param	int	$_GET['polylineid']	Id of polyline
 *
*/
	function addComment() {

		if ( isset( $_POST['commentSubmitted'] ) ) {
		
			$comment = new Comment;
			$comment->storeFormValues($_POST);
			
			if($_GET['articleid']) $comment->articleid = $_GET['articleid'];
				else if ($_GET['pointid'])$comment->pointid = $_GET['pointid'];
					else if($_GET['polygonid'])$comment->polygonid = $_GET['polygonid'];
						else if($_GET['polylineid'])$comment->polylineid = $_GET['polylineid'];
						
			$comment->insert();

			if($_GET['articleid']){
			
				$articleid = $_GET['articleid'];
				header( "Location: index.php?action=viewArticle&articleId=$articleid" );
			}

			else header( "Location: index.php?action=viewMap" );
		}
	}
?>

