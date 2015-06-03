$(function(){
			$(".show_centl").hover(function(){
				$(".c1").css("height","100%");
				$(".c1").css("margin-top","-73.6%");
			},function(){
				$(".c1").css("height","12.8%");
				$(".c1").css("margin-top","-9.5%");
			})
			
			$(".show_cent2").hover(function(){
				$(".c2").css("height","100%");
				$(".c2").css("margin-top","-73.6%");
			},function(){
				$(".c2").css("height","25%");
				$(".c2").css("margin-top","-18.5%");
			})
			$(".show_cent3").hover(function(){
				$(".c3").css("height","100%");
				$(".c3").css("margin-top","-73.6%");
			},function(){
				$(".c3").css("height","25%");
				$(".c3").css("margin-top","-18.5%");
			})
			$(".show_cent4").hover(function(){
				$(".c4").css("height","100%");
				$(".c4").css("margin-top","-73.6%");
			},function(){
				$(".c4").css("height","25%");
				$(".c4").css("margin-top","-18.5%");
			})
			$(".show_cent5").hover(function(){
				$(".c5").css("height","100%");
				$(".c5").css("margin-top","-73.6%");
			},function(){
				$(".c5").css("height","25%");
				$(".c5").css("margin-top","-18.5%");
			})
			$(window).scroll(function(){
				$(".menu").css("margin-top","-10.8%");
				$(".menu").css("background-color","#FAFAFA");
				$(".menu").css("opacity","0.8");
				if ($(document).scrollTop() <= 50) {
                    $(".menu").css("margin-top","-8%");
                    $(".menu").css("background-color","#FFFFFF");
                    $(".menu").css("opacity","1");
                }
			
			})

			
})