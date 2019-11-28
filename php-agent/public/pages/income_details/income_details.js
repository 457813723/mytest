(function (G){
    const now = G.formatDate(G.now());
    const input_date_start = '#income-details-date-start';
    const input_date = '#income-details-date';

    $(input_date_start).val(now);
    $(input_date).val(now);
    $('body')
        .on('click', input_date_start, function (){
            let $t = $(this);
            new DatePicker({
                "type": "3",
                "title": '请选择日期',
                "maxYear": "",
                "minYear": "",
                "separator": "-",
                "defaultValue": $t.value,
                "callBack": function (val){
                    $t.val(val);
//                    startTime= new Date(Date.parse(val));
//                    endTime=new Date(Date.parse(endTime));

                }
            });
        })
        .on('click', input_date, function (){
            let $t = $(this);
            new DatePicker({
                "type": "3",
                "title": '请选择日期',
                "maxYear": "",
                "minYear": "",
                "separator": "-",
                "defaultValue": $t.value,
                "callBack": function (val){
                    $t.val(val);
                }
            });
        });
    GS._mix(GS, {
        // 数据请求
        getListDataFromNet: function (pageNum, pageSize, successCallback, errorCallback){
            G.ajax({
                url: G.API.GENERALIZE_TODAY_TOTAL,
                data: {bt: $(input_date_start).val(), et: $(input_date).val(), page: pageNum, size: pageSize}
            }, function (data){
                console.log(data);
                $('.gs-gold-total').html(data.ret.total+'金币');
//                data.ret.rows = [
//                    {
//                        playerId: 'playerId',
//                        nickName: 'nickName',
//                        playerType: 'playerType',
//                        countFee: 'countFee',
//                    }
//                ];
                successCallback(data.ret.rows.map((item) => {
                    return {
                        playerId: item.playerId || '-',
                        nickName: item.nickName || '-',
                        playerType: item.playerType || '-',
                        countFee: item.countFee || '-',
                    };
                }), data.ret.rows);
            }, function (){
                errorCallback();
            });
        },
        //设置列表数据
        setListData: function (curPageData){
            let str = '';
            curPageData.forEach(function (value){
                str += `<li>`;
                str += `<div class="gs-table-td">${value.playerId}</div>` +
                    `<div class="gs-table-td">${value.nickName}</div>` +
                    `<div class="gs-table-td">${value.playerType}</div>` +
                    `<div class="gs-table-td">${value.countFee}</div>`
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
    //搜索
    $('body').on('click', '#aui-flex-button-btn', function (){
        let startTime = new Date(Date.parse($(input_date_start).val()));
        let endTime = new Date(Date.parse($(input_date).val()));
        //进行比较
        if(startTime > endTime){
            G.msg('开始时间不能大于结束时间');
            return false;
        }
        mescroll.resetUpScroll();
    });
    G.goIndex();
})(GS);


//
//$(function (){
//    var mescroll = new MeScroll("mescroll", {
//        up: {
//            // 上拉 刷新
//            callback: function (page, mescroll){
//                getListData(page);
//            },
//            isBounce: false,
//            clearEmptyId: "dataList",
//            warpId: "upscrollWarp",
//            scrollbar: {
//                use: false
//            }
//        }
//    });
//    var navWarp = document.getElementById("navWarp");
//    if(mescroll.os.ios){
//        navWarp.classList.add("nav-sticky");
//    } else {
//        navWarp.style.height = navWarp.offsetHeight + "px";
//        var navContent = document.getElementById("navContent");
//        mescroll.optUp.onScroll = function (mescroll, y, isUp){
//            console.log("up --> onScroll 列表当前滚动的距离 y = " + y + ", 是否向上滑动 isUp = " + isUp);
//            if(y >= navWarp.offsetTop){
//                navContent.classList.add("nav-fixed");
//            } else {
//                navContent.classList.remove("nav-fixed");
//            }
//        }
//    }
//    //搜索
//    $('body').on('click', '#aui-flex-button-btn', function (){
//        mescroll.resetUpScroll();
//    });
//
//    function getListData(page){
//        getListDataFromNet(page.num, page.size, function (curPageData){
//            console.log("page.num=" + page.num + ", page.size=" + page.size + ", curPageData.length=" + curPageData.length);
//            //方法二(推荐): 后台接口有返回列表的总数据量 totalSize
//            //mescroll.endBySize(curPageData.length, totalSize); //必传参数(当前页的数据个数, 总数据量)
//            mescroll.endSuccess(curPageData.length);
//            setListData(curPageData);
//        }, function (){
//            //联网失败的回调,隐藏下拉刷新和上拉加载的状态;
//            mescroll.endErr();
//        });
//    };
//
//    /*设置列表数据*/
//    function setListData(curPageData){
//        let str = '';
//        curPageData.forEach(function (value){
//            str += `<li>`;
//            str += `<div class="gs-table-td">${value.gameId ? value.gameId : '-'}</div>` +
//                `<div class="gs-table-td">${value.nickName ? value.nickName : '-'}</div>` +
//                `<div class="gs-table-td">${value.timeCreation ? value.timeCreation : '-'}</div>` +
//                `<div class="gs-table-td">` +
//                `<span class="gs-game-span" data-ykt="true">${value.statusAgent}</span>` +
//                `</div>`;
//            str += `</li>`;
//        });
//        $('#dataList').append(str);
//    };
//
//    /*联网加载列表数据
//     在您的实际项目中,请参考官方写法: http://www.mescroll.com/api.html#tagUpCallback
//     请忽略getListDataFromNet的逻辑,这里仅仅是在本地模拟分页数据,本地演示用
//     实际项目以您服务器接口返回的数据为准,无需本地处理分页.
//     * */
//    function getListDataFromNet(pageNum, pageSize, successCallback, errorCallback){
//        let keyword = $('#circle-child-id').val();
//        //延时一秒,模拟联网
//        setTimeout(function (){
//            GS.ajax({
//                url: '/api/generalize_today_total',
//                data: {bt: '1900-00-00',et:'2019-03-20', page: pageNum, size: pageSize}
//            }, function (data){
//                let newArr = data.ret.rows.map((item, index, arr) => {
//                    let json = {};
//                    json.gameId = item.playerId;
//                    json.nickName = item.nickName;
//                    json.typeGame = item.userType;
//                    json.timeCreation = GS.formatDate(item.dateTime, 'yyyy-MM-dd');
//                    json.statusAgent = item.userType;
//                    return json;
//                });
//                successCallback(newArr);
//            }, function (){
//                errorCallback
//            });
//
//        }, 500)
//    };
//});
//
