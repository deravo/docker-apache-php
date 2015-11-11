<?php
/**
 * 此函数执行一个模拟的HTTP请求，并返回HTTP请求的返回值
 *
 * @param String $url
 * @param Array $params
 * @param String $method
 * @return Mixed
 */
if ( !function_exists('httpRequest') ) {
    function httpRequest($url, $params = array(), $method = "get")
    {
        $ch = curl_init($url);
        //curl_setopt($ch, CURLOPT_URL, $url);

        if($params){
            $paramsArray = array();
            foreach ($params as $key=>$value){
                $paramsArray[] = $key . "=" . urlencode($value);
            }
            if($method){
                if(strtolower($method) == "post"){
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&",$paramsArray));
                }
                else {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, implode("&",$paramsArray));
                }
            }
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;//substr($result, 3);
    }
}
/*
 *  加密密码
 *  Deravo
 *  2014-12-19 11:55:54
 * */
function encode_password($str)
{
    return sha1( md5($str) . '123456');//config_item('password_salt') );
}

function json_output($code = 2000, $message = "", $data = "")
{
    $struc = array();
    $struc["code"] = $code;
    if ($message != "") {
        $struc["message"] = $message;
    }
    if ($data != "") {
        $struc["data"] = $data;
    }
    return jencode($struc, '', '');
}

function jencode($value, $frc="GBK", $toc="UTF-8")
{
    if ($frc != "" && $toc != "") {
        return json_encode(jencode_iconv($value, $frc, $toc));
    } else {
        return json_encode($value);
    }
}

function jdecode($str, $frc="", $toc="")
{
    if ($frc && $toc ) {
        return jencode_iconv(json_decode($str), $toc, $frc);
    } else {
        return json_decode($str);
    }
}

function jencode_iconv($m, $from, $to)
{
    switch(gettype($m)) {
        case 'integer':
        case 'boolean':
        case 'float':
        case 'double':
        case 'NULL':
            return $m;

        case 'string':
            return mb_convert_encoding($m, $to, $from);
        case 'object':
            $vars = array_keys(get_object_vars($m));
            foreach($vars as $key) {
                $m->$key = jencode_iconv($m->$key, $from ,$to);
            }
            return $m;
        case 'array':
            foreach($m as $k => $v) {
                $m[$k] = jencode_iconv($v, $from, $to);
            }
            return $m;
        default:
    }
    return $m;
}

function d()
{
    return date("Y-m-d H:i:s");
}

//时间比较函数，返回两个日期相差几秒、几分钟、几小时或几天
function DateDiff($date1, $date2, $unit = "")
{
    switch ($unit) {
        case 's':
            $dividend = 1;
            break;
        case 'i':
            $dividend = 60;
            break;
        case 'h':
            $dividend = 3600;
            break;
        case 'd':
            $dividend = 86400;
            break;
        default:
            $dividend = 86400;
    }
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    if ($time1 && $time2) {
        return (float)($time1 - $time2) / $dividend;
    }
    return false;
}

function str_format($str)
{
    return str_replace("\n", "<br />", $str);
}

function add_date($givendate, $hour=0, $day=0, $mth=0, $yr=0, $tp = 0)
{
    $cd = (!is_numeric($givendate)) ? strtotime($givendate) : $givendate;
    $newdate = mktime(date('H', $cd) + $hour, date('i', $cd), date('s', $cd), date('m', $cd) + $mth, date('d', $cd) + $day, date('Y', $cd) + $yr);
    if ($tp != 0) {
        $newdate = date('Y-m-d H:i:s', $newdate);
    }
    return $newdate;
}

