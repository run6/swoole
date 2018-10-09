<?php


//创建Server对象，监听 127.0.0.1:9501端口

$serv = new swoole_server("127.0.0.1", 9501);

$serv->set([
    'worker_num'    => 4,
    'max_request'   => 1000
]);

//监听连接进入事件
/**
 * $fd  是客户端连接的唯一标识
 * $reactor_id 线程id
 */
$serv->on('connect', function ($serv, $fd ,$reactor_id) {
    echo " Client {$reactor_id} - {$fd}: Connect.\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    echo $data."\n";
    $serv->send($fd, "Server {$from_id} - {$fd} : ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: {$fd}  Close.\n";
});

//启动服务器
$serv->start();