<?php
require_once('include/init.php');
$error_messages = array();
$message = '';
$comment_obj = new comment();
if (isset($_POST['submit'])) {
	$comment_obj->setName($_POST['name']);
	$comment_obj->setEmail($_POST['email']);
	$comment_obj->setComment($_POST['comment']);
	$comment_obj->setArticleId($_POST['article_id']);
	$comment_obj->setCreateTime(date('Y-m-d h:i:s'));
	$comment_obj->setBlock(0);

	if (!$comment_obj->validateComment($_POST['mode'])) {
		$error_messages = $comment_obj->getValidateMessages();
	} else {
		if (!$comment_obj->insertComment()) $message = "An error occurred while publishing comment";
		else {
			$message = "Comment published successfully";
			$_POST['name'] = $_POST['email'] = $_POST['comment'] = '';
		}

	}
}
$article = array();
$article_obj = new article();
$articles = $article_obj->getAllArticles();
$article_count = $article_obj->getArticleCount();

$categories = array();
$category_obj = new category();
$categories = $category_obj->getAllCategories();
$comments = array();
if (isset($_GET['article'])) {
	$article_obj->setArticleTitle($_GET['article']);
	$article = $article_obj->getArticle();
	$article['comment_count'] = $article_obj->getCommentCount($article['article_id']);
	$comment_obj->setArticleId($article['article_id']);
	$comments = $comment_obj->getAllComments('allowed');
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/layout/pagehead.php'); ?>
	<script>
		$(document).ready(function () {
			<?php
			if(isset($_POST['comment'])) {
			if(!empty($error_messages)) {
			?>
			$('#form_div').focus();
			<?php
			}
			elseif($message == 'Comment published successfully') {
			?>
			$('#comments').focus();
			<?php
			}
			}
			?>
		})
	</script>
</head>

<body>
	<!--<div id="fb-root"></div>-->
	<!--<script>-->
	<!--	(function(d, s, id) {-->
	<!--		var js, fjs = d.getElementsByTagName(s)[0];-->
	<!--		if (d.getElementById(id)) return;-->
	<!--		js = d.createElement(s); js.id = id;-->
	<!--		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1628647227398439";-->
	<!--		fjs.parentNode.insertBefore(js, fjs);-->
	<!--	}(document, 'script', 'facebook-jssdk'));-->
	<!--</script>-->
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
				<div class="article">
					<div class="row ">
						<div class="col-md-10 article-title">
							<h4>
								<?php echo $article['article_title']; ?>
							</h4>
						</div>
						<div class="col-md-2"><span
								class="label label-primary pull-right"><?php echo $article['category_name']; ?></span>
						</div>
						<div class="col-md-12 article-info">
							<h6>
								<span>By Admin - </span>
								<span><?php echo date('h:s a', strtotime($article['create_time'])); ?></span>
								<a href="#comments"
								   class="comments"><?php echo $article['comment_count'] . ' comments'; ?></a>
							</h6>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 article-img">
							<a href="article.php?article=<?php echo $article['article_title']; ?>"><img
									src="<?php echo "images/articles/" . $article['article_id'] . "/" . $article['article_image']; ?>"
									alt="<?php echo $article['article_image']; ?>" class="img-responsive"></a>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 article-content">
							<?php echo $article['article_content']; ?>
						</div>
					</div>
					<div class="">
						<div class="col-md-12 share-container">
							<!--<i>SHARE</i>-->
							<!--<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>-->
							<span><a href="#" class="btn btn-sm btn-primary" style="padding: 3px 50px;"><i class="fa fa-facebook-f"></i> Share</a></span>
							<span><a href="#" class="btn btn-sm btn-info" style="padding: 3px 50px;"><i class="fa fa-twitter"></i></a></span>
							<span><a href="#" class="btn btn-sm btn-danger" style="padding: 3px 50px;"><i class="fa fa-google-plus"></i></a></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 well" id="comments">
							<h4><?php echo '[' . $article['comment_count'] . '] Comments'; ?></h4>
							<div>
								<?php
								if (!empty($comments)) {
									foreach ($comments as $comment) {
										?>
										<div class="strator">
											<h4><?php echo $comment['name']; ?></h4>
											<p class="ctime"><?php echo date('jS M, Y @ h:s a', strtotime($comment['create_time'])); ?></p>
											<p><?php echo $comment['comment']; ?></p>
										</div>
										<div class="clearfix"></div>

										<?php
									}
								}
								?>
							</div>
						</div>
					</div>
					<?php if ($article['allow_comments']) { ?>
					<div class="row">
						<div class="col-md-12 well" id="form_div">
							<form role="form" action="" method="post" id="comment_form" name="comment_form">

								<?php if ($message != '') echo '<div id="msg" class="alert alert-success">' . $message . '</div>'; ?>
								<?php if (isset($error_messages['article_id'])) echo '<div id="msg" class="alert alert-danger">' . $error_messages['article_id'] . '</div>'; ?>
								<fieldset>
									<legend>Leave your comment</legend>
									<div class="form-group row <?php if (isset($error_messages['name'])) echo ' has-error' ?>">
										<div class="col-md-2">
											<label class="">Name:<span class="text-danger">*</span></label>
										</div>
										<div class="col-md-10">
											<input name="name" id="name" type="text" placeholder="Your Name"
											       class="form-control"
											       value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
											<?php
											if (isset($error_messages['name']))
												echo '<div class="help-block">' . $error_messages['name'] . '</div>';
											?>
										</div>

									</div>

									<div
										class="form-group row<?php if (isset($error_messages['email'])) echo ' has-error' ?>">
										<div class="col-md-2">
											<label class="">Email:<span class="text-danger">*</span></label>
										</div>
										<div class="col-md-10">
											<input name="email" id="email" type="email"
											       placeholder="Your email (will not be published)" class="form-control"
											       value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
											<?php
											if (isset($error_messages['email']))
												echo '<div class="help-block">' . $error_messages['email'] . '</div>';
											?>
										</div>

									</div>

									<div
										class="form-group row <?php if (isset($error_messages['comment'])) echo ' has-error' ?>">
										<div class="col-md-2">
											<label>Comment:<span class="text-danger">*</span></label>
										</div>
										<div class="col-md-10">
											<textarea placeholder="Your Comment" class="form-control" name="comment"
											          id="comment"
											          rows="5"><?php if (isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
											<?php
											if (isset($error_messages['comment']))
												echo '<div class="help-block">' . $error_messages['comment'] . '</div>';
											?>
										</div>

									</div>

								</fieldset>
								<input type="hidden" name="article_id" id="article_id"
								       value="<?php echo $article['article_id']; ?>">
								<input type="hidden" name="mode" id="mode" value="new">
								<input type="submit" id="submit" name="submit" value="SUBMIT COMMENT"
								       class="btn btn-danger pull-right">
							</form>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<?php } ?>
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