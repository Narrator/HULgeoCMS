<?php
/**
 * config.php defines global constant variables
 *
 * config.php contains database information, defines the path to all the
 * classes, templates and handles exceptions. Config.php is included in 
 * all the pages through index.php
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
 * @package    ConfigPackage
 * @author     Kaushik Subramaniam <humantorch.shadowsin@gmail.com>
 * @copyright  Kaushik Subramaniam, 2012
 * @license    GNU General Public License, Version 3 <http://opensource.org/licenses/gpl-license.php>
 * @version    1
 * @since      0.1
 * @link       http://hyderabadurbanlab.com
 * 
 */	
	ini_set( "display_errors", false );
	session_start();

/**
 * Defines a default time-zone for the entire website 
 */
	date_default_timezone_set( "Asia/Calcutta" );  // http://www.php.net/manual/en/timezones.php

/**
 * Defines the connection to our database
*/
	define( "DB_DSN", "pgsql:host=localhost;dbname=hul_prod;user=hul;password={DIY}Syndr*me");

/**
 * Path to the classes directory
*/
	define( "CLASS_PATH", "classes" );

/**
 * Path to the Templates directory
*/
	define( "TEMPLATE_PATH", "templates" );

/**
 * Definition of number of articles to be shown on homepage - This is never used I think. Needs to be removed.
 *
 * @deprecated Deprecated during development
*/
	define( "HOMEPAGE_NUM_ARTICLES", 5 );

/**
 * Defines Administrator Username
*/
	define( "ADMIN_USERNAME", "kaushik" );

/**
 * Defines Administrator Password
*/
	define( "ADMIN_PASSWORD", "gimme5" );

/**
 * Including the respective classes - Article
*/
	require( CLASS_PATH . "/Article.php" );

/**
 * Including the respective classes - Category
*/
	require( CLASS_PATH . "/Category.php" );

/**
 * Including the respective classes - Comments
*/
	require( CLASS_PATH . "/Comments.php" );

/**
 * Including the respective classes - Tags
*/
	require( CLASS_PATH . "/Tags.php" );

/**
 * Including the respective classes - User
*/
	require( CLASS_PATH . "/User.php" );

/**
 * Including the respective Geometry classes - Circle
*/
	require( CLASS_PATH . "/Circle.php" );

/**
 * Including the respective Geometry classes - Ward
*/
	require( CLASS_PATH . "/Ward.php" );

/**
 * Including the respective Geometry classes - Zone
*/
	require( CLASS_PATH . "/Zone.php" );
	 
/**
 * Function to globally handle exceptions
 *
 * @param	string		$exception	Throws an exception message	
*/
	function handleException( $exception ) {
	  echo "Sorry, a problem occurred. Please try later.";
	  error_log( $exception->getMessage() );
	}

	set_exception_handler( 'handleException' );
?>
