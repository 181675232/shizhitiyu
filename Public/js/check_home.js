//	$('form input[type=submit]').click(function(){
//		$.ajax({
//			type:'POST',
//			url:'user.php',
//			data:$('form').serialize(),
//			success:function(response,status,xhr){
//				alert(response);
//			}
//		});
//	});
//});

//判断是否是手机
            function browserRedirect() {  
                var sUserAgent = navigator.userAgent.toLowerCase();  
                var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";  
                var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";  
                var bIsMidp = sUserAgent.match(/midp/i) == "midp";  
                var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";  
                var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";  
                var bIsAndroid = sUserAgent.match(/android/i) == "android";  
                var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";  
                var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";  
                if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM){  
                	
                	window.location="http://www.pangbo.tv/wap"+window.location.pathname;
                    //$('.hd').css({'max-width':'150%','width':'163%'});
                    //$('.x_contain').css('width','168%');
                    //$('.foot').css('width','143%');
                   // $('.x_contain').width('1318px');
                    ///$('.foot').width('1318px');
            }
            else{
            
                   //alert('您用的是电脑');

            }  
            }
          browserRedirect();  

$(function(){
    //执行瀑布流
    var $container = $('#container');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	var loading = $("#loading").data("on", false);
	$(window).scroll(function(){
		if(loading.data("on")) return;
		if($(document).scrollTop() > 
			$(document).height()-$(window).height()-$('.footer').height()){
			//加载更多数据
			loading.data("on", true).fadeIn();

			$.get(
				"/Public/newsMore/", 
				{"page" : $("#page").html(),
					"tag" : $("#tag").html(),
					"groupid" : $("#groupid").html(),
				},
				function(data){
					if(data==1){
						$(".loading").html("没有更多数据......　　");
					}else{
						var html = "";
						
						
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){
									html += "<li class=\"item\" >";
									html +="<a href=\"/common/news/id/"+data[i]['id']+"\">";
									html += "<img src=\""+data[i]['simg']+"\" height=\"147\" width=\"381\"  style=\"width:381px;height:147px;\">";
									html +="</a>";
									html +="<a href=\"/common/news/id/"+data[i]['id']+"\" class=\"b_lsxq_r_lvshi_p1 clearfix\"> "+data[i]['title']+"</a>";
									html +="<a href=\"/common/news/id/"+data[i]['id']+"\" class=\"b_lsxq_r_lvshi_p2 color_7\">"+data[i]['introduction']+"</a>";	                            
									html +="<div class=\"l_fxpl clearfix right\" style=\'margin-right: 84px;width: 278px;height: 10px; margin-top: -6px;\'>";
									html +="<p class=\"clearfix left\">";
									html +="	<span class=\"l_span_k left f_corfff\">";
									if(data[i]['title_short']){
										html +=""+data[i]['title_short']+"";
									}else{
										html +=""+data[i]['tag_title'].substring(0, 1)+"";
									}
									html +="</span>";
									html +="<span class=\"left f_core9b55f\">"+data[i]['tag_title']+"</span>";
									html +="</p>";
									html +="<ul class=\"l_ul l_ul_cur clearfix right\">";
									html +="<li class=\"position_re\">";
									html +="	<a href=\"/common/news/id/"+data[i]['id']+"\" class=\"f_corc1c1c1\">";
									html +="		<img src=\"/Public/images/l_tb_pl.png\"  class=\"position_ab\">";
									html +="		<img src=\"/Public/images/l_tb_pl_.png\" >";
									html +="("+data[i]['count']+")";
									html +="	</a>";
									html +="	</li>";
									html +="	<li class=\"position_re\">";
									html +="<a  class=\"f_corc1c1c1\">";
									html +="<img src=\"/Public/images/l_tb_fh.png\"  class=\"position_ab\">";
									html +="<img src=\"/Public/images/l_tb_fh_.png\" >	";
									html +="	("+data[i]['share']+")";
									html +="</a>";
									html +="<!--分享  -->";
									html +="<p class=\"clearfix position_ab\" style=\"display:bake\">";
									html +=" <a title=\"分享到微信\"           class=\" l_wx left bor_l \"  onclick=\"showToweixin('"+data[i]['id']+"')\" href=\"javascript:;\" ></a>";
									html +=" 	<a title=\"分享到新浪微博\"    class=\"l_xl left bor_l \" onclick=\"showToSina('"+data[i]['id']+"','"+data[i]['title']+"','http://www.pangbo.tv"+data[i]['simg']+"','http://www.pangbo.tv')\" href=\"javascript:;\"></a>";
									html +="	   <a title=\"分享到腾讯微博\" class=\"l_tx left bor_l\" onclick=\"showToTencent('"+data[i]['id']+"','"+data[i]['title']+"','http://www.pangbo.tv"+data[i]['simg']+"','http://www.pangbo.tv')\" href=\"javascript:;\"></a>";
									html +="		</p>";
									html +="<!--分享  -->";
									html +="</li>";
									html +="<li class=\"position_re\">";
									html +="	<a class=\"f_corc1c1c1\" onclick=\"return upper('news',"+data[i]['id']+")\">";
									html +="		<img src=\"/Public/images/l_tb_dz.png\"  class=\"position_ab\">";
									html +="		<img src=\"/Public/images/l_tb_dz_.png\" >";
									html +="	(<span>"+data[i]['upper']+"</span>)";
									html +="</a>";
									html +="	</li>";
									if(data[i]['is_coll']==1){
										html +="	<li class=\"position_re\">";
										html +="			<a class=\"f_corc1c1c1\" >";
										html +="			<img src=\"/Public/images/l_tb_xx.png\" >";
										html +="			(<span>"+data[i]['clollection']+"</span>)";
										html +="		</a>";
										html +="		</li>";
										}else{
										html +="		<li class=\"position_re\">";
										html +="			<a class=\"f_corc1c1c1\" onclick=\"return clollection("+data[i]['id']+")\">";
										html +="				<img src=\"/Public/images/l_tb_xx.png\" class=\"position_ab\">";
										html +="			<img src=\"/Public/images/l_tb_xx_.png\" >";
										html +="			(<span>"+data[i]['clollection']+"</span>)";
										html +="		</a>";
										html +="	</li>";
										}
									
									html +="	</ul>";
									html +="</div>";
		                            
		                            
									html += "</li>";
								}
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
		}
	});
});


function order(){
    //执行瀑布流
    var $container = $('#container');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	  $('.b_zx_r_jl > li').css('position','static!important');
			$.get(
				"/Member/order_ajax/", 
				{"page" : $("#page").html(),
				},
				function(data){
					if(data==1){
						$("#order").css('display','none');
					}else{
						var html = "";
						
						
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){
									html += "<li class=\"item\" >";
									html += "<div class=\"b_zx_r_wd_r_d\">";
									if(data[i]['img']==""){
										html += "<span class=\"b_zx_r_wd_r1\"></span>";
									}else{
										 html +="<img src=\""+data[i]['img']+"\"  class=\"b_zx_r_wd_r1\">";
									}
									
									html += "<span class=\"b_zx_r_wd_r2 font\">"+data[i]['title']+"</span>";
									html += "<span class=\"b_zx_r_wd_r3\">"+data[i]['description']+"</span>";
									html += "<span class=\"b_zx_r_wd_r4\">￥"+data[i]['price']+"</span>";
									html += "<span class=\"b_zx_r_wd_r5\"><span class=\"font_14 color_c\">"+data[i]['addtime']+"</span><br>";
									
									if(data[i]['state']==2 ){
									html +="已支付 ";
									}else{
									html +="未支付 ";
									}
									if(data[i]['state']==2 && data[i]['type']==1){
										html += "<em class=\"font_14 color_7\">查看详情</em> </span>";
									}
									
									if(data[i]['state']==2 && data[i]['type']==1){
										html += "<div class=\"clearfix\">";
										html += "<div class=\"b_zx_r_wd_r_ul_d left\">";
										html += "<ul class=\"b_zx_r_wd_r_ul\">";
										html += "<li class=\"clearfix\">";
										html += "<div class=\"left b_zx_info_l b_zf_info_ll1\"></div>";
										html += "<div class=\"left b_b_zx_info_r\"><h2> "+data[i]['title']+"<span >（"+data[i]['description']+"）</span></h2></div></li>";
										html += "<li class=\"clearfix\"><div class=\"left b_zx_info_l b_zf_info_ll2\"></div>";
										html += "<div class=\"left b_b_zx_info_r\"><span >"+data[i]['tag']+"</span></div></li>";
										html += "<li class=\"clearfix\"><div class=\"left b_zx_info_l b_zf_info_ll3\"></div>";
										html += "<div class=\"left b_b_zx_info_r\"><span >"+data[i]['phone']+"</span></div></li>";
										html += "<li class=\"clearfix\"><div class=\"left b_zx_info_l b_zf_info_ll4\"></div>";
										html += "<div class=\"left b_b_zx_info_r \"><span >暂无</span></div></li>";
										html += "<li class=\"clearfix\"><div class=\"left b_zx_info_l b_zf_info_ll5\"></div>";
										html += "<div class=\"left b_b_zx_info_r b_zf_info_r_no_border\"><span >暂无</span></div></li>";
										html += "<li class=\"clearfix\" style=\" height: 15px;\"> <!--<div class=\"right\"><span class=\" b_zf_fasong\">点击发送邮件</span></div>--></li></ul></div>";
										html += "<div class=\"left b_zx_fangdajing\">";
										html += "<img src=\"/Public/images/fangdajing.png\" height=\"20\" width=\"20\" ><span>放大查看</span>";
										html += "</div>";
										html += "</div>";
									}
									html += "</div>";
									html += "</li>";
								}
								console.log(html);
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
									$('.b_zx_r_jl > li').css('position','static');
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
}

function collection_ajax(){
    //执行瀑布流
    var $container = $('#container1');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	  $('.b_zx_r_jl > li').css('position','static!important');
			$.get(
				"/Member/collection_ajax/", 
				{"page" : $("#page").html(),
				},
				function(data){
					if(data==1){
						$("#collection").css('display','none');
					}else{
						var html = "";
						
						
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){	
									html += " <li class=\"clearfix item\">";
									html += " <a href=\"/common/news/id/"+data[i]['newid']+"\" style=\"color:#000\">";
									html += "<img src=\"/Public/images/sc_1.jpg\" height=\"54\" width=\"140\"  class=\"left\">";
									html += "<p class=\"b_zx_r_sc_p1 left\">"+data[i]['title']+"</p>";
									html += "<p class=\"b_zx_r_sc_p2 left\">"+data[i]['addtime']+"</p>";
									html += "</a>";
									html += " <p class=\"b_zx_r_sc_p3 left\" onclick=\"return mdelete("+data[i]['id']+",'clollection')\">删除</p>";
									html += "</li>";
								}
								console.log(html);
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
									$('.b_zx_r_jl > li').css('position','static');
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
}


//评论ajax加载
function comment_ajax(){
    var $container = $('#container1');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	  //$('.b_zx_r_pl > li').css('position','static!important');
			$.get(
				"/Member/comment_ajax/", 
				{"page" : $("#page").html(),
				},
				function(data){
					if(data==1){
						$("#comment").css('display','none');
					}else{
						var html = "";
						
						
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){	
									html += " <li class=\"item\">";
									html += "<div class=\"b_zx_r_pl_top clearfix\">";
									html += "<div class=\"left\">我的评论</div>";
									html += "<div class=\"b_zx_r_pl_top_r left\">"+data[i]['description']+"</div>";
									html += "</div>";
									html += "<div class=\"b_zx_r_pl_bt clearfix\">";
									html += "<div class=\"left\">评论文章</div>";
									html += "<div class=\"b_zx_r_pl_bt_l2 left\">"+data[i]['title']+"</div>";
									html += "<div class=\"b_zx_r_pl_bt_l3 left\">"+data[i]['addtime']+"</div>";
									html += "</div>";
									html += "<p class=\"b_zx_r_pl_del\" onclick=\"return mdelete("+data[i]['id']+",'clollection')\">删除</p>";
									html += "</li>";
								}
								console.log(html);
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
									$('.b_zx_r_pl>li').css('position','static');
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
}

//已发表ajax加载
function publish_ajax(){
    var $container = $('#container1');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	  //$('.b_zx_r_tg_content_r2  ul  li').css('position','static!important');
			$.get(
				"/Member/publish_ajax/", 
				{"page" : $("#page").html(),
				},
				function(data){
					if(data==1){
						$("#publish").css('display','none');
					}else{
						var html = "";
												
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){	
									html += " <li class=\"item\">";
									html += "<div class=\"left b_zx_r_tg_r2_l\">";
									html += "<img src=\"/Public/images/zx_yifabiao_1.jpg\"  width=\"157\">";
									html += "</div>";
									html += "<div class=\"left b_zx_r_tg_r2_r\">";
									html += "<p class=\"b_zx_r_tg_p1\">"+data[i]['title']+"</p>";
									html += "<p class=\"b_zx_r_tg_p2 clearfix\">";
									html += "<span>"+data[i]['content']+"</span>";
									html += "<span class=\"b_zx_r_tg_p3\">"+data[i]['addtime']+"</span>";
									html += "</p>";
									html += "</div>";
									html += "</li>";
								}
								console.log(html);
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
									$('.b_zx_r_pl>li').css('position','static');
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
}




//审核中ajax加载
function review_ajax(){
    var $container = $('#container1');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	  //$('.b_zx_r_tg_content_r2  ul  li').css('position','static!important');
			$.get(
				"/Member/review_ajax/", 
				{"page" : $("#page").html(),
				},
				function(data){
					if(data==1){
						$("#review").css('display','none');
					}else{
						var html = "";
												
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){	
									html += " <li class=\"item\">";
									html += "<div class=\"left b_zx_r_tg_r2_l\">";
									html += "<img src=\"/Public/images/zx_yifabiao_1.jpg\"  width=\"157\">";
									html += "</div>";
									html += "<div class=\"left b_zx_r_tg_r2_r\">";
									html += "<p class=\"b_zx_r_tg_p1\">"+data[i]['title']+"</p>";
									html += "<p class=\"b_zx_r_tg_p2 clearfix\">";
									html += "<span>"+data[i]['content']+"</span>";
									html += "<span class=\"b_zx_r_tg_p3\">"+data[i]['addtime']+"</span>";
									html += "</p>";
									html += "</div>";
									html += "</li>";
								}
								console.log(html);
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
									$('.b_zx_r_pl>li').css('position','static');
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
}

//退稿ajax加载
function rejection_ajax(){
    var $container = $('#container1');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	  //$('.b_zx_r_tg_content_r2  ul  li').css('position','static!important');
			$.get(
				"/Member/rejection_ajax/", 
				{"page" : $("#page").html(),
				},
				function(data){
				
					if(data==1){
						$("#rejection").css('display','none');
					}else{
						var html = "";
												
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){	
									html += "<li class=\"item\">";
									html += "<div class=\"left b_zx_r_tg_r2_l\">";
									html += "<img src=\"/public/images/zx_yifabiao_1.jpg\"  width=\"157\">";
									html += "</div>";
									html += "<div class=\"left b_zx_r_tg_r2_r\">";
									html += "<p class=\"b_zx_r_tg_p1\">";
									html += ""+data[i]['title']+"";
									html += "<span class=\"b_zx_r_tg_bj\">编辑<em>></em></span>";
									html += "<span class=\"b_zx_r_tg_bj_d\">";
									html += " <span class=\"b_zx_r_tg_bj_checked\">编辑文章</span>";
									html += " <span>删除文章</span>";
									html += "</span>";
									html += "</p>";
									html += "<p class=\"b_zx_r_tg_p2 clearfix\">";
									html += " <span> "+data[i]['content']+"";
									html += "</span>";
									html += "<span class=\"b_zx_r_tg_p3\">";
									html += " 时间";
									html += "</span>";
									html += "</p>";
									html += "</div>";
									html += "<div class=\"left b_zx_r_tg_r3_bt\">";
									for (k in data[i]['reason']){
										html += "<p class=\"left\">"+data[i]['reason'][k]+"</p>";
									}
									html += "</div>";
									html += "</li>";
								}
								console.log(html);
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
									$('.b_zx_r_pl>li').css('position','static');
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
}


//草稿箱ajax加载
function draft_ajax(){
    var $container = $('#container1');
	  $container.masonry({
	    itemSelector : '.item',
	    isAnimated: true
	  });
	  //$('.b_zx_r_tg_content_r2  ul  li').css('position','static!important');
			$.get(
				"/Member/draft_ajax/", 
				{"page" : $("#page").html(),
				},
				function(data){
				
					if(data==1){
						$("#draft").css('display','none');
					}else{
						var html = "";
												
						if($.isArray(data)){
								//把原来值加1
								var page_index=parseInt($('#page').html());
								$('#page').html(page_index+1);
								for(i in data){	
									html += "<li class=\"clearfix  item\">";
									html += "<div class=\"left b_zx_r_tg_r2_l\">";
						/*			html += "<img src=\"/public/images/zx_yifabiao_1.jpg\"  width=\"157\">";*/
									html += "</div>";
									html += "<div class=\"left b_zx_r_tg_r2_r\">";
									html += "<p class=\"b_zx_r_tg_p1\">";
									html += ""+data[i]['title']+"";
									html += "<span class=\"b_zx_r_tg_bj\">编辑<em>></em></span>";
									html += "<span class=\"b_zx_r_tg_bj_d\">";
									html += " <span class=\"b_zx_r_tg_bj_checked\">编辑文章</span>";
									html += " <span  onclick=\"return mdelete("+data[i]['id']+",'news')\">删除文章</span>";
									html += "</span>";
									html += "</p>";
									html += "<p class=\"b_zx_r_tg_p2 clearfix\">";
									html += " <span> "+data[i]['content']+"";
									html += "</span>";
									html += "<span class=\"b_zx_r_tg_p3\">";
									html += " "+data[i]['addtime']+"";
									html += "</span>";
									html += "</p>";
									html += "</div>";
									html += "</li>";
								}
								console.log(html);
								var $newElems = $(html).css({ opacity: 0 }).appendTo($container);
								$newElems.imagesLoaded(function(){
									$newElems.animate({ opacity: 1 });
									$container.masonry( 'appended', $newElems, true ); 
									$('.b_zx_r_pl>li').css('position','static');
						        });
						        loading.data("on", false);
							}
					loading.fadeOut();
					}
				},
				"json"
			);
}

//个人提交
function manager(){
	if($("#username").val()=="请输入个人昵称"){
		alert("请输入个人昵称！");
		return false;
	}
	if($("#username").val()==""){
		alert("请输入个人昵称！");
		return false;
	}
	if($("#password").val().length<6){
		alert("请输入6位以上密码！");
		return false;
	}
	if($("#code").val()=="请输入图片验证码"){
		alert("请输入图片验证码！");
		return false;
	}
	if($("#phone").val()=="请输入您的手机号码"){
		alert("请输手机号！");
		return false;
	}
	if($("#phone_code").val()=="请输入短信验证码"){
		alert("请输入短信验证码！");
		return false;
	}
	if($("#name").val()=="您的姓名"){
		alert("请输您的姓名！");
		return false;
	}
	if(!$('#btnSubmit').siblings('.b_zc_yiyuedu').find('span').hasClass('b_zc_yiyuedu_check')){
		alert("请同意磅礴新闻客户协议！");
		return false;
	}
/*	if($("#hid").val()==0){
		$.get("/Public/phone_code?phone="+$("#phone").val(),function(data){
			if(data==2){
				$("#hid").val(2);
			}else if(data==3){
				$("#hid").val(3);	 
			}else if(data==4){
				$("#hid").val(4);	 
			}else if(data==5){
				$("#hid").val(5);	
			}else if(data==1){
				$("#hid").val(1);	
			}
		});
	}
	alert($("#hid").val())
	return false;
	if($("#hid").val()==2){
		alert("验证码超时！");
		return false;
	}
	if($("#hid").val()==3){
		alert("手机验证码错误！");
		return false;
	}
	if($("#hid").val()==4){
		alert("手机号错误！");
		return false;
	}
	if($("#hid").val()==5){
		alert("手机号为空！");
		return false;
	}
	if($("#hid").val()==1){
		alert("手机号为空！");
		return true;
	}*/
	
}

//个人发送验证码
function getSecond(){
	if($("#code").val()=="请输入图片验证码"){
		alert("请输入图片验证码");
		$('#txtCode').attr("src","/Public/scode/"+Math.random());
		exit;
	}else{
		$.ajax({
			type:'POST',
			url:'/Public/code_ajax',
			data: {'code':$("#code").val()},
			async:false,
			success:function(response){
				if(response == 1){
					test = 1;
				}else{		
					//$('#msgtip').html('<span style="color:red">'+response+'</span>');
					$('#txtCode').attr("src","/home/public/scode/"+Math.random());
					exit;
				}
			},
		});
	}
	if($("#phone").val()==""){
		alert("请填写正确手机号！");
		$('#txtCode').attr("src","/home/public/scode/"+Math.random());
		exit;
		//$("#mobile_notice").html("请填写符合要求的手机号码在发送！");
	}else{
		
	var a=/^1[3|5|7|8|][0-9]{9}$/; 
		var b=a.test($("#phone").val());
		if(!b){
			alert("手机号码不正确！");
			//$("#mobile_notice").html("手机号码不正确！");
			$('#txtCode').attr("src","/Public/scode/"+Math.random());
			exit;
			}
		$.get("/Public/code/phone/"+$("#phone").val(),function(data){
			if(data == 2){
				alert('手机号不合法！');
				exit;
			}
			if(data == 3){
				alert('手机号已注册！');
				exit;
			}
			if(data == 1){
				alert('手机验证码已发送！');
				add();
			}
		});
	}
}
var timerc=60; //全局时间变量（秒数）
function add(){ //加时函数 
	  if(timerc >0){ //如果不到5分钟
		  timerc--; //时间变量自增1
		  $("#div2").val(Number(parseInt(timerc%60/10)).toString()+(timerc%10)+"秒后重新获取");
		  $("#div2").show();
		  $("#div1").hide();
		  setTimeout("add()", 1000); //设置1000毫秒以后执行一次本函数
	  }else{
		  timerc=60;
		  $("#div2").hide();
		  $("#div1").show();
	  };
	};

	//企业提交
	function manager1(){
		if($("#username1").val()=="请输入企业昵称（5字以内）"){
			alert("请输入企业昵称！");
			return false;
		}
		if($("#username1").val().length>5){
			alert("企业昵称需要在5个字以内！");
			return false;
		}
		if($("#password1").val().length<6){
			alert("请输入6位以上密码！");
			return false;
		}
		if($("#code1").val()=="请输入图片验证码"){
			alert("请输入图片验证码！");
			return false;
		}
		if($("#phone1").val()=="请输入管理员的手机号码"){
			alert("请输手机号！");
			return false;
		}
		if($("#phone_code1").val()=="请输入短信验证码"){
			alert("请输入短信验证码！");
			return false;
		}
		if($("#name1").val()=="请填写企业全称（须与组织机构代码名称一致）"){
			alert("请填写企业全称！");
			return false;
		}
		if(!$('#btnSubmit1').siblings('.b_zc_yiyuedu').find('span').hasClass('b_zc_yiyuedu_check')){
			alert("请同意磅礴新闻客户协议！");
			return false;
		}
	/*	if($("#hid").val()==0){
			$.get("/Public/phone_code?phone="+$("#phone").val(),function(data){
				if(data==2){
					$("#hid").val(2);
				}else if(data==3){
					$("#hid").val(3);	 
				}else if(data==4){
					$("#hid").val(4);	 
				}else if(data==5){
					$("#hid").val(5);	
				}else if(data==1){
					$("#hid").val(1);	
				}
			});
		}
		alert($("#hid").val())
		return false;
		if($("#hid").val()==2){
			alert("验证码超时！");
			return false;
		}
		if($("#hid").val()==3){
			alert("手机验证码错误！");
			return false;
		}
		if($("#hid").val()==4){
			alert("手机号错误！");
			return false;
		}
		if($("#hid").val()==5){
			alert("手机号为空！");
			return false;
		}
		if($("#hid").val()==1){
			alert("手机号为空！");
			return true;
		}*/
		
	}
	//企业手机验证
	function getSecond1(){
		if($("#code1").val()=="请输入图片验证码"){
			alert("请输入图片验证码");
			$('#txtCode1').attr("src","/Public/scode/"+Math.random());
			exit;
		}else{
			$.ajax({
				type:'POST',
				url:'/Public/code_ajax',
				data: {'code':$("#code1").val()},
				async:false,
				success:function(response){
					if(response == 1){
						test = 1;
					}else{
						alert(response);
						//$('#msgtip').html('<span style="color:red">'+response+'</span>');
						$('#txtCode1').attr("src","/Public/scode/"+Math.random());
						exit;
					}
				},
			});
		}
		if($("#phone1").val()==""){
			alert("请填写正确手机号！");
			$('#txtCode1').attr("src","/Public/scode/"+Math.random());
			exit;
			//$("#mobile_notice").html("请填写符合要求的手机号码在发送！");
		}else{
			
		var a=/^1[3|5|7|8|][0-9]{9}$/; 
			var b=a.test($("#phone1").val());
			if(!b){
				alert("手机号码不正确！");
				//$("#mobile_notice").html("手机号码不正确！");
				$('#txtCode1').attr("src","/Public/scode/"+Math.random());
				exit;
				}
			$.get("/Public/code/phone/"+$("#phone1").val(),function(data){
				if(data == 2){
					alert('手机号不合法！');
					exit;
				}
				if(data == 3){
					alert('手机号已注册！');
					exit;
				}
				if(data == 1){
					alert('手机验证码已发送！');
					add1();
				}
			});
		}
	}
	var timerc1=60; //全局时间变量（秒数）
	function add1(){ //加时函数 
		  if(timerc1 >0){ //如果不到5分钟
			  timerc1--; //时间变量自增1
			  $("#div4").val(Number(parseInt(timerc1%60/10)).toString()+(timerc1%10)+"秒后重新获取");
			  $("#div4").show();
			  $("#div3").hide();
			  setTimeout("add1()", 1000); //设置1000毫秒以后执行一次本函数
		  }else{
			  timerc1=60;
			  $("#div4").hide();
			  $("#div3").show();
		  };
		};
		
		
		function login(){
			if($("#phone3").val()=="请输入手机号码"){
				alert("请输入手机号！");
				return false;
			}
			if($("#password3").val()==""){
				alert("请输入密码！");
				return false;
			}	
		}

		//评论
		function review(newid){
			if(!newid){
				alert("发布失败");
				return false;
			}else if($("#description").val().length<5){
				$("#review_warning").show();
				return false;
			}else{
				$.ajax({
					type:'POST',
					url:'/Common/comment/',
					data: {'description':$("#description").val(),'newid':newid},
					async:false,
					success:function(response){
						if(response == 1){
							alert("请输入评论！");
						}else if(response == 2){		
							alert("发布成功！");
							$("#description").val('发表评论');
							$("#description").css('color','#ccc');
							location.reload();
						}else if(response == 3){
							alert("发布失败！");
						}else if(response == 4){
							alert("未登录，通过审核后显示");
							$("#description").val('发表评论');
							$("#description").css('color','#ccc');
							location.reload();
						}
					},
				});
			}		
		}
		
		//回复
		function reply(newid,pid){
			if(!newid){
				alert("发布失败");
				return false;
			}else if(!pid){
				alert("发布失败");
				return false;
			}else if($("#description"+pid).val()==""){
				alert("请输入回复内容");
				return false;
			}else{
				$.ajax({
					type:'POST',
					url:'/Common/reply/',
					data: {
						'description':$("#description"+pid).val(),
						'newid':newid,
						'pid':pid
						},
					async:false,
					success:function(response){
						if(response == 1){
							alert("请输入回复！");
						}else if(response == 2){		
							alert("发布成功！");
							$("#description"+pid).val('');
							location.reload();
						}else if(response == 3){
							alert("发布失败！");
						}else if(response == 4){
							alert("未登录，通过审核后显示");
						}else{
							alert('发布失败');
						}
					},
				});
			}
		}
		
		//二级回复
		function reply_r(newid,pid,rid){
			if(!newid){
				alert("发布失败");
				return false;
			}else if(!rid){
				alert("发布失败");
				return false;
			}else if($("#reply_r"+rid).val()==""){
				alert("请输入回复内容");
				return false;
			}else{
				$.ajax({
					type:'POST',
					url:'/Common/reply_r/',
					data: {
						'description':$("#reply_r"+rid).val(),
						'newid':newid,
						'pid':pid,
						'rid':rid
						},
					async:false,
					success:function(response){
						if(response == 1){
							alert("请输入回复！");
						}else if(response == 2){		
							alert("发布成功！");
							$("#reply_r"+rid).val('');
							location.reload();
						}else if(response == 3){
							alert("发布失败！");
						}else if(response == 4){
							alert("未登录，通过审核后显示");
						}else{
							alert('发布失败');
						}
					},
				});
			}	
		}
		var zan_i=0;
		
		function upper(type,id){
			if(zan_i==0)
				{
			$.ajax({
				type:'POST',
				url:'/Common/upper/',
				data: {
					'type':type,
					'id':id
					},
				async:false,
				success:function(response){
					if(response == 1){
						
				$('.l_fxpl li').click(function(){
					console.log(zan_i);
							var t=$(this).find('span').text();
							console.log(t);
							if(!$(this).hasClass('b_index_zan'))
								{
									$(this).find('span').text(parseInt(t)+1);
							$(this).find('img:nth-child(2)').addClass('position_ab');
							$(this).find('img:first-child').removeClass('position_ab');
							$(this).addClass('b_index_zan');
								}
						
						})
						
							zan_i=zan_i+1;
						

							
					}else{
						alert('点赞失败');
					}
				},
			});
		}
		}
		
		var shoucang_i=0;
		function clollection(id){
			if(shoucang_i==0)
			{
				$.ajax({
					type:'POST',
					url:'/Common/clollection/',
					data: {'id':id},
					async:false,
					success:function(response){
						if(response == 1){
							
							shoucang_i=shoucang_i+1;
							
							//列表页
							$(function(){
								$('#container').on('click','ul li .b_lsxq_r_lvshi_span4',function(){
									  if (!$(this).hasClass('shoucang')) {
									    $(this).addClass('shoucang');
									    $(this).find('em').text(parseInt($(this).find('em').text())+1);
									  };

									});
							})
							
							//首页
							$('.l_fxpl li').click(function(){
							var t=$(this).find('span').text();
							if(!$(this).hasClass('b_index_zan'))
								{
									$(this).find('span').text(parseInt(t)+1);
							$(this).find('img:nth-child(2)').addClass('position_ab');
							$(this).find('img:first-child').removeClass('position_ab');
							$(this).addClass('b_index_zan');
								}
							})
							
						
						$('.b_lsxq_r_lvshi_span4').on('click',function(){
						  if (!$(this).hasClass('shoucang')) {
						    $(this).addClass('shoucang');
						    $(this).find('em').text(parseInt($(this).find('em').text())+1);
						    $(this).unbind();
						  };
						})
						
						}else if (response == 2){
							alert('收藏失败');
						}else if (response == 3){
							alert("请登录后收藏");
						}
					},
				});
			}
		}

		function manus(){
			if($("#title").val()=="标题限制52字符"){
				alert("请输入标题！");
				return false;
			}
			if($("#title").val().length>52){
				alert("标题限制52以内字符！");
				return false;
			}
		}
		
		function editpass(){
			if($("#used_pass").val() == ''){		
				alert("旧密码不可为空")
				return false;
			}
			if($('#pass').val().length < 6){		
				alert("新密码长度不能小于6位")
				return false;
			}
			if($('#pass').val()!=$('#pass1').val()){	
				alert("两次新密码不一致");
				return false;
			}
			
		}
		
		function mdelete(id,table){
			if(!id){
				alert("参数错误");
				return false;
			}else{
				$.ajax({
					type:'POST',
					url:'/Member/mdelete/',
					data: {
						'id':id,
						'table':table
						},
					async:false,
					success:function(response){
						if(response == 1){
							alert("删除成功");
						}else{
							alert(response);
						}
					},
				});
			}
			
		}
		
		
		//腾讯微博分享
		function showToTencent(contId,title,picUrl,portalUrl){
			var localurl = portalUrl+"/common/news/id/"+contId;
		    var pp = encodeURI("磅礴新闻");
		    var _t = encodeURI("【磅礴新闻："+title+"】(分享自@磅礴新闻)");
			var _url = encodeURIComponent(localurl);
			var _appkey = encodeURI("");//你从腾讯获得的appkey
			var _pic = encodeURI(picUrl);//（例如：var _pic='图片url1|图片url2|图片url3....）
			var _site = 'http://www.pangbo.tv';//你的网站地址
			var _u = 'http://v.t.qq.com/share/share.php?url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic+'&title='+_t+'%23'+pp+'%23';
			window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
			share(contId);//分享加1
		}
		
		
		
		function showToSina(contId,title,picUrl,portalUrl){
	        var localurl = portalUrl+"/common/news/id/"+contId;
	        var pp = encodeURI("磅礴新闻");
	        var _url = encodeURIComponent(localurl);
	        var _t = encodeURI("【磅礴新闻："+title+"】(分享自@磅礴新闻)");
			var _appkey = encodeURI("");//你从微薄获得的appkey
			var _pic = encodeURI(picUrl);
			var _site = '';//你的网站地址
			var _u = 'http://service.weibo.com/share/share.php?url='+_url+'&appkey='+_appkey+'&pic='+_pic+'&title='+_t+'%23'+pp+'%23';
			window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
			share(contId);//分享加1
		}
		
		function showToweixin(contId){
				
				$('#qrcode').find('img').attr("src","/Public/erweima/id/"+contId);
				$('#qrcode').animate({top:'20%'});
				share(contId);//分享加1
		}
		$('body').prepend('<div id="qrcode" ><p>打开微信“扫一扫”<span>关闭</span></p><img ></div>');
		$('#qrcode').click(function(){
			$('#qrcode').animate({top:'-50%'});
		})
		
		function share(id){
			$.ajax({
				type:'POST',
				url:'/Common/share/',
				data: {
					'id':id,
					},
				async:false,
				success:function(response){
				},
			});
		}
		
		
		
		
		
		
		