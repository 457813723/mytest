(function (G){
    G.ajax({url: '/api/create_qrcode.php'}, (data) => {
        console.log(data);
        $("#container").erweima({
            label: '天神娱乐',
            text: data.url
        });
        G.goIndex();
    });
    // G.ajax({url: '/api/create_qrcode.php'}, function(data) {
    //     $("#container").erweima({
    //         label: '天神娱乐',
    //         text: data.ret
    //     });
    //     G.goIndex();
    // });
    // G.goIndex();
})(GS);
