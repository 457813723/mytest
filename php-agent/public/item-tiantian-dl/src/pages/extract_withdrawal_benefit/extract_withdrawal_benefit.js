(function (G){
    let userType = G.sessionStorage.get('userinfo').userType;
    G.ajax({
        url: G.API.CAN_CARRY_INFO,
    }, function (data){
        let {daqushouyi, sanjifanli, canCarryToday, yitishouyi} = data.ret;
        let html = '';
        if(userType == 3){
            html += `<div class="aui-flex aui-flex-mar b-line">`;
            html += `<div class="aui-flex-head">累计大区整线收益（元）：</div>`;
            html += `<div class="aui-flex-box">${daqushouyi}</div>`;
            html += `</div>`;
        }
        html += `<div class="aui-flex aui-flex-mar b-line">`;
        html += `<div class="aui-flex-head">五级累计返利（元）：</div>`;
        html += `<div class="aui-flex-box" id="sanjifanli">${sanjifanli}</div>`;
        html += `</div>`;
        html += `<div class="aui-flex aui-flex-mar b-line">`;
        html += `<div class="aui-flex-head">${userType == 3 ? "今日可提 （大区可提+五级返利余额）：" : "今日可提 （五级返利余额）"}</div>`;
        html += `<div class="aui-flex-box" id="canCarryToday"> ${canCarryToday} </div>`;
        html += `</div>`;
        html += `<div class="aui-flex aui-flex-mar b-line">`;
        html += `<div class="aui-flex-head">已提收益：</div>`;
        html += `<div class="aui-flex-box" id="yitishouyi">${yitishouyi}</div>`;
        html += `</div>`;
        $('.gs-prepend-html').prepend(html);
    });
    $('body').on('click', '#palace-grid-list .aui-palace-grid', function (){
        var $t = $(this);
        $t.addClass('active').siblings('.aui-palace-grid').removeClass('active');
    });
    $('#send-to-game').on('click', function (){
        //底部对话框
        G.layer.open({
            content: $('.gs-extract-withdrawal').html()
            , btn: ['确定', '取消']
            , skin: 'footer'
            , yes: () => {
                let $active = $('#palace-grid-list').find('.active');
                G.ajax({
                    url: G.API.DISTRIBUTE_TO_GAMES,
                    data: {coins: $active.attr('data-coins')}
                }, data => {
                    G.msg(data.msg, function (){
                        G.reload();
                    });
                }, function (data){
                    G.confirm(data.msg,{btn:['&#21047;&#26032;','&#x53D6;&#x6D88;']}, () => {
                        G.reload();
                    });
                });
            }
        });
    });
})(GS);
(function (G){
    GS._mix(GS, {
        // 数据请求
        getListDataFromNet: function (pageNum, pageSize, successCallback, errorCallback){
            G.ajax({
                url: G.API.OPERATE_RECORD,
                data: {bt: '2017-03-12', et: G.formatDate(G.now(), 'yyyy-MM-dd'), page: pageNum, size: pageSize}
            }, function (data){
//                data.ret.list = [
//                    {
//                        REG_TIME: 'asdasd',
//                        AMOUNT: 'asdasd',
//                        STATUS: 'asdasd',
//                    }
//                ];
                let newArr = data.ret.list.map((item) => {
                    return {
                        REG_TIME: item.REG_TIME || '-',
                        AMOUNT: item.AMOUNT || '-',
                        STATUS: item.STATUS || '-',
                    };
                });
                successCallback(newArr, data.ret.list);
            }, function (){
                errorCallback();
            });
        },
        //设置列表数据
        setListData: function (curPageData){
            let str = '';
            curPageData.forEach((value) => {
                str += `<li>`;
                str += `<div class="gs-table-td">${value.REG_TIME}</div>` +
                    `<div class="gs-table-td">${value.AMOUNT}</div>` +
                    `<div class="gs-table-td">${value.STATUS}</div>`
                str += `</li>`;
            });
            $('#dataList').append(str);
        },
        // 浮动层
        navWarpOnScroll: function (){
            var navWarp = document.getElementById("navWarp");
            if(mescroll.os.ios){
                navWarp.classList.add("nav-sticky");
            } else {
                navWarp.style.height = navWarp.offsetHeight + "px";
                let navContent = document.getElementById("navContent");
                mescroll.optUp.onScroll = function (mescroll, y, isUp){
                    if(y >= navWarp.offsetTop){
                        navContent.classList.add("nav-fixed");
                    } else {
                        navContent.classList.remove("nav-fixed");
                    }
                }
            }
        }
    });
    var mescroll = new MeScroll("mescroll", {
        up: {
            callback: function (page, mescroll){
                var pageIndex = page.num - 1;
                GS.getListDataFromNet(pageIndex, page.size, function (curPageData){
                    mescroll.endSuccess(curPageData.length);
                    GS.setListData(curPageData);
                }, function (){
                    mescroll.endErr();
                });
            },
            isBounce: false,
            clearEmptyId: "dataList",
            warpId: "upscrollWarp",
            scrollbar: {
                use: false
            }
        }
    });
    G.goIndex();
})(GS);
