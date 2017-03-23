<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 21/03/2017
 * Time: 20:30
 */
?>
<div class="">
	<div class="input-group">
		<input type="search" name="searchblog" id="searchblog" placeholder="Search blog" required=""
		       class="form-control"
		       value="<?php echo isset($_GET['searchb']) ? str_replace('-', ' ', $_GET['searchb']) : '' ?>">
		<div class="input-group-btn">
			<button type="button" id="search_blog_btn" class="btn btn-danger" aria-label="Left Align"
			        onclick="searchSite('blog')">
				<i class="fa fa-search" aria-hidden="true"></i>
			</button>
		</div>
	</div>
</div>
<div class="clearfix"></div><br>
<div class="panel panel-danger">
	<div class="panel-heading" style="background-color: #f53f1a; color: #fff;">
		Categories
	</div>
	<div class="panel-body" style="padding: 0;">
		<div class="list-group">
			<?php foreach ($categories as $category) { ?>
				<a href="blog.php?category=<?php echo $category['category_id']; ?>"
				   class="list-group-item"><?php echo $category['category_name']; ?><span
						class="pull-right">  (<?php if (isset($article_count[$category['category_name']])) echo $article_count[$category['category_name']]; else echo '0'; ?>
						)</span></a>
			<?php } ?>
		</div>
	</div>
</div>
<div class="well popular-article">
	<h3>popular posts</h3>
	<?php
	$articles = $article_obj->getAllArticles();
	if (!empty($articles)) {
		foreach ($articles as $article) {
			?>
			<div class="row">
				<div class="col-md-3 article-img">
					<a href="article.php?article=<?php echo $article['article_title']; ?>"><img
							src="<?php echo "images/articles/" . $article['article_id'] . "/" . $article['article_image']; ?>"
							alt="<?php echo $article['article_image']; ?>" class="img-responsive"></a>
				</div>
				<div class="col-md-9 article-content">
					<h5>
						<a href="article.php?article=<?php echo $article['article_title']; ?>"><?php echo $article['article_title']; ?></a>
					</h5>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php
		}
	}
	?>
</div>
