(function (G){
    let pdType = 0;//全部0; 玩家1; 代理2;
    $(".nav p").click(function (){
        var i = $(this).attr("i");
        if(pdType != i){
            //更改列表条件
            pdType = i;
            $(".nav .active").removeClass("active");
            $(this).addClass("active");
            //重置列表数据
            mescroll.resetUpScroll();
            //隐藏回到顶部按钮
            mescroll.hideTopBtn();
        }
    });
    GS._mix(GS, {
        // 数据请求
        getListDataFromNet: function (pageNum, pageSize, successCallback, errorCallback){
            console.log(pdType);
            G.ajax({
                url: G.API.NEW_SCAN_TODAY,
                data: {type: pdType, page: pageNum, size: pageSize}
            }, (data) => {
//                data.ret.rows = [
//                    {
//                        playerId: 'asdasd',
//                        nickName: 'asdasd',
//                        userType: 'asdasd',
//                        dateTime: '2019-3-27',
//                    }
//                ];
                let newArr = data.ret.rows.map((item) => {
                    return {
                        playerId: item.playerId || '-',
                        nickName: item.nickName || '-',
                        userType: item.userType || '-',
                        dateTime: item.dateTime || '-',
                    };
                });
                successCallback(newArr, data.ret.rows);
            }, function (){
                errorCallback();
            });
        },
        //设置列表数据
        setListData: function (curPageData){
            let str = '';
            curPageData.forEach((value) => {
                str += `<li>`;
                str += `<div class="gs-table-td">${value.playerId}</div>` +
                    `<div class="gs-table-td">${value.nickName}</div>` +
                    `<div class="gs-table-td">${value.userType}</div>` +
                    `<div class="gs-table-td">${value.dateTime}</div>`
                str += `</li>`;
            });
            $('#dataList').append(str);
        },
        // 浮动层
        navWarpOnScroll: function (){
            let navWarp = document.getElementById("navWarp");
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
                let pageIndex = page.num - 1;
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



