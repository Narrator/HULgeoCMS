	
	<div class="right-nav" style="margin-top:10px;">
	<h1><?php if(isset($results['pageHeading'])) echo htmlspecialchars( $results['pageHeading'] ) ?> Tags</h1>
	<h5>
	
	<?php 
		foreach($results['tagidattributes'] as $tag){ 
			$articleidstring = $tag->articleids;
			$articletrimmed = trim($articleidstring,"{}");
			$articleidarray = explode(",",$articletrimmed);
			$tagcount = count($articleidarray);
	?>
	
	<a style="font-size:15px;margin-right:3px;" href=".?action=archive&amp;tagid=<?php echo $tag->id?>"><span class="pubDate"><?php echo $tag->tag?> (<?php echo $tagcount; ?>)</a></span>
	
	<?php }  ?>
	
	</h5>
	</div>
