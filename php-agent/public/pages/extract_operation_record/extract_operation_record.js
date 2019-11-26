(function (G){
    const now = G.formatDate(G.now());
    const input_date = '#gs-game-extract-operation-record';
    $(input_date).val(now);
    $('body').on('click', input_date, function (){
        let $t = $(this);
        new DatePicker({
            "type": "3",//0年, 1年月, 2月日, 3年月日
            "title": '请选择日期',//标题(可选)
            "maxYear": "",//最大年份（可选）
            "minYear": "",//最小年份（可选）
            "separator": "-",//分割符(可选)
            "defaultValue": $t.value,//默认值（可选）
            "callBack": function (val){
                //回调函数（val为选中的日期）
                $t.val(val);
            }
        });
    });
})(GS);
$(function (){
    let userinfo = GS.sessionStorage.get('userinfo');
    $('#myid').text(userinfo.myId);
//     $('#circleName').text(userinfo.circleName);
    $('#circleId').text(userinfo.club_id);
    var mescroll = new MeScroll("mescroll", {
        up: {
            callback: function (page, mescroll){
                getListData(page);
            },
            isBounce: false,
            clearEmptyId: "dataList",
            warpId: "upscrollWarp",
            scrollbar: {
                use: false
            }
        }
    });
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
    //搜索
    $('body').on('click', '#circle-child-soso', function (){
        mescroll.resetUpScroll();
    });

    function getListData(page){
        getListDataFromNet(page.num, page.size, function (curPageData, totalSize){
            //mescroll会根据传的参数,自动判断列表如果无任何数据,则提示空;列表无下一页数据,则提示无更多数据;
            console.log("page.num=" + page.num + ", page.size=" + page.size + ", curPageData.length=" + curPageData.length);
            //必传参数(当前页的数据个数, 总数据量)
            mescroll.endBySize(curPageData.length, totalSize);
            setListData(curPageData);
        }, function (){
            //联网失败的回调,隐藏下拉刷新和上拉加载的状态;
            mescroll.endErr();
        });
    };

    /*设置列表数据*/
    function setListData(curPageData){
        let str = '';
        curPageData.forEach(function (value){
            str += `<li>`;
            str += `<div class="gs-table-td">${value.gameId ? value.gameId : '-'}</div>` +
                `<div class="gs-table-td">${value.nickName ? value.nickName : '-'}</div>` +
                `<div class="gs-table-td">${value.timeCreation ? value.timeCreation : '-'}</div>` +
                `<div class="gs-table-td">` +
                `<span class="gs-game-span" data-ykt="true">${value.statusAgent}</span>` +
                `</div>`;
            str += `</li>`;
        });
        $('#dataList').append(str);
    };

    /*加载列表数据* */
    function getListDataFromNet(pageNum, pageSize, successCallback, errorCallback){
        let time = $('#gs-game-extract-operation-record').val();
        //延时一秒,模拟联网
        setTimeout(function (){
            GS.ajax({
                url: '/api/operate_record',
                data: {bt: '2017-03-12', et: time, page: pageNum, size: pageSize}
            }, function (data){
                let newArr = data.ret.list.map((item, index, arr) => {
                    let json = {};
                    json.gameId = item.playerId;
                    json.nickName = item.nickName;
                    json.typeGame = item.userType;
                    json.timeCreation = GS.formatDate(item.dateTime, 'yyyy-MM-dd');
                    json.statusAgent = item.userType;
                    return json;
                });
                successCallback(newArr, data.ret.total);
            }, function (){
                errorCallback
            });
        }, 500)
    };
});

