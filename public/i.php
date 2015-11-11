<?php
//echo md5('123456') , '<br />' , md5('123123');exit;
phpinfo();exit;
$cipher_list = mcrypt_list_algorithms();//mcrypt支持的加密算法列表  
$mode_list = mcrypt_list_modes();   //mcrypt支持的加密模式列表
  
echo '<xmp>';
print_r($cipher_list);
print_r($mode_list);