function check_idc($id)
{
    if (!$id) {return FALSE;}
    $_province = array(11 => "北京",12 => "天津",13 => "河北",14 => "山西",15 => "内蒙古",21 => "辽宁",22 => "吉林",23 => "黑龙江",31 => "上海",32 => "江苏",33 => "浙江",34 => "安徽",35 => "福建",36 => "江西",37 => "山东",41 => "河南",42 => "湖北",43 => "湖南",44 => "广东",45 => "广西",46 => "海南",50 => "重庆",51 => "四川",52 => "贵州",53 => "云南",54 => "西藏",61 => "陕西",62 => "甘肃",63 => "青海",64 => "宁夏",65 => "新疆",71 => "台湾",81 => "香港",82 => "澳门",91 => "国外");
    //~ $idc= array("/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/", "/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/i");
    //~ if (!preg_match($idc[0], $sId) && !preg_match($idc[1], $sId))
    //~ {
        //~ return FALSE;
    //~ }
    if (strlen($id) != 15 && strlen($id) != 18)
    {
        return FALSE;
    }
    if (!isset($_province[(int)substr($id, 0, 2)]))
    {
        return FALSE;;
    }
    if (strlen($id) == 15)
    {
        $id = id_upgrade_Fto12($id);
    }
    if (!strtotime(substr($id, 6, 4)."-".substr($id, 10, 2)."-".substr($id, 12, 2)))
    {
        return FALSE;
    }
    $Wi = array(7,  9,  10,  5, 8,   4,  2,  1, 6,  3,  7,  9,  10,  5,  8,  4,  2,  1);
    $YY = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    $S = 0;
    for ($i = 0; $i <= 16; $i++)
    {
        $Ai = (int)substr($id, $i, 1);
        $S += $Ai * (int)$Wi[$i];
    }
    $Y = $S % 11;
    if ($YY[$Y] !=  strtoupper(substr($id, 17, 1)) )
    {
        return FALSE;
    }
    return TRUE;
}

/*15位身份证号升18位--------方法一*/
function id_upgrade_Fto12_a($id)
{
    $arrInt = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    $arrCh = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    $nTemp = 0;
    $num = substr($id, 0, 6) . '19' . substr($id, 6, strlen($id) - 6);
    for($i = 0; $i < 17; $i++)
    {
        $nTemp += (int)substr($num, $i, 1) * $arrInt[$i];
    }
    $num .= $arrCh[$nTemp % 11];
    return $num;
}
/*15位身份证号升18位--------方法二*/
function id_upgrade_Fto12($id)
{
    $id = substr($id, 0, 6) . "19" . substr($id, 6);
    $i = 0;
    $num = 0;
    $code = "";
    for ($i = 18; $i >= 2; $i--)
    {
        $num += (pow(2, ($i - 1)) % 11) * (int)substr($id, (18 - $i) , 1);
    }
    $num = $num % 11;
    switch ($num)
    {
        case 0: $code = "1"; break;
        case 1: $code = "0"; break;
        case 2: $code = "X"; break;
        default: $code = (12 - $num); break;
    }
    return $id . $code;
}

function check_phone($str, $type = 0)
{
    if ($type == 1)
    {
        return preg_match("/^(0)?(1[3-9])[0-9]{9}$/", $str);
    } else {
        return preg_match("/(^(1[3-9])[0-9]{9}$)|(^(010|02[0-9]|0[3-9][0-9]{2})(-)?[1-9][0-9]{6,7}$)/", $str);

    }
}

function format_telephone($str, $seperator = "&nbsp;")
{
    return substr($str, 0, 3) . $seperator . substr($str, 3, 4) . $seperator . substr($str, 6);
}

function get_microtime()
{
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (int)$sec);
}

function get_microtime_str()
{
    list($usec, $sec) = explode(' ', microtime());
    return ($sec . substr($usec, 2, 6));
}

