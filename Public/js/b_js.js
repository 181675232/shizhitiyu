(function($) { 
  $(function(){
    var b_lsxq_i=0; 
    var windowH=$(window).height();
    // 支付页面

    var header_H= $('.header').height();
    var footer_H= $('.footer').height();
    zf_h();
    function zf_h(){
      var qrendingdan_H=$('#b_qrd').height();
      var zf_H= $('#b_zf').height();
      if (zf_H<windowH-header_H-footer_H) {
        $('#b_zf').css('marginBottom',windowH-header_H-footer_H-63-zf_H);
      };
      if (qrendingdan_H<windowH-header_H-footer_H) {
        var t2=windowH-header_H-footer_H-113-qrendingdan_H;
        $('#b_qrd').css('marginBottom',t2+'px');
      };
    }
    $(window).resize(function(){
      windowH=$(window).height();
      zf_h();
    })
    function lsxq_turn(lvshi){
      if (!lvshi.find('.b_lsxq_r_turn').is(':animated')) 
      {
        if (b_lsxq_i<=0) {b_lsxq_i=0;}
        else if(b_lsxq_i>=lvshi.find('.b_lsxq_r_turn li').length){b_lsxq_i=0;}  
        var ttt=-364*b_lsxq_i;
        lvshi.find('.b_lsxq_r_turn').stop(true,true).animate({'marginLeft':ttt+'px'},800,function(){
         b_lsxq_i++;
       });
        lvshi.find('.b_lsxq_r_turn_dian li').eq(b_lsxq_i).addClass('b_lsxq_r_turn_dian_checked').siblings().removeClass('b_lsxq_r_turn_dian_checked');
        lvshi.find('.b_ls_r_ul>li').eq(b_lsxq_i).addClass('b_ls_r_li_checked').siblings().removeClass('b_ls_r_li_checked');
      }
    }

    
    
    $('.b_lsxq_l_move').click(function(){
      var b_lsxq_r_lvshi=$(this).closest('.b_lsxq_r_lvshi');
      b_lsxq_i =b_lsxq_r_lvshi.find('.b_lsxq_r_turn_dian li.b_lsxq_r_turn_dian_checked').index()-1;
      lsxq_turn(b_lsxq_r_lvshi);
    })
    $('.b_lsxq_r_move').click(function(){

      var b_lsxq_r_lvshi=$(this).closest('.b_lsxq_r_lvshi'); 
      if (b_lsxq_r_lvshi.find('.b_lsxq_r_turn_dian li.b_lsxq_r_turn_dian_checked').index()==0) {b_lsxq_i=1;}
      else{
       b_lsxq_i =b_lsxq_r_lvshi.find('.b_lsxq_r_turn_dian li.b_lsxq_r_turn_dian_checked').index()+1;
     }
     lsxq_turn($(this).closest('.b_lsxq_r_lvshi'));
   })
    $('.b_lsxq_r_turn_dian li').click(function(){
      b_lsxq_i=$(this).index();
      lsxq_turn($(this).closest('.b_lsxq_r_lvshi'));
    })

    // 数据图片圆角
    if (window.PIE) {
      $('.b_lssj_round').each(function() {
        PIE.attach(this);
      });
      $('.round').each(function () {
        PIE.attach(this);
      }); 
      $('.b_zf_info').each(function () {
        PIE.attach(this);
      });
    }
    // 律师数据hover
    $('.b_lssj_ul li').hover(function(){
      $(this).find('.b_lssl_li_div3').stop(true,true).fadeIn(1000).siblings('.b_lssl_li_div2').stop(true,true).hide().siblings('.b_lssl_li_div1').find('img').removeClass('lvjing').stop(true,true).animate({'opacity':'1','filter':'alpha(opacity=100)'},1000);
    },function(){
     $(this).find('.b_lssl_li_div2').stop(true,true).fadeIn(1000).siblings('.b_lssl_li_div3').stop(true,true).hide().siblings('.b_lssl_li_div1').find('img').addClass('lvjing').stop(true,true).removeAttr('style');})
    $('#b_lxwm_l ul li').click(function(){
      $(this).addClass('b_lxwm_li_checked').siblings().removeClass('b_lxwm_li_checked');
    })
    // vip
    $('.b_v_fukuan span').text($('.b_v_dingdan_price ').text());
    // 文章页
    $('.b_b_wz_pl_fabu_r textarea').focus(function(){
      if ($(this).val()=='发表评论') {
        $(this).val('');
        $(this).css('color','#333');
      }
    }).blur(function(){
      if ($(this).val()=='') {
        $(this).val('发表评论');
        $(this).css('color','#ccc');
      }
    })
    // 文章页举报

    $('.b_wz_huifu_laizi2').hover(function(){
      var jubao=$(this).siblings('.b_wz_huifu_jubao');
      jubao.css('display','block').stop(true,true).animate({width:'92px','height':'51px','opacity':'1','filter':'alpha(opacity=100)'});
    })
    $('.b_wz_huifu_d').mouseleave(function(){
     $(this).find('.b_wz_huifu_jubao').stop(true,true).animate({'opacity':'0','filter':'alpha(opacity=0)'},function(){
      $(this).css({'width':'0','height':'0'});
    }); 
     $('.b_wz_huifu_jubao').css('display','none');

   })
    $('.b_wz_huifu_jubao em').click(function(){
      $(this).addClass('b_color_333').siblings().removeClass('b_color_333');
    })

// 文章页点赞
$('.b_wz_pl_r3_r  span:first-child').click(function(){
  if(!$(this).hasClass('b_wz_pl_r3_span11'))
  {
    $(this).addClass('b_wz_pl_r3_span11');
    var emt=$(this).find('em').text();
    $(this).find('em').text(parseInt(emt)+1);
  }
})
// 收起回复

$('.b_wz_pl_content > ul > li').each(function (){
 var ulH= $(this).find('ul').height();
 $(this).find('ul').height(ulH);
})


$('.b_wz_shouqi_block').click(function(){
  // $(this).siblings('ul').animate({'opacity':'0'},{duration:1000}).animate({height:'0'},500);
  $(this).siblings('ul').stop(true,true).slideToggle(800);
  if ($(this).text()=='收起回复') {$(this).text('展开回复')}else{$(this).text('收起回复')}
})  
$('#b_wz .b_lsxq_r_lvshi_span1').click(function(){
 var scroll= $('.b_wz_pl_fabu').offset().top;
 $('html').animate({'scrollTop':scroll+'px'},1000);
 $('.b_b_wz_pl_fabu_r textarea').focus();
})
// 回复个人
$('.b_wz_pl_r3_span2').click(function(){
  $(this).closest('.b_wz_pl_r').siblings('.b_wz_pl_hf1').slideDown();
})
$('.b_wz_huifu_laizi3 em').click(function(){
  var hf2=$(this).closest('.b_wz_huifu_laizi').siblings('.b_wz_pl_hf2');
  var hf2H=hf2.height()+18; 
  if (hf2.css('display')=='none') {
    $(this).closest('ul').stop(true,true).animate({'height':'+='+hf2H},800,function(){

    });  
    hf2.slideDown();
  };

})
/*$('.b_zx_tg_bt1').click(function(){
  $('.b_zx_tougao_tiaojian').stop(true,true).fadeIn();
  $('html').animate({'scrollTop':$('.b_zx_tougao_tiaojian').offset().top-100},800);
})*/
$('.b_zx_tougao_tiaojian input,.b_zx_tougao_close').click(function(){
  $('.b_zx_tougao_tiaojian').stop(true,true).fadeOut();
})

// **********登录************
// 登录高度不够

$('#b_dl .b_dl_input1').focus(function(){
  if ($(this).val()=='请输入手机号码') {
    $(this).val('');
  }
}).blur(function(){
  if ($(this).val()=='') {
    $(this).val('请输入手机号码');
  }
})
/*$('#b_dl .b_dl_input2').focus(function(){
  if ($(this).val()=='请输入密码') {
    $(this).val('');
  }
}).blur(function(){
  if ($(this).val()=='') {
    $(this).val('请输入密码');
  }
})*/
$('#b_dl .b_dl_input_l_symbol').click(function(){
  $(this).toggleClass('b_dl_input_l_symbol');
})
var dl_input_typeI=0;
$('#b_dl .b_dl_input_type').click(function(){
  $(this).siblings('.b_dl_input_type_d').slideToggle();
  if (dl_input_typeI%2==0) {
    $(this).css('border-bottom','0');
  }
  else
  {
    $(this).css('border-bottom','1px solid #ccc');
  }
  dl_input_typeI++;

})
$('#b_dl .b_dl_input_type_d p').click(function(){
  $(this).parent().siblings('.b_dl_input_type').text($(this).text());
  $(this).parent().slideUp();
  $('#b_dl .b_dl_input_type').css('border-bottom','1px solid #ccc');
  dl_input_typeI++;
})
// 
$('.b_zc_sex_r input').click(function(){
  $(this).siblings('em').addClass('b_zc_sex_checked').parent().siblings().find('em').removeClass('b_zc_sex_checked');
})
// 已同意
$('.b_zc_yiyuedu span').click(function(){
  $(this).toggleClass('b_zc_yiyuedu_check');
})
//注册input
function zc_input(i,liI){
  var val;
  if (liI==0) {
    switch(i){
      case 0:val='请输入个人昵称';break;
      case 1:val='请输入6位以上密码';break;
      case 2:val='请输入图片验证码';break;
      case 4:val='请输入您的手机号码';break;
      case 7:val='请输入短信验证码';break;
      case 8:val='您的姓名';break;
      default:break;
    }
  }
  else{
    switch(i){
      case 0:val='请输入企业昵称（5字以内）';break;
      case 1:val='请输入6位以上密码';break;
      case 2:val='请输入图片验证码';break;
      case 4:val='请输入管理员的手机号码';break;
      case 7:val='请输入短信验证码';break;
      case 8:val='请填写企业全称（须与组织机构代码名称一致）';break;
      default:break;
    }
  }

  return val;
} 
$('#b_zc .b_zc_content input[type=text]').focus(function(){
  var thisVal=$(this).val();
  var duiyingVal=zc_input($(this).index(),$(this).closest('li').index());
  if (thisVal==duiyingVal) {
    $(this).val('');
  }
  else if(thisVal==''){
	  $(this).val(duiyingVal);
  }
}).blur(function(){
  var thisVal=$(this).val();
  var duiyingVal=zc_input($(this).index(),$(this).closest('li').index());
  if (thisVal=='') {
    $(this).val(duiyingVal);
  };
})
$('#b_zc .b_zc_tit div').click(function(){
  $(this).addClass(' b_zc_tit_checked').siblings().removeClass(' b_zc_tit_checked');
  $('.b_zc_content>ul>li').eq($(this).index()).addClass('b_zc_li_checked').siblings().removeClass('b_zc_li_checked');
  if ($(this).index()==1) {
    $('#b_zc > div').stop(true,true).animate({'height':'+=20'});
  }else{
    $('#b_zc > div').stop(true,true).animate({'height':'-=20'});

  }
})
$('#container').on('click','.b_zx_r_wd_r5 em',function(){
  $(this).parent().siblings('div').stop(true,true).slideToggle(800);
  if ($(this).text()=='查看详情') {
    $(this).text('收起');
  }else{
    $(this).text('查看详情');

  }
})
// 个人中心标题
$('#b_zx .b_zx_tg_biaoti input').focus(function(){
  if ($(this).val()=='标题限制52字符') {
    $(this).val('');
    $(this).css('color','#333');
  };
}).blur(function(){

  if ($(this).val()=='') {
    $(this).val('标题限制52字符');
    $(this).css('color','#ccc');
  };
})
// 短信提醒
$('#b_zx .b_zx_vip_xufei_d2 input[type="text"]').focus(function(){
  if ($(this).val()=='请输入您的手机号') {
    $(this).val('');
    $(this).css('color','#333');
  };
}).blur(function(){

  if ($(this).val()=='') {
    $(this).css('color','#ccc');
    $(this).val('请输入您的手机号');
  };
})
$('#b_zx  .b_zx_tg_lanmu ul li input').click(function(){
  $(this).parent().addClass('b_zx_tg_checked').siblings().removeClass('b_zx_tg_checked');
})
$('.b_zx_td_liuyan ul li input').click(function(){
  $(this).parent().addClass('b_zx_tg_checked').siblings().removeClass('b_zx_tg_checked');

})

$('.b_lsxq_r_lvshi li').hover(function(){
  $(this).find('a').css('color','#cc1320').parent().siblings().find('a').css('color','#999');
},function(){
  $(this).find('a').css('color','#999');

})

$('.b_lsxq_r_lvshi_span3').on('click',function(){
  if (!$(this).hasClass('b_zaned')) {
    $(this).addClass('b_zaned');
    $(this).find('em').text(parseInt($(this).find('em').text())+1);
    $(this).unbind();
  };

})


/*$('#b_lsxq_r').on('click','.b_lsxq_r_lvshi_span4',function(){
	  $(this).css('background','url("images/shouchang_h.png") no-repeat scroll left center');
	  var em_=$(this).find('em').text();
	  $(this).find('em').text(parseInt(em_)+1);
	})*/
function zx_sc(){
	  $('.b_zx_r_wd_content_r2 .b_zx_r_sc li').each(function(){
	    var sc_li=$('.b_zx_r_wd_content_r2 .b_zx_r_sc li');
	    var img_h=$(this).find('img').height();
	    var p1_h=$(this).find('.b_zx_r_sc_p1').height();
	    var p2_h=$(this).find('.b_zx_r_sc_p2').height();
	    if (p1_h<img_h) {
	      $(this).find('.b_zx_r_sc_p1').css('padding-top',(img_h-p1_h)/2+'px');
	    };
	    if (p2_h<img_h) {
	      $(this).find('.b_zx_r_sc_p2').css('padding-top',(img_h-p2_h)/2+'px');
	    };
	  })
	}
	var zx_sc_i=0;
	$('.b_zx_r_wd_content_r2.b_zx_r_sc_d>h2').click(function(){
	  if (zx_sc_i%2==0) {
	    $(this).siblings('ul').find('.b_zx_r_sc_p3').fadeIn(800);
	  }
	  else{
	    $(this).siblings('ul').find('.b_zx_r_sc_p3').fadeOut(800);
	  }
	  zx_sc_i++;
	})
	var zx_pl_i=0;
	$('.b_zx_r_wd_content_r3.b_zx_r_sc_d>h2').click(function(){
	  if (zx_pl_i%2==0) {
	    $(this).siblings('ul').find('.b_zx_r_pl_del').fadeIn(800);
	  }
	  else{
	    $(this).siblings('ul').find('.b_zx_r_pl_del').fadeOut(800);
	  }
	  zx_pl_i++;
	})
	$('.b_zx_r_pl_del').click(function(){
	  $(this).parent().fadeOut(800);
	})
	$('.b_zx_r_sc_p3').click(function(){
	  $(this).parent().fadeOut(800);
	})
	$('.b_zx_r_wd .b_zx_r_wd_content_l ul li').click(function(){
	  var i=parseInt($(this).index())+1;

	  $(this).addClass('b_zx_r_tit_ed').siblings().removeClass('b_zx_r_tit_ed');
	  $('.b_zx_r_wd_content_r'+i).fadeIn(800).siblings().fadeOut(10);
	  if (i==2) {zx_sc()};
	})

	 $('.b_zx_r_tg_content_r3>ul').on('click','.b_zx_r_tg_bj',function(){
	   // alert($(this).siblings('.b_zx_r_tg_bj_d').css('display'));
	   if ($(this).siblings('.b_zx_r_tg_bj_d').css('display')=='none') {
	     $(this).siblings('.b_zx_r_tg_bj_d').fadeIn(800);
	     $(this).find('em').html('&nu;');
     $(this).css('color','#333');
	   }
	   else{
	    $(this).siblings('.b_zx_r_tg_bj_d').fadeOut(800);
	    $(this).find('em').text('>');

    $(this).css('color','#0757a9');

	 }


	 })
	$('.b_zx_r_tg_bj_d span').click(function(){
	  $(this).addClass('b_zx_r_tg_bj_checked').siblings().removeClass('b_zx_r_tg_bj_checked');
	  $(this).parent().fadeOut(800);
	  $(this).parent().siblings('.b_zx_r_tg_bj').find('em').text('>');

	  $(this).parent().siblings('.b_zx_r_tg_bj').css('color','#0757a9');
	})
	$('.b_zx_r_tg .b_zx_r_wd_content_l ul li').click(function(){
	  $(this).addClass('b_zx_r_tit_ed_r').siblings().removeClass('b_zx_r_tit_ed_r');
	  var i=$(this).index()+1;
	  
	  if ($(this).index()==3) {
	   $('.b_zx_r_tg_content_r3').fadeIn(800).siblings().fadeOut(10);
	 }
	 else if($(this).index()==2)
	 {
	  $('.b_zx_r_tg_content_r4').fadeIn(800).siblings().fadeOut(10);
	}
	else{
	  $('.b_zx_r_tg_content_r'+i).fadeIn(800).siblings().fadeOut(10);
	}
	if (i!=1) {
	  $('.b_zx_r_tg .b_zx_r_tit span').fadeOut(10);
	}else{
	 $('.b_zx_r_tg .b_zx_r_tit span').fadeIn(10);
	}
	})

	// $('.b_zx_vip_top .b_zx_td3').click(function(){
	//   if ($('.b_zx_sz_info .b_zx_vip').css('display')=='none') {

//	     $('.b_zx_vip').fadeIn(800);
//	     $(this).text('收起>');
//	     $(this).css('color','#c00909');
	//   }
	//   else{
//	    $('.b_zx_vip').fadeOut(800);
//	    $(this).text('编辑>');
//	    $(this).css('color','#777');

	//  }
	// })
	// 个人中心vip
	$('.b_zx_sz .b_zx_r_tit_ed_r').click(function(){
	  $(this).siblings().removeClass('b_zx_r_tit_ed');
	  $('.b_zx_sz_vip,.b_zx_qy_xufei').fadeIn(800);
	  $('.b_zx_sz_info').fadeOut(10);
	  $(this).parent().siblings('.b_zx_l_line').css('background','#ccc');
	})
	$('.b_zx_sz .b_zx_r_wd_content_l ul li:first-child').click(function(){
	  $(this).addClass('b_zx_r_tit_ed');
	  $('.b_zx_sz_vip,.b_zx_qy_xufei').fadeOut(10);
	  $('.b_zx_sz_info').fadeIn(800);
	  $(this).parent().siblings('.b_zx_l_line').css('background','#333');
	})
	// 个人中心记录放大

	$('.b_zx_r_wd_content_r1 ul ').on('click','.b_zx_fangdajing span',function(){
	 var text1= $(this).parent().siblings('.b_zx_r_wd_r_ul_d').find('ul li:first-child h2').html();
	 var text2= $(this).parent().siblings('.b_zx_r_wd_r_ul_d').find('ul li:nth-child(2) .b_b_zx_info_r').html();
	 var text3= $(this).parent().siblings('.b_zx_r_wd_r_ul_d').find('ul li:nth-child(3) .b_b_zx_info_r').html();
	 var text4= $(this).parent().siblings('.b_zx_r_wd_r_ul_d').find('ul li:nth-child(4) .b_b_zx_info_r').html();
	 var text5= $(this).parent().siblings('.b_zx_r_wd_r_ul_d').find('ul li:nth-child(5) .b_b_zx_info_r').html();
	 console.log(text2);
	 $('.b_zx_jl_fd ul li:first-child h2').html(text1);
	 $('.b_zx_jl_fd ul li:nth-child(2) .b_zf_info_r').html(text2);
	 $('.b_zx_jl_fd ul li:nth-child(3) .b_zf_info_r').html(text3);
	 $('.b_zx_jl_fd ul li:nth-child(4) .b_zf_info_r').html(text4);
	 $('.b_zx_jl_fd ul li:nth-child(5) .b_zf_info_r').html(text5);
	 $('.b_zx_jl_fd').fadeIn(800);
	})
	$('.b_zx_jl_fd_m').click(function(){
	  $('.b_zx_jl_fd').fadeOut(800);
	})
	// 个人中心标题跳转
	$('.b_zx_r_nav2').click(function(){
	  var t2=$('.b_zx_r_tg').offset().top;
	  $('html,body').animate({scrollTop:t2},800)
	})
	$('.b_zx_r_nav4').click(function(){
	  var t2=$('.b_zx_sz').offset().top;
	  $('html,body').animate({scrollTop:t2},800)
	})
	// 基本信息设置编辑
	$('.b_zx_sz_r table tr:odd').css('display','none');
	$('.b_zx_sz_r table tr .b_zx_td3').click(function(){
	  console.log($(this).parent().index());
	  if ($(this).parent().index()!=12) {
	    if ( $(this).parent().next().css('display')=='none') {
	      $(this).parent().next().fadeIn();
	      $(this).html('收起&nu;');
	      $(this).css('color','#c00909');
	      $(this).parent().find('td').css('border-bottom','1px dashed #eeeeee');

	    }
	    else{
	      $(this).parent().next().fadeOut();
	      $(this).html('编辑>');
	      $(this).css('color','#777');
	      $(this).parent().find('td').css('border-bottom','1px solid #eeeeee');

	    }
	  };
	})


})
})(jQuery)
// ***********所有小赞***********
$(function(){
	$('#container').on('click','ul li .b_lsxq_r_lvshi_span3',function(){
		  if (!$(this).hasClass('b_zaned')) {
		    $(this).addClass('b_zaned');
		    $(this).find('em').text(parseInt($(this).find('em').text())+1);
		  };

		});
	$('.b_wz_laiyuan').on('mouseenter','li',function(){
		$(this).find('p.clearfix').stop(true,true).fadeIn();
	});
	$('.b_wz_laiyuan').on('mouseleave','li',function(){
			$(this).find('p.clearfix').fadeOut();
	});	
})
/*$(function(){
	$('#container').on('click','ul li .b_lsxq_r_lvshi_span4',function(){
		  if (!$(this).hasClass('shoucang')) {
		    $(this).addClass('shoucang');
		    $(this).find('em').text(parseInt($(this).find('em').text())+1);
		  };

		});
})*/



