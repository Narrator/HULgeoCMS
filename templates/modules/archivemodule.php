	
	<div class="right-nav" style="margin-top:10px;">
	<h1> <?php if(isset($results['pageHeading'])) echo htmlspecialchars( $results['pageHeading'] ) ?> Archives</h1>
	<h5>
	
	<?php foreach ( $results['articles'] as $article ) { ?>
	
		<h3>
		<a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><span class="pubDate"><?php echo date($article->pub_date)?></a></span>
		</h3>
		
	<?php } ?>
	
	</h5>
	</div>