function Pinyin($_String, $_Code='UTF8'){
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
                        "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
                        "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
                        "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
                        "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
                        "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
                        "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
                        "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
                        "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
                        "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
                        "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
                        "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
                        "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
                        "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
                        "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
                        "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
                        "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
                        "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
                        "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
                        "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
                        "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
                        "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
                        "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
                        "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
                        "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
                        "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
                        "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
                        "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
                        "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
                        "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
                        "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
                        "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
                        "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
                        "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
                        "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
                        "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
                        "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
                        "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
                        "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
                        "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
                        "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
                        "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey   = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data = array_combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        reset($_Data);
        if($_Code!= 'gb2312') $_String = _U2_Utf8_Gb($_String);
        $_Res = '';
        for($i=0; $i<strlen($_String); $i++) {
                $_P = ord(substr($_String, $i, 1));
                if($_P>160) {
                        $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536;
                }
                $_Res .= _Pinyin($_P, $_Data);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
}

function _Pinyin($_Num, $_Data)
{
        if($_Num>0 && $_Num<160 ){
                return chr($_Num);
        }elseif($_Num<-20319 || $_Num>-10247){
                return '';
        }else{
                foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
                return $k;
        }
}

function _U2_Utf8_Gb($_C)
{
        $_String = '';
        if($_C < 0x80){
                $_String .= $_C;
        }elseif($_C < 0x800) {
                $_String .= chr(0xC0 | $_C>>6);
                $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x10000){
                $_String .= chr(0xE0 | $_C>>12);
                $_String .= chr(0x80 | $_C>>6 & 0x3F);
                $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x200000) {
                $_String .= chr(0xF0 | $_C>>18);
                $_String .= chr(0x80 | $_C>>12 & 0x3F);
                $_String .= chr(0x80 | $_C>>6 & 0x3F);
                $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GBK', $_String);
}

function std_class_object_to_array($stdclassobject)
{
    $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
    foreach ($_array as $key => $value) {
        $value = (is_array($value) || is_object($value)) ? std_class_object_to_array($value) : $value;
        $array[$key] = $value;
    }
    return $array;
}
// print_r(std_class_object_to_array(json_decode($json_str)));

function object_array($array)
{
    if(is_object($array)) {
        $array = (array)$array;
    }
    if(is_array($array)) {
        foreach($array as $key => $value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

function ts(&$item, $val)
{
    $item =  preg_replace("/([\d]+)\.html/i", 'act_Read/\1', $item);
}

function getCSVdata($filename)
{
    $row = 1;//第一行开始
    if (($handle = fopen($filename, "r")) !== false)
    {
        while(($dataSrc = fgetcsv($handle)) !== false)
        {
            $num = count($dataSrc);
            for ($c=0; $c < $num; $c++)//列 column
            {
                if($row === 1)//第一行作为字段
                {
                    $dataName[] = $dataSrc[$c];//字段名称
                } else {
                    foreach ($dataName as $k=>$v)
                    {
                        if($k == $c)//对应的字段
                        {
                            $data[$v] = $dataSrc[$c];
                        }
                    }
                }
            }
            if(!empty($data))
            {
                $dataRtn[] = $data;
                unset($data);
            }
            $row++;
        }
        fclose($handle);
        return $dataRtn;
    }
}

/*
    method:     array_duplicated
    description:    返回数组中重复的值
    author:     Deravo
    update:     2013-11-15 16:42:08
*/
function array_duplicated($_array)
{
    if (!is_array($_array) || count($_array) < 2) {return;}
    return array_unique(array_diff_assoc($_array,array_unique($_array)));
}


/**
* 无符号32位右移
* @param mixed $x 要进行操作的数字，如果是字符串，必须是十进制形式
* @param string $bits 右移位数
* @return mixed 结果，如果超出整型范围将返回浮点数
*/
function shr32($x, $bits, $len = 32) {
    // 位移量超出范围的两种情况
    if ($bits <= 0)
    {
        return $x;
    }
    if ($bits >= $len)
    {
        return 0;
    }
    //转换成代表二进制数字的字符串
    $bin = decbin($x);
    $l = strlen($bin);
    //字符串长度超出则截取底32位，长度不够，则填充高位为0到32位
    if ($l > $len)
    {
        $bin = substr($bin, $l - $len, $len);
    } elseif ($l < $len) {
        $bin = str_pad($bin, $len, '0', STR_PAD_LEFT);
    }
    //取出要移动的位数，并在左边填充0
    return bindec(str_pad(substr($bin, 0, 32 - $bits), 32, '0', STR_PAD_LEFT));
}

/**
* 无符号32位左移
* @param mixed $x 要进行操作的数字，如果是字符串，必须是十进制形式
* @param string $bits 左移位数
* @return mixed 结果，如果超出整型范围将返回浮点数
*/
function shl32 ($x, $bits)
{
    // 位移量超出范围的两种情况
    if ($bits <= 0)
    {
        return $x;
    }
    if ($bits >= 32)
    {
        return 0;
    }
    //转换成代表二进制数字的字符串
    $bin = decbin($x);
    $l = strlen($bin);
    //字符串长度超出则截取底32位，长度不够，则填充高位为0到32位
    if ($l > 32)
    {
        $bin = substr($bin, $l - 32, 32);
    } elseif ($l < 32) {
        $bin = str_pad($bin, 32, '0', STR_PAD_LEFT);
    }
    //取出要移动的位数，并在右边填充0
    return bindec(str_pad(substr($bin, $bits), 32, '0', STR_PAD_RIGHT));
}


/*
*   显示某一个时间相当于当前时间在多少秒前，多少分钟前，多少小时前
*   @timeInt unix time时间戳
*   @format 时间显示格式
*/
function timeFormat($timeInt, $format = 'Y-m-d H:i:s')
{
    if ( empty($timeInt) || !is_numeric($timeInt) || !$timeInt )
    {
        return '';
    }
    $d = time() - $timeInt;
    if ( $d < 0 )
    {
        return '';
    }
    else
    {
        if ( $d < 60 )
        {
            return $d . '秒前';
        }
        else
        {
            if ( $d < 3600 )
            {
                return floor( $d / 60 ) . '分钟前';
            }
            else
            {
                if ( $d < 86400 )
                {
                    return floor( $d / 3600 ) . '小时前';
                }
                else
                {
                    if ( $d < 259200 )
                    {//3天内
                        return floor( $d / 86400) . '天前';
                    }
                    else
                    {
                        return date($format, $timeInt);
                    }
                }
            }
        }
    }
}

/*
 *  UTF-8字符串截取
 *  Deravo
 *  2014-12-26 16:12:05
 * */
function cutstr($string, $length, $surfix = true)
{
    $wordscut = "";
    if ( strlen($string) < 1 ) { return $wordscut; }
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
    for ( $i=0; $i < count($info[0]); $i++)
    {
        $wordscut .= $info[0][$i];
        $j = ord( $info[0][$i] ) > 127 ? $i + 2 : $i + 1;
        if ($j > $length)
        {
            
            if ( $surfix )
            {
                return $wordscut . "...";
            }
            else {
                return $wordscut;
            }
        }
    }
    return join('', $info[0]);
}


/**
* 计算两个坐标之间的距离(米)
* @param float $fP1Lat 起点(纬度)
* @param float $fP1Lon 起点(经度)
* @param float $fP2Lat 终点(纬度)
* @param float $fP2Lon 终点(经度)
* @return int
*/
function distanceBetween($mylonlat, $findlonlat){
    $mylonlat = explode(',', $mylonlat);
    $findlonlat = explode(',', $findlonlat);
    list($lng1,$lat1) = $mylonlat;
    list($lng2,$lat2) = $findlonlat;
    $EARTH_RADIUS=6378.137;
    $PI=3.1415926;
    $radLat1 = $lat1 * $PI / 180.0;
    $radLat2 = $lat2 * $PI / 180.0;
    $a = $radLat1 - $radLat2;
    $b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
    $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $s = $s * $EARTH_RADIUS;
    $s = round($s * 1000);
    if ($len_type > 1) { 
        $s /= 1000; 
    } 
    $distance = round($s/1000,2);
    return $distance;
}

function clearWordTags($content, $allowtags='') { 
    mb_regex_encoding('UTF-8');
    $search = array('/‘/u', '/’/u', '/“/u', '/”/u', '/—/u');
    $replace = array('\'', '\'', '"', '"', '-');
    $content = preg_replace($search, $replace, $content);
    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    if (mb_stripos($content, '/*') !== FALSE) {
        $content = mb_eregi_replace('#/\*.*?\*/#s', '', $content, 'm');
    }
    $content = preg_replace(array('/<([0-9]+)/'), array('< $1'), $content);
    $content = strip_tags($content, $allowtags);
    $content = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $content);

    $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu');
    $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>');
    $content = preg_replace($search, $replace, $content);
    $num_matches = preg_match_all("/\<!--/u", $content, $matches);
    if ($num_matches) {
        $content = preg_replace('/\<!--(.)*--\>/isu', '', $content);
    }
    return $content;
} 

/**
 * @Author:      Alvin
 * @Email:       jin@aliuda.com
 * @DateTime:    2015-10-22 16:12:24
 * @Description: 快速生成随机数字
 */

function randomInt($length)
{
    $pool="0123456789";
    return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
}