<?php
/**
 * editCategory.php is a template file which displays the edit category form
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
        <input type="hidden" name="categoryid" value="<?php echo $results['category']->id ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
            <label for="name">Category Name</label>
            <input style="width:100%;" type="text" name="name" id="name" placeholder="Name of the category" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['category']->name )?>" />

            <label for="description">Description</label>
            <textarea style="width:100%;height:200px;" name="description" id="description" placeholder="Brief description of the category" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['category']->description )?></textarea>
 
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
 
      </form>
 
		<?php } else header("Location: index.php");?>
	 	</div>		
	
	<div class="right-nav">
		<h1>Edit Category</h1>
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

