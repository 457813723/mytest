(function (G){
    $('body')
        .on('click', '#gs-game-record-date', function (){
            let $t = $(this);
            new DatePicker({
                "type": "3",
                "title": '请选择日期',
                "maxYear": "",
                "minYear": "",
                "separator": "-",
                "defaultValue": GS.formatDate(GS.now()),
                "callBack": function (val){
                    $t.val(val);
                }
            });
        });
})(GS);
$(function (){
    GS._mix(GS, {
        // 数据请求
        getListDataFromNet: function (pageNum, pageSize, successCallback, errorCallback){
            let userId = $('#gs-flex-search').val();
            let date = $('#gs-game-record-date').val();
            GS.ajax({
                // todo tiantian-无 暂无此界面
                url: '/api/game_records',
                data: {page: pageNum, size: pageSize, userId: userId, date: date}
            }, function (data){
                let newArr = data.ret.rows.map((item) => {
                    let json = {};
                    json.gameId = item.gameid;
                    json.nickName = item.rolename;
                    json.typeGame = item.reason;
                    json.timeCreation = item.num;
                    json.statusAgent = GS.formatDate(item.time_stamp, 'yyyy-MM-dd');
                    return json;
                });
                successCallback(newArr);
            }, function (){
                errorCallback
            });
        },
        //设置列表数据
        setListData: function (curPageData){
            let str = '';
            curPageData.forEach(function (value){
                str += `<li>`;
                str += `<div class="gs-table-td">${value.gameId ? value.gameId : '-'}</div>` +
                    `<div class="gs-table-td">${value.nickName ? value.nickName : '-'}</div>` +
                    `<div class="gs-table-td">${value.typeGame}</div>` +
                    `<div class="gs-table-td">${value.timeCreation ? value.timeCreation : '-'}</div>` +
                    `<div class="gs-table-td">` +
                    `<span class="gs-game-span" data-ykt="true">${value.statusAgent}</span>` +
                    `</div>`;
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
                var navContent = document.getElementById("navContent");
                mescroll.optUp.onScroll = function (mescroll, y, isUp){
                    console.log("up --> onScroll 列表当前滚动的距离 y = " + y + ", 是否向上滑动 isUp = " + isUp);
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
            // 上拉 刷新
            callback: function (page, mescroll){
                GS.getListDataFromNet(page.num, page.size, function (curPageData){
                    console.log("page.num=" + page.num + ", page.size=" + page.size + ", curPageData.length=" + curPageData.length);
                    // mescroll.endBySize(curPageData.length, totalSize); //必传参数(当前页的数据个数, 总数据量)
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
    GS.navWarpOnScroll(mescroll);
    /**
     * 搜索
     */
    $('body').on('click', '#game-query-soso', function (){
        mescroll.resetUpScroll();
    });
});

