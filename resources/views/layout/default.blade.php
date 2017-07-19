<!DOCTYPE html>
<html>
	<head>
		@include('includes.head')
	</head>
	<body>
		<!-- header -->
		@include('includes.header')
		<!-- //header -->

		<!-- content -->
		<div class="content">
			<div class="col-md-3">
				<script>
					(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=162744593790875";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like-box" data-href="http://www.facebook.com/racaboxer" data-width="300px" data-show-faces="true" data-stream="true" data-header="false" style="background:#FFFFFF;margin:0px auto !important;"></div>
			</div>
			<div class="col-md-9 main">
				@yield('content')
			</div>
		</div>
		<!-- //content -->
		<div class="clearfix"> </div>
		<!-- footer -->
		@include('includes.footer')
		<!-- //footer -->
			
		<!-- Slide-To-Top JavaScript (No-Need-To-Change) -->
		<script type="text/javascript">
			$(document).ready(function() {			
				$().UItoTop({ easingType: 'easeOutQuart' });
				
			});
		</script>
		<a href="#" id="toTop"> </a>
		<!-- //Slide-To-Top JavaScript -->
		<!-- Bootstrap-Main --> 	<script src="js/bootstrap.min.js">		</script>
	</body>
</html>