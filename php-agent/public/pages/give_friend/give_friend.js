(function (G){
    let bindData=function(){
        // 当前保险箱数为
        G.ajax({
            url: G.API.SAFE_BOX_COINS,
        }, function (data){
            $('.gs-safe-1').text(data.ret);
        });
    };
    bindData();
    $('body')
    //搜索
        .on('click', '#circle-child-soso', function (){
            G.ajax({
                url: G.API.SEARCH_PLAYER,
                data: {playerId: $('#circle-child-id').val()},
            }, function (data){
                let datas = data.ret;
                if(datas.searchPlayerId){
                    //       2001678
                    if(datas.searchPlayerInfo.HEADIMG.length < 2){
                        $('.prompt-text-2').find('img').attr('src', '../../images/home/logoError.png');
                    } else {
                        $('.prompt-text-2').find('img').attr('src', datas.searchPlayerInfo.HEADIMG);
                    }
                    $('.prompt-text-3').attr('data-id', datas.searchPlayerInfo.USER_ID);
                    $('.prompt-text-3').attr('data-uname', datas.searchPlayerInfo.U_NAME);
                    $('.prompt-text-3').find('span').text(datas.searchPlayerInfo.U_NAME);
                    $('.prompt-text-1').addClass('hide');
                    $('.prompt-text-2').removeClass('hide');
                    $('.prompt-text-3').removeClass('hide');
                    $('#circle-child-sosos').removeAttr("disabled");
                } else {
//                    GS.msg('查询亲友游戏ID不存在');
                    $('.prompt-text-3').attr('data-id', '');
                    $('.prompt-text-3').attr('data-uname', '');
                    $('#circle-child-sosos').attr('disabled', 'disabled');
                    $('.prompt-text-1').removeClass('hide');
                    $('.prompt-text-2').addClass('hide');
                    $('.prompt-text-3').addClass('hide');
                }
            });

            return false;
            let $t = $(this);
            G.enableBtn($t, !1), mescroll.resetUpScroll(), G.later(function (){
                G.enableBtn($t, !0);
            }, 200);
        })
        // 发送金币
        .on('click', '#circle-child-sosos', function (){
            if('' == $('.prompt-text-3').attr('data-id')){
                G.msg('用户ID不能为空');
            } else {
                let src = $('.prompt-text-2').find('img').attr('src');
                let id = $('.prompt-text-3').attr('data-id');
                let uname = $('.prompt-text-3').attr('data-uname');
                let goldval = $('#circle-child-ids').val();
                if(''==goldval){
                    G.msg('请输入金币数量');
                    return false;
                }
                if(goldval>$('.gs-safe-1').text()){
                    G.msg('保险箱金币数数量不足');
                    return false;
                }
                let hl = `<table class="gs-table-default gs-table-default-gold"><tr><td class="f-tar mr5"><img style="height:1rem;border-radius: 50%;" src="${src}" alt=""></td><td class="f-tal">${uname}</td></tr><tr><td class="f-tar mr5">金币:</td><td class="f-tal">${goldval}</td></tr></table>`;
                G.confirm(hl, {
                    title: '发送金币确认'
                }, function (){
                    G.ajax({
                        url: G.API.GIVE_COINS,
                        data: {
                            coins: $('#circle-child-ids').val(),
                            playerId: $('.prompt-text-3').attr('data-id')
                        },
                    }, function (data){
                        if(data.state==1){
                            bindData();
                        }else {
                            G.msg(data.msg);
                        }
                    });
                });
            }

            return false;
            let $t = $(this);
            G.enableBtn($t, !1), mescroll.resetUpScroll(), G.later(function (){
                G.enableBtn($t, !0);
            }, 200);
        });
    G.goIndex();
})(GS);



