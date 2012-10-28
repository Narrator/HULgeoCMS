<?php
/**
 * archive.php is a template file which displays the archive format of articles
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
?>

<?php $i=0; foreach ( $results['articles'] as $article ) { ?>
 
		  <h1>
			<a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><?php echo htmlspecialchars( $article->title )?></a>
		  </h1>
          <h2>
			<span>by <?php echo $results['useridattributes'][$article->id]->usr?></span>
            <span class="pubDate"><?php echo date($article->pub_date)?></span>
			<?php if ( !$results['category'] && $article->categoryid ) { ?>
				<span class="category">in <a href=".?action=archive&amp;categoryid=<?php echo $article->categoryid?>"><?php echo htmlspecialchars( $results['categories'][$article->categoryid]->name ) ?></a></span>
			<?php } ?>  
				<a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>#comments"><span class="comments">Comments:<?php echo $results['commentRows'][$article->id] ?></span></a>
		  </h2>
          <p class="summary"><?php echo htmlspecialchars( $article->summary )?></p>
			<h2> 
	       <span>Tags: <?php if($article->tagidarray){$tagstring=""; foreach($article->tagidarray as $tag) { $tagstring .= $tag." "; } $tagtrimmed = mb_substr($tagstring, 0, -1); echo $tagtrimmed;}?></span>
		   </h2>
		   <?php if (isset($_SESSION['usr']) && $_SESSION['access_level']==1) { ?>
		   <div style="float:left;color:red;margin-right:10px;" onclick="location='index.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">Edit</div>
		   <div style="margin-left:10px;margin-bottom:5px;color:red;" onclick="location='index.php?action=deleteArticle&amp;articleId=<?php echo $article->id ?>'">Delete</div>
		   <?php } ?>

<?php $i++; if($i>5) break; } ?>
	</div>		
	
	<div class="right-nav">
		<h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?></h1>
	<?php if ( $results['category'] ) { ?>
		  <h5 class="categoryDescription"><?php echo htmlspecialchars( $results['category']->description ) ?></h5>
	<?php } ?>
	<?php if (isset($_SESSION['usr']) && $_SESSION['access_level']==1) { ?>
		<div style="float:left;color:red;margin-right:10px;" onclick="location='index.php?action=editCategory&amp;categoryid=<?php echo $results['category']->id?>'">Edit</div>
	<?php } ?>
	</div>
	
<?php 

	if(!$tagidget){
/**
 * Including the archive Module for Right-Nav
 */
		include 'modules/archivemodule.php'; 
/**
 * Including the commentModule for Right-Nav
 */
		include 'modules/commentmodule.php';
/**
 * Including the Tag Module for Right-Nav
 */
		include 'modules/tagmodule.php'; 
	}
?>
	</div>
<?php

/**
 * Including the Gloabl footer
 */

 include 'include/footer.php';

?>
	
