(function (G){

//    G.pageLoaded.start();
    let typesHtml = [{
        text: '二维码',
        icon: './images/home/icon-head-001.png',
        userType: [2, 3],
        hrefClass:'gs-go-code-scanning',
        index:1
    }, {
        text: '推广佣金提现',
        icon: './images/home/icon-head-009.png',
        userType: [2, 3],
        hrefClass:'gs-go-extract-withdrawal-benefit',
        index:2
    }, {
        text: '我的茶楼',
        icon: './images/home/icon-head-002.png',
        userType: [2, 3],
        hrefClass:'gs-go-my-club',
        index:3
    }, {
        text: '今日新增扫码',
        icon: './images/home/icon-head-006.png',
        userType: [2, 3],
        hrefClass:'gs-go-new-scan-today',
        index:4
    }, {
        text: '今日推广收益',
        icon: './images/home/icon-head-003.png',
        userType: [2, 3],
        hrefClass:'gs-go-income-details',
        index:5
    }, {
        text: '大区收益明细',
        icon: './images/home/icon-head-007.png',
        userType: [3],
        hrefClass:'gs-go-income-breakdown',
        index:6
    }, {
        text: '整线总业绩',
        icon: './images/home/icon-head-004.png',
        userType: [3],
        hrefClass:'gs-go-totalper-formance',
        index:7
    }, {
        text: '授权大区代理',
        icon: './images/home/icon-head-008.png',
        userType: [3],
        hrefClass:'gs-go-regiona-lagent',
        index:8
    }, {
        text: '赠送亲友',
        icon: './images/home/icon-head-005.png',
        userType: [1, 2, 3],
        hrefClass:'gs-go-give-friend',
        index:9
    }];
    G.ajax({
        url: G.API.INDEX_USER_INFO,
        data: {},
    }, function (data){
        let ret=data.ret;
        let typesHtmlList = '<a href="javascript:;" class="aui-grids-item {hrefClass} home-list-{index}"><div class="aui-grids-item-hd"><img src="{icon}" alt=""></div><div class="aui-grids-item-bd aui-grids-item-text">{text}</div></a>';
        let recommendHtml = '';
        typesHtml.forEach(function (element) {
            if(-1!=$.inArray(ret.userType, element.userType)){
                recommendHtml += G.format(typesHtmlList, element);
            }
        });
        $('#toolMenu').html(recommendHtml);
        let {nickName, playerId, headImg, userType} = ret;
        $('#headeImg').attr('src', headImg);
        $('#nickName').text(nickName);
        $('#gs-game-id').text(playerId);
        $('.home-list-3').append('<div class="home-list-bubble f-toe">'+ret.myTeaHouse+'人</div>');
        $('.home-list-4').append('<div class="home-list-bubble f-toe">'+ret.newScanToday+'人</div>');
        $('.home-list-5').append('<div class="home-list-bubble f-toe">'+ret.genToday+'</div>');
        $('.home-list-7').append('<marquee scrollamount="4" ><div class="home-list-bubble-1 ">'+ret.wholeLine+'</div></marquee>');
        G.goIndex();
        G.sessionStorage.set('userinfo', {
            playerId: playerId,
            userType: userType
            });
        G.pageLoaded.done(() => {
            $('.gs-wrap-conts').removeClass('hide');
        });
    }, function (res){
        $('body').html(G.showNothing({
            word: res.msg
        }));
    });
})(GS);

