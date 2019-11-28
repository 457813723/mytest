(function ($, D, doc, win){

    D._mix(D, {
        /**
         *
         * @param options
         */
        fontSize: (function (options){

            var defaults = {
                    setup: '100*640'
                },
                opts = $.extend(true, {}, defaults, options),
                docEl = doc.documentElement,
                str = opts.setup.split('*'),
                size = str[0],
                Windth = str[1],
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function (){
                    var clientWidth = docEl.clientWidth;
                    if(!clientWidth) return;
                    if(D.config('setWindow').setUpSize){
                        docEl.style.fontSize = size * (clientWidth / Windth) + 'px';
                    } else {
                        if(clientWidth >= Windth){
                            docEl.style.fontSize = size + 'px';
                        } else {
                            docEl.style.fontSize = size * (clientWidth / Windth) + 'px';
                        }
                    }
                };
            if(!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            doc.addEventListener('DOMContentLoaded', recalc, false);

            $(doc).ready(function ($){
                $('body').removeClass('gs-layout');
            });
        })({setup: $('body').data('layout')}),
        API: (() => {
            return {
                // 获取用户信息
                INDEX_USER_INFO: '/api/index_user_info',
                // 生成二维码
                CREATE_QRCODE: '/api/create_qrcode',
                // 推广佣金提现 统计数据
                CAN_CARRY_INFO: '/api/can_carry_info',
                // 收益提现记录
                OPERATE_RECORD: '/api/operate_record',
                // 提现
                DISTRIBUTE_TO_GAMES: '/api/distribute_to_games',
                // 我的茶楼
                SUBORDINATE: '/api/subordinate',
                // 添加代理
                OPEN_AGENT: '/api/open_agent',
                // 今日新增扫码
                NEW_SCAN_TODAY: '/api/new_scan_today',
                //
                GENERALIZE_TODAY_TOTAL: '/api/generalize_today_total',
                // 大区收益明细
                REGION_YESTERDAY: '/api/region_yesterday',
                // 大区收益明细-搜索下级代理
                REGION_YESTERDAY_PLAYER_ID: '/api/region_yesterday_player_id',
                // 整线总业绩
                WHOLE_LINE: '/api/whole_line',
                // 授权大区
                REGION_AGENT_AUTHOR: '/api/region_agent_author',
                // 设置大区比例
                SET_PRORATA: '/api/set_prorata',
                // 查询被赠送玩家
                SEARCH_PLAYER: '/api/search_player',
                // 赠送好友
                GIVE_COINS: '/api/give_coins',
                // 检查保险箱的余额
                SAFE_BOX_COINS: '/api/safe_box_coins',
                // 赠送记录
                GIVE_FRIEND_RECORD: '/api/give_friend_record',
                // 登录
                INDEX: '/api/index?rid=2000854&code=2000854',
            };
        })(),
        /**
         *
         * @param attr
         * @param loadFun
         * @param callback
         */
        attrJudge: function (attr, loadFun, callback){
            if(typeof window[attr] !== "undefined"){
                loadFun();
            }
            callback && callback();
        },
        /**
         *
         * @param options
         * @param success
         * @param error
         */
        ajax: (options, success, error) => {
            let defaults = {url: '', type: 'POST', dataType: 'json', data: {}};
            let {url, type, dataType, data} = {...defaults, ...options};
            $.ajax({
                url: url,
                type: type,
                dataType: dataType,
                data: data,
                success: res => {
                    switch (res.state) {
                        case 0:
                            location.href = res.downloadUrl;
                            D.isFunction(error) && error(res);
                            break;
                        case 1:
                            D.isFunction(success) && success(res);
                            break;
                        default :
                            if(D.isFunction(error)){
                                error(res);
                            }else{
                                D.msg(res.msg);
                            }

                    }

                },
                error: () => {
                    console.log('链接请求失败');
                }
            });
        },
        /**
         * random
         */
        notData: function (){
            D.getLogger('notData').warn('Please remove “D.notData” This is just a demonstration');
            return Math.random() > 0.5;
        },
        /**
         *
         * @param option
         */
        showNothing: function (option){
            var opt = $.extend({
                    word: '没有数据',
                    icon: 'gs-icon-nodata-130x163',
                    skin: '',
                    css: null
                }, option || {}),
                $html = $(D.format('<div class="gs-box-nodata {skin}"><div class="gs-box-nodata-text">{word}</div></div>', opt));

            opt.icon &&
            $html.find('.gs-box-nodata-text').prepend(D.format('<i class="gs-box-nodata-icon {0}"></i>', opt.icon));
            option && opt.css && $html.css(opt.css);
            return $html.get(0).outerHTML;
        },
        /**
         * @param $btn
         * @param enabled
         * @param word
         * @returns {boolean}
         */
        enableBtn: function ($btn, enabled, word){
            if(!$btn)
                return false;
            var DISABLED = 'disabled',
                DATA_NAME = 'cache_html';
            if(D.isUndefined(enabled)){
                enabled = $btn.hasClass(DISABLED);
            } else {
                if(enabled == !$btn.hasClass(DISABLED)){
                    return false;
                }
            }
            if(enabled){
                $btn.removeClass(DISABLED).removeAttr(DISABLED);
                word = $btn.data(DATA_NAME);
                word && $btn.html(word);
            } else {
                $btn.addClass(DISABLED).attr(DISABLED, DISABLED);
                if(word){
                    $btn.data(DATA_NAME, $btn.html());
                    $btn.html(word);
                }
            }
        },
        /**
         * pageLoaded
         */
        pageLoaded: {
            start: function (options){
                D.layer.pageLoaded = D.layer.open($.extend({
                    type: 2,
                    content: '加载中',
                    shadeClose: false,
                    skin: 'page-loaded'
                }, options));
            },
            done: function (callback){
                D.layer.close(D.layer.pageLoaded);
                D.isFunction(callback) && callback();
            }
        },
        /**
         * @param obj
         */
        loading: {
            start: function (obj){
                obj.prepend('<div class="gs-loading"><i></i></div>');
            },
            done: function (obj){
                obj.find('.gs-loading').remove();
            }
        },
        loggingBtn: function ($inputs, $btn){
            $inputs.bind('keyup', function (){
                var count = 0;
                $inputs.each(function (i, item){
                    var text = D.trim($(item).val());
                    if(text && text.length > 0){
                        count++;
                    }
                });
                count == $inputs.length ?
                    D.enableBtn($btn, true) :
                    D.enableBtn($btn, false);
            });
        },
        delval: function (){
            $(document).on({
                focus: function (){
                    var $t = $(this);
                    $t.addClass("focus").val() == this.defaultValue && $t.val("");
                },
                blur: function (){
                    var $t = $(this);
                    $t.removeClass("focus").val() == '' && $t.val(this.defaultValue);
                },
            }, '[data-bai-delval]');
        },
        goIndex: function (){
            var gsHref = {
                // 首页
                index: "../../index"
                // 二维码
                , code_scanning: "./pages/code_scanning/code_scanning"
                //推广佣金提现
                , extract_withdrawal_benefit: "../../pages/extract_withdrawal_benefit/extract_withdrawal_benefit"
                //我的茶楼
                , my_club: "./pages/my_club/my_club"
                // 今日新增扫码
                , new_scan_today: "./pages/new_scan_today/new_scan_today"
                //今日推广收益
                , income_details: "./pages/income_details/income_details"
                // 大区收益明细
                , incomebreakdown: "./pages/incomebreakdown/incomebreakdown"
                // 整线总业绩
                , totalperformance: "./pages/totalperformance/totalperformance"
                // 授权大区代理
                , regionalagent: "./pages/regional_agent/regional_agent"
                //赠送亲友
                , givefriend: "./pages/give_friend/give_friend"
                //历史记录
                , historyrecord: "../../pages/history_record/history_record"
                //返回上一页
                , history: "javascript:history.go(-1)"



                //对局记录
                , game_record: "./pages/game_record/game_record.html"
                //操作记录
                , extract_operation_record: "../../pages/extract_operation_record/extract_operation_record.html"
            };

            $('.gs-go-index').attr('href', gsHref.index);
            $('.gs-go-code-scanning').attr('href', gsHref.code_scanning);
            $('.gs-go-my-club').attr('href', gsHref.my_club);
            $('.gs-go-new-scan-today').attr('href', gsHref.new_scan_today);
            $('.gs-go-income-details').attr('href', gsHref.income_details);
            $('.gs-go-income-breakdown').attr('href', gsHref.incomebreakdown);
            $('.gs-go-totalper-formance').attr('href', gsHref.totalperformance);
            $('.gs-go-regiona-lagent').attr('href', gsHref.regionalagent);
            $('.gs-go-give-friend').attr('href', gsHref.regionalagent);
            $('.gs-go-history-record').attr('href', gsHref.historyrecord);
            // 返回上一页
            $('.gs-go-history').attr('href', gsHref.history)


            $('.gs-go-extract-withdrawal-benefit').attr('href', gsHref.extract_withdrawal_benefit);
            $('.gs-go-give-friend').attr('href', gsHref.givefriend);

//            $('.gs-go-extract-operation-record').attr('href', gsHref.extract_operation_record);
        },
        reload: function (){
            window.location.reload();
        },
        /**
         *
         * @param o
         */
        copyArticle: function (obj_clik, obj_txt){
            document.getElementById(obj_clik).addEventListener('click', function (){
                const range = document.createRange();
                range.selectNode(document.getElementById(obj_txt));
                const selection = window.getSelection();
                if(selection.rangeCount > 0) selection.removeAllRanges();
                selection.addRange(range);
                document.execCommand('copy');
                //授权提示
                layer.open({
                    content: '复制成功'
                    , skin: 'msg'
                    , time: 2 //2秒后自动关闭
                    , end: function (){

                    }
                });
            }, false);
        },
        sessionStorage: (() => {
            let set = (name, val) => {
                //存储
                let str = JSON.stringify(val);
                sessionStorage[name] = str;
            };
            let get = (name) => {
                //取出
                let str = sessionStorage[name];
                str = JSON.parse(str);
                return str;
            };
            return {
                set: set,
                get: get
            };
        })()
    });
    D._mix($.fn, {
        getCodeTimer: function (options, callback){
            if(!this.length){
                return this;
            }
            var defaults = {
                    time: 60,
                    textH: '发送验证码',
                    textS: 'S后重新获取'
                },
                opts = $.extend(true, {}, defaults, options),
                type = $.isFunction(options);
            if(type){
                callback = options;
            }
            var
                $this = $(this),
                second = opts.time,
                reduceSecond;
            reduceSecond = function (){
                if(--second > 0){
                    $this.attr("disabled", "disabled").addClass('disabled').html(second + opts.textS);
                    setTimeout(reduceSecond, 1000);
                    return;
                }
                $this.removeAttr("disabled").removeClass('disabled').html(opts.textH);
                second = opts.time;
                callback && $.isFunction(callback) && callback.call(this);
            };
            !$this.hasClass('disabled') && reduceSecond();

        },
        textMax: function (){
            var $t = $(this),
                text = $t.val(),
                len = text.length,
                maxLength = $t.attr('maxlength'),
                oText = $t.siblings('.gs-result').find('em');
            if(len > maxLength){
                text = text.substring(0, maxLength);
                $t.val(text);
                len = maxLength;
            }
            oText && oText.html(len);
        }
    });
})(Zepto, GS, document, window);
(function ($, D){
    D.attrJudge('layer', function (){
        /**
         * 对话框扩展
         * @options  {icon:{-1:'默认没有图标',0:'感叹号',1：'勾正确',2:'叉错误',3:'问号'，4：'锁符号',5：'红哭脸',6：'绿笑脸',N>6:'感叹号'}}
         */
        D._mix(D, {
            layer: layer,
            /**
             * @param msg
             * @param options
             * @param yes
             * @returns {*}
             */
            alert: function (msg, options, yes){
                var type = D.isFunction(options);
                if(type){
                    yes = options;
                }
                return D.layer.open($.extend({
                    content: msg,
                    shadeClose: true,
                    btn: '我知道了',
                    yes: function (index){
                        yes && D.isFunction(yes) && yes.call(this);
                        D.layer.close(index);
                    }
                }, type ? {} : options));
            },
            /**
             *
             * @param msg
             * @param options
             * @param callback
             */
            msg: function (msg, options, callback){

                var type = D.isFunction(options);
                if(type){
                    callback = options;
                }
                return D.layer.open($.extend({
                    content: msg,
                    skin: 'msg',
                    time: 2,
                    yes: function (index){
                        callback && D.isFunction(callback) && callback.call(this);
                        D.layer.close(index);
                    }
                }, type ? {} : options));
            },
            /**
             *
             * @param msg
             * @param options
             * @param ok
             * @param cancel
             * @returns {*}
             */
            confirm: function (msg, options, ok, cancel){
                var type = D.isFunction(options);
                if(type){
                    cancel = ok;
                    ok = options;
                }
                return D.layer.open($.extend({
                    content: msg,
                    btn: ['&#x786E;&#x5B9A;', '&#x53D6;&#x6D88;'],
                    btn2: cancel,
                    yes: function (index){
                        ok && D.isFunction(ok) && ok.call(this);
                        D.layer.close(index);
                    }
                }, type ? {} : options));
            },
            /**
             *
             * @param msg
             * @param options
             * @param ok
             * @param cancel
             * @returns {*}
             * 需要在回调主动关闭弹框
             */
            confirm2: function (msg, options, ok, cancel){
                var type = D.isFunction(options);
                if(type){
                    cancel = ok;
                    ok = options;
                }
                return D.layer.open($.extend({
                    content: msg,
                    btn: ['&#x786E;&#x5B9A;', '&#x53D6;&#x6D88;'],
                    btn2: cancel,
                    yes: function (index){
                        ok && D.isFunction(ok) && ok.call(this);
                    }
                }, type ? {} : options));
            },
            /**
             *
             * @param url
             * @param options
             */
            showImage: function (url, options){
                D.layer.open(D.mix({
                    content: '<img src="' + url + '" alt="">',
                    skin: 'gs-show-image'
                }, options));
            },
            /**
             * showShare
             * @param options
             * wxType 0 分享好友分享朋友圈  1 分享给好友  2 分享给朋友圈
             */
            showShare: function (options){
                layer.open(D.mix({
                    content: D.format('<img src="{0}/images/uiParts/icon-weixin-share{1}.png' + '" alt="">', D.sites.static, options ? options.wxType : 0),
                    skin: 'header'
                }, options));
            }
        });
    });
})(Zepto, GS);
