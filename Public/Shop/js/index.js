// JavaScript Document

var color=[];
$(function(){
	
	$("#div_main dl.dl_info").mouseover(function(){
		$(this).css('border','1px solid #FFB2CC');
		
	});
	
	$("#div_main dl.dl_info").mouseout(function(){
		//$(this).removeClass("select")
		$(this).css('border','1px solid #fff');
	});
	
	/*
	 * 主菜单鼠标移入事件
	 */
	$("#div_title ul li").mouseover(function(){
		//$(this).removeClass("select")
		$(this).addClass("selected");
	});
	
	/*
	 * 主菜单鼠标移出事件
	 */
	$("#div_title ul li").mouseout(function(){
		//$(this).removeClass("select")
		$(this).removeClass("selected");
	});
	
	
	/*
	 * 轮播器鼠标移入事件
	 */
	$("#div_lunbo  ul.ul_lunbo li").mouseover(function(){
		//$(this).removeClass("select")
		
		
		$(this).addClass("current").siblings().removeClass("current");
		i=$("#div_lunbo  ul.ul_lunbo li").index(this)-1;
		
		changeBgColor();
		window.clearInterval(timeLunbo);
	});
	
	/*
	 * 轮播器鼠标移出事件
	 */
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
	

	
	/**
	 * 获取背景色
	 */
	$("input[name='bgcolor']").each(function(i){
		if(i==0){
			$("#div_lunbo").css("background",$(this).val());
		}
		color.push($(this).val());
	})
	
	
})



function toUrl(url){
	//alert("跳到地址："+url);
	window.location.href=url;
}


/**
 * 改变背景色
 */
var i=0;
function changeBgColor(){
	i=i+1;
	if(i>3){
		i=0;
	}
	$("#div_lunbo  ul.ul_lunbo li").eq(i).addClass("current").siblings().removeClass("current");
	$("#div_lunbo").css('background',color[i]);	
    $("#div_lunbo .div_image .div_minlunbo:eq("+i+")").css("display","block").siblings().css("display","none");
	
	
}


/**
 * 查看福品详情
 * pid  福品id
 */
function toDetail(pid){
	//alert(pid);
	window.location.href="Index/spxq?id="+pid;
}

function overSku(font){
	$(font).addClass("sku_style");
}

function outSku(font){
	if($(font).attr("index")==0){
		$(font).removeClass("sku_style");
	}
}

function selectSku(font){
	$(font).siblings().removeClass("sku_style");
	$(font).siblings().attr("index",0);
	$(font).attr("index")==0?$(font).attr("index",1):$(font).attr("index",0);
	
	$(".sku_style").each(function(i,n){
		//alert($(n).html());
	})
}



/**
 *	轮播器 
 */
var timeLunbo=window.setInterval('changeBgColor()',3000);
 

function changePageTitle(url){
	window.location.href=url;
}
