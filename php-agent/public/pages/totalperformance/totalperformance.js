(function (G){
    const now = G.formatDate(G.now());
    const input_date_start = '#income-details-date-start';
    const input_date = '#income-details-date';
    let playerId = G.sessionStorage.get('userinfo').playerId;
    let solistid = [{
        text: playerId,
        id: ''
    }];
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
//                    $('#aui-flex-button-btn').trigger("click");
                    $t.val(val);
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
            let soval = $('#income-details-date-starts').val();
            // 用户复制已有ID搜索
            if(soval != ''){
                let ret2 = solistid.findIndex((v) => {
                    return v.id == soval;
                });
                if(-1 != ret2){
                    solistid.splice(ret2+1, solistid.length-1);
                }
            }
            G.ajax({
                url: G.API.WHOLE_LINE,
                data: {
                    agentId: soval || solistid[solistid.length - 1].id,
                    bt: $(input_date_start).val(),
                    et: $(input_date).val(),
                    page: pageNum,
                    size: pageSize
                }
            }, function (data){
                /*菜单层级列表*/
                let li = '';
                solistid.forEach(function (element, index){
                    li += `<li class="gs-list-refresh" data-id="${element.id}">${element.text}</li>`;
                });
                $('.mescroll-show-left').find('ul').html(li)
                $('.gs-total-performance').text(data.ret.totalAch);
//                data.ret.rows = [
//                    {
//                        agentId: 'agentId',
//                        agentName: 'agentName',
//                        userType: 'userType',
//                        ach: 'ach',
//                        operate: 'operate',
//                    }
//                ];
                successCallback(data.ret.rows.map((item) => {
                    return {
                        // 游戏ID
                        agentId: item.agentId || '-',
                        // 昵称
                        agentName: item.agentName || '-',
                        // 类型
                        userType: item.userType || '-',
                        // 业绩
                        ach: item.ach || '-',
                        // 操作
                        operate: item.operate || '-',
                    };
                }), data.ret.rows);
            }, function (data){
                G.msg(data.msg);
                errorCallback();
            });
        },
        //设置列表数据
        setListData: function (curPageData){
            let str = '';
            curPageData.forEach(function (value){
                str += `<li>`;
                str += `<div class="gs-table-td">${value.agentId}</div>` +
                    `<div class="gs-table-td">${value.agentName}</div>` +
                    `<div class="gs-table-td">${value.userType}</div>` +
                    `<div class="gs-table-td">${value.ach}</div>` +

//                    `<div class="gs-table-td">操作</div>`
                        `<div class="gs-table-td" data-id="${value.agentId}" ><a href="javascript:void(0);" class="gs-a01">${playerId != value.agentId && solistid[solistid.length - 1].id != value.agentId ? '<span   class="gs-look-subordinate">查看下级</span>' : '-'}</a></div>`
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
                    mescroll.showEmpty();
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
        // 判断ID不为中文字符
        let soval = $('#income-details-date-starts').val();
        if('' != soval){
            let reg = /^[0-9]*$/;
            if(!reg.test(soval) || soval == 0){
                G.msg('请输入正确的ID');
                return false;
            }
        }
        //进行比较
        if(startTime > endTime){
            G.msg('开始时间不能大于结束时间');
            return false;
        }
        mescroll.resetUpScroll();
    });
    //菜单显示
    $('body')
        .on('click', '.gs-js-item', function (){
        let $of = $('.mescroll-show-right');
        let $conten = $('.mescroll-show-conten');
        $of.toggleClass('left-show')
        if($of.hasClass('left-show')){
            $of.css('left', '2rem');
            $conten.css('width', '5.5rem');
        } else {
            $of.css('left', '0rem');
            $conten.css('width', '0');
        }
    }).on('click', '.mescroll-show-conten', function (){
        $('.gs-js-item').trigger("click");
    })
    // 列表刷新
        .on('click', '.gs-list-refresh', function (){
            $('#income-details-date-starts').val('');
            solistid.splice($(this).index() + 1, solistid.length - 1);
            $('.gs-js-item').trigger("click");
            $('.aui-flex-button-btn').trigger("click");
        })
        // 查看下一级
        .on('click', '.gs-look-subordinate', function (){
            $('#income-details-date-starts').val('');
            let id = $(this).parents('.gs-table-td').attr('data-id');
            solistid.push({
                id: id,
                text: id
            });
            $('#aui-flex-button-btn').trigger("click");
        });
    G.goIndex();
})(GS);



