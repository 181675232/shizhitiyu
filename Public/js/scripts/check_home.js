//$(function(){
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
is_mobile();
var window_w=$(window).width();
function is_mobile(){

	if(window_w<=414)
		{
		  window.location="www.pangbo.tv/wap/";
		}
	else{
		
	}
}

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
									html +="<a href=\"/common/news/id/{$val.id}\">";
									html += "<img src=\""+data[i]['simg']+"\" height=\"147\" width=\"381\"  style=\"width:381px;height:147px;\">";
									html +="</a>";
									html +="<a href=\"/common/news/id/"+data[i]['id']+"\" class=\"b_lsxq_r_lvshi_p1 clearfix\"> "+data[i]['title']+"</a>";
									html +="<a href=\"/common/news/id/"+data[i]['id']+"\" class=\"b_lsxq_r_lvshi_p2 color_7\">"+data[i]['introduction']+"</a>";
									html +="<p class=\"b_lsxq_r_lvshi_p3  color_9 clearfix\">";
		                            html +="<span class=\"color_c left b_xwtt_l\">";
		                            html +="<em class=\"b_xwtt_l_em1\">"+data[i]['tag_title'].substring(0, 1)+"</em>";
		                            html +="<em class=\"b_xwtt_l_em2\">"+data[i]['tag_title']+"</em>";
		                            html +="</span><span class=\"b_lsxq_r_lvshi_span1\">（"+data[i]['count']+"） </span>";
		                            html +="<span class=\"b_lsxq_r_lvshi_span2\">（"+data[i]['share']+"）</span>";
		                            html +="<span class=\"b_lsxq_r_lvshi_span3\"onclick=\"return upper('news',"+data[i]['id']+")\">（<em>"+data[i]['upper']+"</em>） </span>";
		                            if(data[i]['is_coll']==1){
		                            	html +="<span class=\"b_lsxq_r_lvshi_span4 shoucang\" >（<em>"+data[i]['clollection']+"</em>） </span>";
		                            }else{
		                            	html +="<span class=\"b_lsxq_r_lvshi_span4\" onclick=\"return clollection("+data[i]['id']+")\">（<em>"+data[i]['clollection']+"</em>） </span>";
		                            }
		                            html +="</p>";
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
		
		
		
		

		
		
		
		
		
		
		
		
		