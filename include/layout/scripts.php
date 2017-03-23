<!-- Bootstrap Core JavaScript -->
<script src="assets/site/js/bootstrap.min.js"></script>

<!-- top-header and slider -->
<!-- here stars scrolling icon -->
<script type="text/javascript">
	$(document).ready(function () {
		/*
		 var defaults = {
		 containerID: 'toTop', // fading element id
		 containerHoverID: 'toTopHover', // fading element hover id
		 scrollSpeed: 1200,
		 easingType: 'linear'
		 };
		 */

		$().UItoTop({easingType: 'easeOutQuart'});

	});
</script>
<!-- //here ends scrolling icon -->
<script src="assets/site/js/minicart.min.js"></script>
<script>
	// Mini Cart
	paypal.minicart.render({
		action: '#'
	});

	if (~window.location.search.indexOf('reset=true')) {
		paypal.minicart.reset();
	}
</script>
<!-- main slider-banner -->
<script src="assets/site/js/skdslider.min.js"></script>
<link href="assets/site/css/skdslider.css" rel="stylesheet">
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('#demo1').skdslider({
			'delay'         : 5000,
			'animationSpeed': 2000,
			'showNextPrev'  : true,
			'showPlayButton': true,
			'autoSlide'     : true,
			'animationType' : 'fading'
		});

		jQuery('#responsive').change(function () {
			$('#responsive_wrapper').width(jQuery(this).val());
		});

	});
</script>
<!-- //main slider-banner -->
<script src="assets/site/js/jquery.blockui.js"></script>
<script>
	function searchSite(type) {
		var searchstring;
		if (type == 'product') {
			searchstring = $('#search').val();
		}
		else {
			searchstring = $('#searchblog').val();
		}

		searchstring = searchstring.trim().replace(/\s/g, '-');

		if (type == 'product') {
			location.href = 'products.php?search=' + searchstring;
		}
		else {
			location.href = 'blog.php?searchb=' + searchstring;
		}


	}
	$('#search').keypress(function (e) {
		var key = e.which;
		if (key == 13)  // the enter key code
		{
			$('#search_btn').click();
			return false;
		}
	});
	$('#searchblog').keypress(function (e) {
		var key = e.which;
		if (key == 13)  // the enter key code
		{
			$('#search_blog_btn').click();
			return false;
		}
	});
</script>