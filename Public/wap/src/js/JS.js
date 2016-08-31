$(function  () {
    var indexSwiper = new Swiper ('.index-banner', {
         slidesPerView:1,
    pagination: '.index-banner .swiper-pagination'
    }); 
    var baoSwiper = new Swiper ('.baoming-banner', {
    slidesPerView:1.5,
    pagination: '.pic-down .swiper-pagination',
    spaceBetween:10});
    $('#goBack').click(function  () {
        window.history.go(-1);
    });
    $('#switchBisai div').click(function  () {
        $(this).addClass('active').siblings().removeClass('active');
        $('#switchNeirong .neirong').eq($(this).index()).addClass('show').siblings().removeClass('show');
    });
    $('#queren .td .btn-arr').click(function  () {
        $(this).parent().toggleClass('hide');
        $(this).toggleClass('active');
    });
    $('#jian').click(function  () {
          var num=$(this).next().val();
            num>1&&$(this).next().val(num-1);
    });  
    $('#jia').click(function  () {
          var num=$(this).prev().val();
            $(this).prev().val(num-0+1);
    });
});
