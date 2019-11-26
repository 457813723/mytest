(function (G){
    GS._mix(GS, {
        // 数据请求
        getListDataFromNet: function (pageNum, pageSize, successCallback, errorCallback){
            G.ajax({
                url: G.API.GIVE_FRIEND_RECORD,
                data: { page: pageNum, size: pageSize}
            }, function (data){
//                data.ret.rows = [
//                    {
//                        INCOME_ID: 'INCOME_ID',
//                        MONEY: 'MONEY',
//                        STATUS: 'STATUS',
//                        REG_TIME: 'REG_TIME',
//                    }
//                ];
                successCallback(data.ret.rows.map((item) => {
                    return {
                        // 玩家ID
                        INCOME_ID: item.INCOME_ID || '-',
                        // 发放金币
                        MONEY: item.MONEY || '-',
                        // 状态
                        STATUS: item.STATUS || '-',
                        // 时间
                        REG_TIME: item.REG_TIME || '-',
                    };
                }), data.ret.total);
            }, function (){
                errorCallback();
            });
        },
        //设置列表数据
        setListData: function (curPageData){
            let str = '';
            curPageData.forEach(function (value){
                str += `<li>`;
                str += `<div class="gs-table-td">${value.INCOME_ID}</div>` +
                    `<div class="gs-table-td">${value.MONEY}</div>` +
                    `<div class="gs-table-td">${value.STATUS}</div>` +
                    `<div class="gs-table-td">${value.REG_TIME}</div>`
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
                mescroll.optUp.onScroll = function (mescroll, y){
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



