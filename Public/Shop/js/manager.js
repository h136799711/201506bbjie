$(function(){
	$(".div_left a").click(function(){
		$(this).css("color","#f92d5e").siblings().css("color","#333");
		$(this).parent().siblings().find("a").css("color","#333");
		$(this).parent().parent().siblings().find("a").css("color","#333");
	})
	
	$(".div_top2 div").mouseover(function(){
		//$(this).css("background","#CA4756");
		$(this).addClass("selected");
	})
	
	$(".div_top2 div").mouseout(function(){
		$(this).removeClass("selected");
		//$(this).css("background","");
		//$(this).css("opacity",0.2);
	})
	/*
	$(".div_left a").mouseover(function(){
		$(this).css("color","#f92d5e");
	})
	
	$(".div_left a").mouseout(function(){
		$(this).css("color","#333");
	})*/
})


function toPage(url){
	//alert(url);
	$(".div_right").load(url);
}
