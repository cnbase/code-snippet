/**
 * 一套轮询请求机制，实现定时请求且不会因为服务端异常而堵塞
 * @type {string}
 */
var url = '/test.php';
var data = {s:'123'};
post(url,data);
// 休眠 函数
function sleep(n){
    var start=new Date().getTime();
    while(true) if(new Date().getTime()-start>n) break;
}
// 递归post函数
function post(url,data) {
    $.ajax({
        url:url,
        type:'post',
        data:data,
        dataType:'json',
        success:function (res) {
            if (res.status == "1"){
                // 成功，睡眠 5 秒
                sleep(5000);
                console.log(new Date().getTime());
                console.log(res.info);
                post(url,data);
            } else {
                // 服务器查询结果出错，刷新页面
            }
        },
        error:function () {
            // 请求出错，刷新页面
        }
    });
}