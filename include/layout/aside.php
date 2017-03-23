<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 25/02/2017
 * Time: 02:55
 */

?>


<h3>Popular Posts</h3>
<div class="agile_top_brands_grids">
	<?php
	if (!empty($articles)) {
		foreach ($articles as $article) {
			?>
			<div style="background-color: aliceblue;">
				<div class="tch-img col-md-3">
					<a href="article.php?article=<?php echo $article['article_title']; ?>"><img
							src="<?php echo "images/articles/" . $article['article_id'] . "/" . $article['article_image']; ?>"
							alt="<?php echo $article['article_image']; ?>" class="img-responsive"></a>

				</div>
				<div class="col-md-9">
					<h5>
						<a href="article.php?article=<?php echo $article['article_title']; ?>"><?php echo $article['article_title']; ?></a>
					</h5>
				</div>
			</div>
			<div class="clearfix"></div><br>
			<?php
		}
	}
	?>
</div>

