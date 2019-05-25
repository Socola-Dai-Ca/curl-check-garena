<?php

require('vendor/autoload.php');

$curl = new Curl\Curl();

$user = 'BaoNTg200902';

$pass = 'baontg200902';



$curl->get('https://sso.garena.com/api/prelogin?account=' . $user . '&format=json&id=' . time() . rand(500,999) . '&app_id=10100');

$token = json_decode($curl->response, true);

$curl->reset();

// Pass & key
$pass = md5($pass);
$key = hash('sha256', hash('sha256', $pass . $token['v1']) . $token['v2']);

// sử dụng trang web bên thứ 3 để lấy mật khẩu mã hoá
$curl->post('http://www.cryptogrium.com/crypto.php', 'optype=aes_ecb&operation=encrypt&blocksize=256&key=' . $key . '&input=' . $pass);
$pass = strip_tags($curl->response); // loại bỏ html trong response chỉ giữ lại phần pass đã mã hoá
$curl->reset();


// Thông nát garena
$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
$curl->get('https://sso.garena.com/api/login?account=' . $token['acccount'] . '&password=' . $pass . '&redirect_uri=https%3A%2F%2Faccount.garena.com%2F&format=json&id=' . $token['id'] . '&app_id=10100');

$data = json_decode($curl->response, true);

// var_dump($data); 
if ($data['uid']) {
	echo 'Tài Khoản hợp lệ';
} else {
	echo 'Tài Khoản sai thông tin';
}

$curl->close();





