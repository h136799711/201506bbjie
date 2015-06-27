// JavaScript Document
$(function(){
	
	$("#div_main dl.dl_info").mouseover(function(){
		$(this).css('border','1px solid #FFB2CC');
		
	});
	
	$("#div_main dl.dl_info").mouseout(function(){
		//$(this).removeClass("select")
		$(this).css('border','1px solid #fff');
	});
	
	$("#div_title ul li").mouseover(function(){
		//$(this).removeClass("select")
		$(this).addClass("selected");
	});
	
	$("#div_title ul li").mouseout(function(){
		//$(this).removeClass("select")
		$(this).removeClass("selected");
	});
	
	$("#div_lunbo  ul.ul_lunbo li").mouseover(function(){
		//$(this).removeClass("select")
		
		
		$(this).addClass("current").siblings().removeClass("current");
		i=$("#div_lunbo  ul.ul_lunbo li").index(this)-1;
		
		changeBgColor();
		window.clearInterval(timeLunbo);
	});
	
	$("#div_lunbo ul.ul_lunbo li").mouseout(function(){
		
		timeLunbo=window.setInterval('changeBgColor()',3000);
	});
	
	
	$("#div_main .div_info .div_bzzx .div_bzzx_zzfw li").mouseover(function(){
		$(this).addClass("li_bzzx_over");
	});
	
	$("#div_main .div_info .div_bzzx .div_bzzx_zzfw li").mouseout(function(){
		$(this).removeClass("li_bzzx_over");
	});
		
		//alert($("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_bbjlc a"));
		
	$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_bbjlc font").mouseover(function(){
		var m=$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_bbjlc font").index($(this));
		for(var i=1; i<=m;i++){
			$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_bbjlc font").eq(i).addClass("font_bzjlc_hdzb");
		
		}
		for(var i=3; i>m;i--){
			$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_bbjlc font").eq(i).removeClass("font_bzjlc_hdzb");
		}
		$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_bbjlc_info div.div_bzzx_bbjlc_info_min").eq(m-1).show().siblings().hide();
	})
	
	$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_hdgc font").mouseover(function(){
		var m=$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_hdgc font").index($(this));
		for(var i=1; i<=m;i++){
			$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_hdgc font").eq(i).addClass("font_bzjlc_hdgc");
		
		}
		for(var i=5; i>m;i--){
			$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_hdgc font").eq(i).removeClass("font_bzjlc_hdgc");
		}
		$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_hdgc_info div.div_bzzx_hdgc_info_min").eq(m-1).show().siblings().hide();
	})
	
	
	/*$("#div_main .div_info .div_bzzx .div_bzzx_zzfw .div_bzzx_bbjlc font").mouseout(function(){
		$(this).removeClass("font_bzjlc_hdzb");
	})*/
	
})

var color=['#E1E4F5','#E34D35','#ECA76C','#00A0AA']
var i=0;
function changeBgColor(){
	i=i+1;
	if(i>3){
		i=0;
	}
	$("#div_lunbo  ul.ul_lunbo li").eq(i).addClass("current").siblings().removeClass("current");
	$("#div_lunbo").css('background',color[i]);	
    $("#div_lunbo .div_image .div_minlunbo:eq("+i+")").css("display","block").siblings().css("display","none")
	
	
}

 var timeLunbo=window.setInterval('changeBgColor()',3000);
 

function changePageTitle(url){
	window.location.href=url;
}