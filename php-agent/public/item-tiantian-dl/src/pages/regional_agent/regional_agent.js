(function (G){
    GS._mix(GS, {
        // 数据请求
        getListDataFromNet: function (pageNum, pageSize, successCallback, errorCallback){
            let keyword = $('#circle-child-id').val();
            G.ajax({
                url: G.API.REGION_AGENT_AUTHOR,
                data: {keyword: keyword, page: pageNum, size: pageSize}
            }, function (data){
//                data.ret.rows = [
//                    {
//                        agentId: 'agentId',
//                        nickName: 'nickName',
//                        ratio: 'ratio',
//                        operate: 'operate',
//                    }
//                ];
                successCallback(data.ret.rows.map((item) => {
                    return {
                        // 代理ID
                        agentId: item.agentId || '-',
                        // 代理昵称
                        nickName: item.nickName || '-',
                        // 分成比例
                        ratio: item.ratio || '-',
                        // 分成比例设置
                        operate: item.operate || '<a class="gs-a01 gs-authorization-ratio" href="javascript:void(0);">授权比例</a>',
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
                str += `<div class="gs-table-td gs-agentid" data-agentid="${value.agentId}">${value.agentId}</div>` +
                    `<div class="gs-table-td">${value.nickName}</div>` +
                    `<div class="gs-table-td gs-proportion-1">${value.ratio}</div>` +
                    `<div class="gs-table-td">${value.operate}</div>`
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
                url: G.API.OPEN_AGENT,
                data: {playerId: $t.attr('data-playerid')}
            }, function (){
                G.msg('开通成功！');
                $t.parents('.gs-table-td').html('已是代理');
            })
        })
        // 操作比例
        .on('click', '.gs-authorization-ratio', function (){
            let agent_id = $(this).parents('li').find('.gs-agentid').attr('data-agentid');
            let gs_proportion_1 = $(this).parents('li').find('.gs-proportion-1');
            G.confirm(`<div class="gs-box"><div class="gs-col-4 f-tar mr5 mt10">分成比例：</div><div class="gs-col-6"><input id="gs-proportion" type="text"></div></div>`, {
                title: '授权比例'
            }, function (){
                let prorata = $('#gs-proportion').val();
                if(/(^[1-9][0-9]$|^[0-9]$|^100$)/.test(prorata)){
                    G.ajax({
                        url: G.API.SET_PRORATA,
                        data: {
                            agent_id: agent_id,
                            prorata: prorata
                        }
                    }, function (ret){
                        G.msg(ret.ret);
                        ret.state == 1 && gs_proportion_1.text(prorata + '.00');
                    });
                } else {
                    G.msg('请输入1-100整数');
                }
            });
        });

    G.goIndex();
})(GS);



