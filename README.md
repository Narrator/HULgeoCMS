HULgeoCMS
=========
--------------
Hyderabad Urban Lab's Mapping platform allows users to access a map, on which information would be crowd-sourced.
The platform also has a very basic CMS running, along with the mapping platform, which allows users to browse through articles under various categories and to comment on them.

Technical Information
=========
--------------
The application uses/requires the following:
* PHP 5.3 http://php.net
* PostgreSQL 9.1 http://www.postgresql.org/
* PostGIS 1.5 (PostgreSQl extension) http://postgis.refractions.net/
* Tilestache http://tilestache.org/

Javascript Libraries
====================
* Leaflet http://leafletjs.com/ 
* Leaft draw https://github.com/jacobtoye/Leaflet.draw (Modified to add an undo feature)
* Jquery-UI dialog http://jqueryui.com/
* Jquery http://jquery.com/
* Jquery validation
* Jquery Fileupload http://blueimp.github.com/jQuery-File-Upload/ (For Image)
* Jquery Slider http://web-kreation.com/demos/Sliding_login_panel_jquery/
* Tilelayer.Bing.Js https://gist.github.com/1221998
* CKEditor http://ckeditor.com/

(Version numbers can be found in the source code)

Directory Listing
=================
* classes - contains all the PHP classes
* templates - contains HTML templates with in-line PHP (** viewMap.php is important )
* css - The website is being redesigned by Chandu chandu1987128@gmail.com
* js - globals.js,registered.js (Main javascript functions). Directory "pkgs" contains all external packages.
* server - contains server connection details for XHR calls made for storing geometry

Config.php
==========
Edit the database information in this file.

Documentation
=========
--------------
Visit: http://hyderabadurbanlab.com/docs for an auto-generated documentation from inline documentation

DocBlocks in the source code is your best bet to understanding the code better.