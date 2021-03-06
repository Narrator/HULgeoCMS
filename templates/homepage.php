<?php

/**
 * homepage.php is a template file which displays the default homepage for
 * HUL
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
		<a href=".?action=viewArticle&articleId=<?php echo $article->id?>"><?php echo htmlspecialchars( $article->title )?></a>
	  </h1>
	  <h2>
		<span>by <?php echo $results['useridattributes'][$article->id]->usr?></span>
		<span><?php echo date($article->pub_date)?></span>
		<?php if ( $article->categoryid ) { ?>
			<span>in <a href=".?action=archive&categoryid=<?php echo $article->categoryid?>"><?php echo htmlspecialchars( $results['categories'][$article->categoryid]->name )?></a></span>
		<?php } ?>
		<a href=".?action=viewArticle&articleId=<?php echo $article->id?>#comments"><span class="comments">Comments:<?php echo $results['commentRows'][$article->id] ?></span></a>
	  </h2>
	  <p><?php echo htmlspecialchars( $article->summary )?></p>
	  <h2> 
	       <span>Tags: <?php if($article->tagidarray){$tagstring=""; foreach($article->tagidarray as $tag) { $tagstring .= $tag." "; } $tagtrimmed = mb_substr($tagstring, 0, -1); echo $tagtrimmed;}?></span>
	  </h2>
		<?php if (isset($_SESSION['usr']) && $_SESSION['access_level']==1) { ?>
		   <div style="float:left;color:red;margin-right:10px;" onclick="location='index.php?action=editArticle&articleId=<?php echo $article->id?>'">Edit</div>
		   <div style="margin-left:10px;margin-bottom:5px;color:red;" onclick="location='index.php?action=deleteArticle&articleId=<?php echo $article->id ?>'">Delete</div>
		<?php } ?>

<?php $i++; if($i>5) break; } ?>
	</div>		
	
	<div class="right-nav">
		<h1>Welcome to HUL</h1>
		<h5>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. In augue odio, vehicula nec interdum eget, ultricies at quam. Morbi ullamcorper convallis placerat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum luctus lorem non urna dictum quis ornare ipsum aliquet. Curabitur imperdiet metus ut urna sollicitudin faucibus. Ut at lectus nec lacus lacinia gravida id nec nisi. In sed ligula sed erat tincidunt faucibus. Duis euismod eleifend ipsum, in consectetur ipsum lobortis sed. Donec placerat nisl quis mi euismod molestie. Pellentesque eu mauris diam, laoreet consectetur eros. Ut lacinia blandit lectus, eget lobortis lorem euismod eu.
		</h5>
	</div>

<?php 

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
?>
	</div>
<?php

/**
 * Including the Gloabl footer
 */

 include 'include/footer.php';

?>
