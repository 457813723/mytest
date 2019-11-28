(function (G){
    G._mix(G, {
        /**
         * 推荐码
         */
        superiorID:G.uri().id,
        // 下载地址
        downloadLink: {
            apk: '',
            ios: '',
        }
    })

    G._mix(G, {
        loadOpt:function(){
            // '推荐ID:5868654'
            if(G.uri().id){
                $('#invite_code').val(G.superiorID)
            }
        },
        /**
         * 浏览器事件
         */
        browserJudgment: function (){
            // 安卓
            if(G.UA.android){
                window.location.href = G.downloadLink.apk
            } else if(G.UA.ios){
                // ios
                window.location.href = G.downloadLink.ios
            } else {
                window.location.href = G.downloadLink.apk
            }
        },
        /**
         * 点击事件
         */
        clickInit: function (){
            // 发送验证码
            $('#authCodeBtn').on('click', function (){
                $(this).getCodeTimer(function (){
                    alert('2');
                    return false
                    G.ajax({
                        url: G.API.INDEX_USER_INFO,
                        data: {},
                    }, function (data){

                    }, function (res){
                        $('body').html(G.showNothing({
                            word: res.msg
                        }));
                    });
                });
            });
            // 已有账号跳转
            $('#tipJump').on('click', function (){
                G.browserJudgment()
            });
        },
        init:function (){
            G.loadOpt();
            G.clickInit();
        }
    });

    G.init();

})(GS);

