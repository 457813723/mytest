(function (G){
    G.ajax({url: '/api/create_qrcode'}, (data) => {
        $("#container").erweima({
            label: '天神娱乐',
            text: data.ret
        });
        G.goIndex();
    });
    G.goIndex();
})(GS);
