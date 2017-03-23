<?php
require_once('include/init.php');
$artcle = array();
$article_obj = new article();
$filter = '';
if (isset($_GET['category'])) {
	$filter = 'category';
	$article_obj->setCategoryId($_GET['category']);
}
if (isset($_GET['searchb'])) {
	$filter = 'search';
	$article_obj->setSearchString(addslashes($_GET['searchb']));
}
$articles = $article_obj->getAllArticles($filter);
$article_count = $article_obj->getArticleCount();
$categories = array();
$category_obj = new category();
$categories = $category_obj->getAllCategories();

?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/layout/pagehead.php'); ?>
</head>

<body>
	<!-- header -->
	<?php include('include/layout/header.php'); ?>
	<!-- //header -->
	<!-- navigation -->
	<?php include('include/layout/nav.php'); ?>
	<!-- //navigation -->
	<!-- breadcrumbs -->
	<div class="breadcrumbs container">
		<div class="">
			<ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
				<li class="active">Blog</li>
			</ol>
		</div>
	</div>
	<!-- //breadcrumbs -->
	<!--- articles --->
	<div class="products container">
		<div class="" style="padding-top: 1em; ">
			<div class="col-md-8">
				<?php
				if (!empty($articles)) {
					foreach ($articles as $article) {
						?>
						<div class="article">
							<div class="row ">
								<div class="col-md-10 article-title">
									<h4>
										<a href="article.php?article=<?php echo $article['article_title']; ?>"><?php echo $article['article_title']; ?></a>
									</h4>
								</div>
								<div class="col-md-2"><span
										class="label label-primary pull-right"><?php echo $article['category_name']; ?></span>
								</div>
								<div class="col-md-12 article-info">
									<h6>
										<span>By Admin - </span>
										<span><?php echo date('h:s a', strtotime($article['create_time'])); ?></span>
										<a href="article.php?article=<?php echo $article['article_title']; ?>"
										   class="comments"><?php echo $article_obj->getCommentCount($article['article_id']) . ' comments'; ?></a>
									</h6>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3 article-img">
									<a href="article.php?article=<?php echo $article['article_title']; ?>"><img
											src="<?php echo "images/articles/" . $article['article_id'] . "/" . $article['article_image']; ?>"
											alt="<?php echo $article['article_image']; ?>" class="img-responsive"></a>
								</div>
								<div class="col-md-9 article-content">
									<?php
									if (strlen($article['article_content']) > 400) {
										echo substr($article['article_content'], 0, 400) . "...";
									} else {
										echo $article['article_content'];
									}
									?>
									<div class="row article-share">
										<div class="col-md-6 col-sm-6">
											<span><a
													href="article.php?article=<?php echo $article['article_title']; ?>">Continue
													reading >>></a></span>
										</div>
										<div class="col-md-6 col-sm-6">
											<div class="pull-right article-icons">
												<a class="btn btn-default"><i class="fa fa-facebook-official"></i></a>
												<a class="btn btn-default"><i
														class="fa fa-google-plus-official"></i></a>
												<a class="btn btn-default"><i class="fa fa-twitter"></i></a>
												<a class="btn btn-default"><i class="fa fa-instagram"></i></a>
											</div>

										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<?php
					}
				} else {
					?>
					<div class="tc-ch wow fadeInDown" data-wow-duration=".8s" data-wow-delay=".2s">
						<div class="col-md-12">
							<h4 class="alert alert-info">
								<?php
								echo isset($_GET['category']) ?
									'Oops! there are no articles under this category. ' : 'Sorry, we couldnt find what you are looking for. ' ?>
								<a href="blog.php">Continue to view all Articles</a></h4>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php
				}
				?>
			</div>
			<div class="col-md-4">
				<?php include('include/layout/blog_aside.php'); ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<!--- articles --->

	<!-- //footer -->
	<?php include('include/layout/footer.php'); ?>
	<!-- //footer -->

	<!-- //scripts -->
	<?php include('include/layout/scripts.php'); ?>
	<!-- //scripts -->
</body>
</html>