/*------------------------------头部---------------------------*/
// /*---------------搜索--------------*/
// $('.l_head_top li').eq(0).mouseenter(function(){
// 	$(this).find('a').addClass('l_active');
// 	$(this).find('div').stop(true,true).show().animate({'width':'264px','height':'60px'},500);
// });
// $('.l_head_top li').eq(0).mouseleave(function(){
// 	$(this).find('a').removeClass('l_active');
// 	$(this).find('div').stop(true,true).animate({'width':'0px','height':'0px'},500).hide();
// });
// $('.l_head_top li').eq(0).find('input').click(function(){
// 	$(this).parents('div').css('display','block');
// });
// /*---------------个人中心--------------*/
// $('.l_head_top li').eq(2).mouseenter(function(){
// 	$(this).find('a').addClass('l_active');
// 	$(this).find('div').stop(true,true).show().animate({'width':'75px'},500);
// });
// $('.l_head_top li').eq(2).mouseleave(function(){
// 	$(this).find('a').removeClass('l_active');
// 	$(this).find('div').stop(true,true).animate({'width':'0px'},500).hide();
// });
// /*---------------客户端--------------*/
// $('.l_head_top li').eq(3).mouseenter(function(){
// 	$(this).find('a').addClass('l_active');
// 	$(this).find('div').stop(true,true).show().animate({'width':'234px','height':'140px'},500);
// });
// $('.l_head_top li').eq(3).mouseleave(function(){
// 	$(this).find('a').removeClass('l_active');
// 	$(this).find('div').stop(true,true).animate({'width':'0px','height':'0px'},500).hide();
// });
// /*---------------登录--------------*/
// $('.l_an_dl').click(function(){
// 	$().css('display','block');
// 	$().css('display','block');
// });
// $('').click(function(){
// 	$().css('display','none');
// 	$().css('display','none');
// });
// /*---------------注册--------------*/
// $('.l_an_zc').click(function(){
// 	$().css('display','block');
// 	$().css('display','block');
// });
// $('').click(function(){
// 	$().css('display','none');
// 	$().css('display','none');
// });
// /*---------------导航--------------*/
// var sum_nav_;
// $('.l_nav li').each(function(){
// 	$(this).find('div.l_a_ho_fff').css({'width':40*$(this).find('div.l_a_ho_fff a').size()});
// 	$(this).find('div.l_a_ho_fff').css({'left':-($(this).find('div.l_a_ho_fff').width()-$(this).width())/2});
// });
// sum_nav_=$('.l_nav li').size()-1;
// $('.l_nav li').eq(sum_nav_).find('div.l_a_ho_fff').css({'left':-$('.l_nav li').eq(sum_nav_).find('div.l_a_ho_fff').width()+$('.l_nav li').eq(sum_nav_).width()});	
// $('.l_nav li').mouseenter(function(){
// 	$(this).find('a.f_18').addClass('l_active');
// 	$(this).find('div.l_a_ho_fff').stop(true,true).slideDown();
// });
// $('.l_nav li').mouseleave(function(){
// 	$(this).find('a.f_18').removeClass('l_active');
// 	$(this).find('div.l_a_ho_fff').stop(true,true).slideUp();
// });




/*----------------------------内容---------------------------*/
/*---------------banner样式设置与标签添加--------------*/
var sum_ban=0,
admin_ban=true;
$('.l_ban .l_rq_').css({'width':$('.l_ban .l_rq_ img').width()*$('.l_ban .l_rq_ img').size()});
$('.l_ban').append('<ul class="l_list l_ul l_ul_cur position_ab"></ul>');
for(var i=0;i<$('.l_ban .l_rq_ a').size();i++){
	$('.l_ban .l_list').append('<li></li>')
};
$('.l_ban .l_list li').eq(0).addClass('l_active');
$('.l_ban .l_list').css({'width':$('.l_ban .l_list li').size()*($('.l_ban .l_list li').width()+parseInt($('.l_ban .l_list li').css('margin-left'))*2)});
$('.l_ban .l_list').css({'left':($('.l_ban').width()-$('.l_ban .l_list').width())/2});
/*---------------banner切换效果--------------*/
function lb_ban(){
	$('.l_ban .l_list li').eq(sum_ban).addClass('l_active').siblings().removeClass('l_active');
	$('.l_ban .l_rq_').animate({'marginLeft':-$('.l_ban .l_rq_ img').width()*sum_ban},1000,function(){
		admin_ban=true;
	});
};
/*---------------banner列表点击效果--------------*/
$('.l_ban .l_list li').click(function(){
	sum_ban=$(this).index();
	lb_ban();
});
/*---------------banner左箭头点击效果--------------*/
$('.l_ban .l_an_l').click(function(){
	if(admin_ban){
		admin_ban=false;
		if(sum_ban>0){
			sum_ban--;
		};
		lb_ban();
	};
	
});
/*---------------banner右箭头点击效果--------------*/
$('.l_ban .l_an_r').click(function(){
	if(admin_ban){
		admin_ban=false;
		if(sum_ban<$('.l_ban .l_list li').size()-1){
			sum_ban++;
		};
		lb_ban();
	};
});
/*---------------分享鼠标滑效果--------------*/
$('.l_fxpl li').eq(1).mouseenter(function(){
	$(this).find('p.clearfix').stop(true,true).fadeIn();
});
$('.l_fxpl li').eq(1).mouseleave(function(){
	$(this).find('p.clearfix').fadeOut();
});
$('.l_xw_list_d li').each(function(){
	$(this).find('.l_fxpl li').eq(1).mouseenter(function(){
		$(this).find('p.clearfix').stop(true,true).fadeIn();
	});
	$(this).find('.l_fxpl li').eq(1).mouseleave(function(){
		$(this).find('p.clearfix').fadeOut();
	});	
});



/*---------------视频文字垂直居中--------------*/
function cz_sp(){
	$('.l_sp .l_div_div li').each(function(){
		$(this).find('.l_div p').css({'margin-top':($(this).find('.l_div').height()-$(this).find('.l_div p').height())/2});
	});	
};
cz_sp();
/*---------------视频鼠标滑动切换效果--------------*/
$('.l_sp .l_div_list li').mouseenter(function(){
	$('.l_sp .l_div_div li').eq($(this).index()).stop(true,true).fadeIn().siblings().stop(true,true).fadeOut();
	cz_sp()
});
/*---------------新闻列表右浮动效果--------------*/
$('.l_right .l_xw_list_x').each(function(){
	$(this).find('> li').eq(1).css({'border':'none','padding-top':'20px'});
});

$('.l_xw_list_x > li').each(function(){
	if($(this).index()%2==0){
		$(this).css('float','left');
	}else{
		$(this).css('float','right');
	};
});
























/*------------------------------头部---------------------------*/

/*------------------------------头部---------------------------*/
/*------------------------------头部---------------------------*/
/*------------------------------头部---------------------------*/
/*------------------------------头部---------------------------*/
/*------------------------------头部---------------------------*/
/*------------------------------头部---------------------------*/
