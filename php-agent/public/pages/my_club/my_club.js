(function (G){
    GS._mix(GS, {
        // 数据请求
        getListDataFromNet: function (pageNum, pageSize, successCallback, errorCallback){
            let keyword = $('#circle-child-id').val();
            G.ajax({
                url: G.API.SUBORDINATE,
                data: {keyword: keyword, page: pageNum, size: pageSize}
            }, function (data){
//                data.ret.rows = [
//                    {
//                        playerId: '2000429',
//                        nickName: 'nickName',
//                        userType: '玩家',
//                    }
//                ];
                let newArr = data.ret.rows.map((item) => {
                    let json = {};
                    json.playerId = item.playerId;
                    json.nickName = item.nickName;
                    json.userType = item.userType;
                    json.operating = item.userType == '玩家' ? `<a class="gs-a01 gs-stop-club" data-playerId="${item.playerId}" href="javascript:void(0);">开通</a>` : '已是代理';
                    return json;
                });
                successCallback(newArr, data.ret.rows);
            }, function (){
                errorCallback
            });
        },
        //设置列表数据
        setListData: function (curPageData){
            let str = '';
            curPageData.forEach(function (value){
                str += `<li>`;
                str += `<div class="gs-table-td">${value.playerId ? value.playerId : '-'}</div>` +
                    `<div class="gs-table-td">${value.nickName ? value.nickName : '-'}</div>` +
                    `<div class="gs-table-td">${value.userType ? value.userType : '-'}</div>` +
                    `<div class="gs-table-td">${value.operating ? value.operating : '-'}</div>`
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
    $('body')
    //搜索
        .on('click', '#circle-child-soso', function (){
            let $t = $(this);
            G.enableBtn($t, !1), mescroll.resetUpScroll(), G.later(function (){
                G.enableBtn($t, !0);
            }, 200);
        })
        // 开通代理
        .on('click', '.gs-stop-club', function (){
            let $t = $(this);
            G.ajax({
                url: '/api/addAgent.php',
                data: {playerId: $t.attr('data-playerid')}
            }, function (){
                G.msg('开通成功！');
                $t.parents('.gs-table-td').html('已是代理');
            })
        });
    G.goIndex();
})(GS);



