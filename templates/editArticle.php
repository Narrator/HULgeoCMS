<?php
/**
 * editAricle.php is a template file which displays article editing form
 * when a new article is entered or when a current article is being edited.
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
	<?php if(isset($_SESSION['id'])) { ?>
      <h1><?php echo $results['pageTitle']?></h1>
 
      <form action="index.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 

 

            <label for="title">Article Title</label>
            <input style="width:100%;" type="text" name="title" id="title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->title )?>" />

 

            <label for="summary">Article Summary</label>
            <textarea style="width:100%; height:200px;" name="summary" id="summary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->summary )?></textarea>

            <label for="content">Article Content</label>
            <textarea style="width:100%; height:500px;" name="content" id="content" placeholder="The HTML content of the article" maxlength="100000" style="height: 30em;"><?php echo htmlspecialchars( $results['article']->content )?></textarea>
			<script type="text/javascript">
				CKEDITOR.replace('content');
			</script>
			<label for="tags">Tags</label>
			<input style="width:100%;" type="text" name="tags" id="tags" placeholder="Please enter values seperated by commas" maxlength="255" value="<?php if($results['article']->tagidarray){$tagstring=""; foreach($results['article']->tagidarray as $tag) { $tagstring .= $tag.","; } $tagtrimmed = mb_substr($tagstring, 0, -1); echo $tagtrimmed;}?>" />
            <label for="categoryid">Article Category</label>
            <select name="categoryid">
              <option value="0"<?php echo !$results['article']->categoryid ? " selected" : ""?>>(none)</option>
            <?php foreach ( $results['categories'] as $category ) { ?>
              <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['article']->categoryid ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
            <?php } ?>
            </select>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
 
      </form>
 
<?php if ( $results['article']->id ) { ?>
      <><a href="index.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Delete This Article?')">Delete This Article</a></p>
<?php } ?>
	<?php } else header("Location: index.php");?>
 	</div>		
	
	<div class="right-nav">
		<h1>Edit Article</h1>
		<h5>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. In augue odio, vehicula nec interdum eget, ultricies at quam. Morbi ullamcorper convallis placerat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum luctus lorem non urna dictum quis ornare ipsum aliquet. Curabitur imperdiet metus ut urna sollicitudin faucibus. Ut at lectus nec lacus lacinia gravida id nec nisi. In sed ligula sed erat tincidunt faucibus. Duis euismod eleifend ipsum, in consectetur ipsum lobortis sed. Donec placerat nisl quis mi euismod molestie. Pellentesque eu mauris diam, laoreet consectetur eros. Ut lacinia blandit lectus, eget lobortis lorem euismod eu.
		</h5>
	</div>
	</div>
<?php 

/**
 * Including the Gloabl footer
 */
	include 'include/footer.php'
?>
