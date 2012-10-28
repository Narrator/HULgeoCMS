	<div class="right-nav" style="margin-top:10px;">
	<h1><?php if(isset($results['pageHeading'])) echo htmlspecialchars( $results['pageHeading'] ) ?> Comments</h1>
	
	<h5>
	
	<?php $i=0; foreach ( $results['articles'] as $article ) { foreach($results['comments'][$article->id] as $comment) {?>
		
		<h2>
		<span><?php echo $results['commentuser'][$comment->id]->usr; ?> said on </span>
		<span><?php echo $comment->pub_date; ?></span>
		</h2>		  
		
		<h3>
		<a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>#<?php echo $comment->id?>"><span class="pubDate"><?php if(strlen($comment->comment)>200)echo substr($comment->comment,0,50); else echo $comment->comment?></a></span>
		</h3>
		
	<?php $i++; if($i>4) break; } if($i>4) break;  }?>
	</h5>
	</div>
