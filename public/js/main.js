$(function () {
	$(".scroll").click(function(event){
		event.preventDefault();
		$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
	});

	// $(".paw").toggle(
	// 	function(){
	// 		$('.header').height(440);
	// 	},
	// 	function(){
	// 		$('.header').height(177);
	// 	},
	// );

	$(".link-home img").hover(
		function(){
			var src = $(this).attr('src');
			$(this).attr('src', src.replace('home.png', 'home-hover.png'));
		},
		function(){
			var src = $(this).attr('src');
			$(this).attr('src', src.replace('home-hover.png', 'home.png'));
		}
	);

	$(".link-fotos img").hover(
		function(){
			var src = $(this).attr('src');
			$(this).attr('src', src.replace('fotos.png', 'fotos-hover.png'));
		},
		function(){
			var src = $(this).attr('src');
			$(this).attr('src', src.replace('fotos-hover.png', 'fotos.png'));
		}
	);

	$(".link-contato img").hover(
		function(){
			var src = $(this).attr('src');
			$(this).attr('src', src.replace('contato.png', 'contato-hover.png'));
		},
		function(){
			var src = $(this).attr('src');
			$(this).attr('src', src.replace('contato-hover.png', 'contato.png'));
		}
	);
});
