<?php
/**
 * viewArticle.php is a template file which displays a single article on the page
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
		<?php if ($_SESSION['usr'] && $_SESSION['access_level']==1) { ?>
		   <div style="float:left;color:red;margin-right:10px;" onclick="location='index.php?action=editArticle&amp;articleId=<?php echo $results['article']->id?>'">Edit</div>
		   <div style="margin-left:10px;margin-bottom:5px;color:red;" onclick="location='index.php?action=deleteArticle&amp;articleId=<?php $results['article']->id ?>'">Delete</div>
		<?php } ?>
      <h1 style="width: 100%;"><?php echo htmlspecialchars( $results['article']->title )?></h1>
      <div style="width: 100%; font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>
      <div style="width: 100%;"><?php echo $results['article']->content?></div>
		<span>by <?php echo $results['useridattributes']->usr?></span></br>
		<span>Last edit by <?php echo $results['lastidattributes']->usr?></span></br>
		<span> Last Edit date <?php echo date($results['article']->edit_date); ?></span>
        <p class="pubDate">Published on <?php echo date($results['article']->pub_date)?>
		<?php if ( $results['category'] ) { ?>
				in <a href="./?action=archive&amp;categoryid=<?php echo $results['category']->id?>"><?php echo htmlspecialchars( $results['category']->name ) ?></a>
		<?php } ?>
      </p>
	  <h1 style="width:100%;">Tags</h1>
	  <span><?php if($results['article']->tagidarray){$tagstring=""; foreach($results['article']->tagidarray as $tag) { $tagstring .= $tag." "; } $tagtrimmed = mb_substr($tagstring, 0, -1); echo $tagtrimmed;}?></span>
	  <?php if ($results['comments']) { ?>
		<div style="width:100%;">
			</br>
			<a name="comments"><h1 style="width:100%;">Comments</h1></a>
			<?php foreach ($results['comments'] as $comment) { ?>
				<h2>
				<span class="pubDate"><?php echo date($comment->pub_date)?></span>
				</h2>
				<a name="<?php echo $comment->id ?>"><p class="summary"><?php echo $comment->comment?></p></a>
			<?php } ?>
		</div>
	  <?php } ?>
	  <?php if ($_SESSION['usr']) { ?>
	  <div style="width:100%;">
		</br>
		<h1 style="width:100%;">Add comments</h1>
		<form action="index.php?action=addComment&amp;articleid=<?php echo $results['article']->id; ?>" method="post">
		    <label for="comment">Comment text area</label>
            <textarea style="width:100%; height:100px;" name="comment" id="comment" placeholder="Enter your comments for this article" required maxlength="1000" style="height: 5em;"></textarea>
			<div class="buttons">
			  <input type="submit" name="commentSubmitted" value="Submit Comment" />
			</div>
		</form>
	  </div>
	  <?php } ?>
	</div>		
	
	<div class="right-nav">
		<h1><?php echo htmlspecialchars( $results['category']->name ) ?></h1>
	<?php if ( $results['category'] ) { ?>
		  <h5 class="categoryDescription"><?php echo htmlspecialchars( $results['category']->description ) ?></h5>
	<?php } ?>
	</div>
	</div>
<?php
/**
 * Including the Gloabl footer
 */
	include 'include/footer.php'

?>
