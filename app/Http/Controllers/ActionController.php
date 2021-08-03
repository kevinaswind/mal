<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function payStep2(Request $request)
    {
        header('Content-Type: text/html; charset=utf-8');

        echo $merchantId = config('constant.merchant_id');//1
        echo "<br>";

        //$requestId=$_POST["requestId"];//2
        $v_ymd = date('Ymd'); //订单产生日期，要求订单日期格式yyyymmdd.
        $v_date = date('His');
        $requestId = $v_ymd . '-' . $merchantId . '-' . $v_date;


        $orderAmount = $request->orderAmount;

        $orderCurrency = $request->orderCurrency;

        $language = $request->language;

        $notifyUrl = $request->notifyUrl;

        $callbackUrl = $request->callbackUrl;

        $terminalNo = config('constant.terminal_id');

        $remark = $request->remark;

        $json_arr = array();
        $json_arr["merchantId"] = $merchantId;
        $json_arr["orderAmount"] = $orderAmount;
        $json_arr["orderCurrency"] = $orderCurrency;
        $json_arr["requestId"] = $requestId;//4

        $json_arr["language"] = $language;//5
        $json_arr["notifyUrl"] = $notifyUrl;//
        $json_arr["callbackUrl"] = $callbackUrl;//7
        $json_arr["terminalNo"] = $terminalNo;//
        $json_arr["remark"] = $remark;//9
        echo "<br>";
        //print_r (json_encode($_POST));
        $data = array();
        foreach ($json_arr as $k => $var) {
            if (is_scalar($var) && $var !== '' && $var !== null) {//如果给出的变量参数 var 是一个标量，is_scalar() 返回 TRUE，否则返回 FALSE。标量变量是指那些包含了 integer、float、string 或 boolean的变量，而 array、object 和 resource 则不是标量。
                $data[$k] = $var;
            } else if (is_object($var)) {
                $data[$k] = array_filter((array)$var);
            } else if (is_array($var)) {
                $data[$k] = array_filter($var);
            }
            if (empty($data[$k])) {
                unset($data[$k]);
            }
        }//foreach -end

        ksort($data);//按照 键名 对关联数组进行升序排序：

        $hmacSource = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                ksort($value);
                foreach ($value as $key2 => $value2) {

                    if (is_object($value2)) {
                        $value2 = array_filter((array)$value2);
                        ksort($value2);
                        foreach ($value2 as $oKey => $oValue) {
                            $oValue .= '#';
                            $hmacSource .= trim($oValue);

                        }
                    } else if (is_array($value2)) {
                        ksort($value2);
                        foreach ($value2 as $key3 => $value3) {
                            if (is_object($value3)) {
                                $value3 = array_filter((array)$value3);
                                ksort($value3);
                                foreach ($value3 as $oKey => $oValue) {
                                    $oValue .= '#';
                                    $hmacSource .= trim($oValue);
                                }
                            } else {
                                $value3 .= '#';
                                $hmacSource .= trim($value3);
                            }
                        }
                    } else {
                        $value2 .= '#';
                        $hmacSource .= trim($value2);
                    }
                }
            } else {
                $value .= '#';
                $hmacSource .= trim($value);
            }
        }
        echo $hmacSource;
        echo "<br>";
        $sha1mac = sha1($hmacSource, true); //SHA1加密

        $pubKey = File::get(storage_path('app/siyao.pfx'));//私钥签名
        $results = array();
        $worked = openssl_pkcs12_read($pubKey, $results, config('constant.private_key_password'));
        $rs = openssl_sign($sha1mac, $hmac, $results['pkey'], "md5");
        $hmac = base64_encode($hmac);

        $hmacarr = array();
        $hmacarr["hmac"] = $hmac;

        $arr_t = (array_merge($json_arr, $hmacarr)); //合并数组
        //$json_str=json_encode($arr_t);
        /*$json_str=json_encode($arr_t,JSON_UNESCAPED_SLASHES);//去掉转移字符
        $json_str=json_encode($arr_t, JSON_UNESCAPED_UNICODE); //正常输出中文*/
        //echo $json_str=json_encode($arr_t, JSON_NUMERIC_CHECK); //正常输出数字 字符串转数字
        //echo "4321<br>";
        echo $json_str = json_encode($arr_t, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);  //将数组转成JSON
        echo "<br>";

###############AES#####################请求体

        /*
         * 生成16位随机数（AES秘钥）AES加密JSON数据串
         */
        $str1 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $randStr = str_shuffle($str1);//打乱字符串
        $rands = substr($randStr, 0, 16);

        /*$screct_key = $rands;
        $str = trim($json_str);
        $str= addPKCS7Padding($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);
        $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_ECB, $iv);
        $date = base64_encode($encrypt_str);*/

        //PHP 7.0 以上用AES加密
        $screct_key = $rands;
        $str = trim($json_str);
        $str = $this->addPKCS7Padding($str);
        $encrypt_str = openssl_encrypt($str, 'AES-128-ECB', $screct_key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
        $date = base64_encode($encrypt_str);


        /**
         * 填充算法
         * @param string $source
         * @return string
         */
        /*function addPKCS7Padding($source){
            $source = trim($source);
            $block = mcrypt_get_block_size('rijndael-128', 'ecb');
            $pad = $block - (strlen($source) % $block);
            if ($pad <= $block) {
                $char = chr($pad);
                $source .= str_repeat($char, $pad);
            }
            return $source;
        }*/

        //PHP 7.0以上 AES填充方法


        /*$json_arr_aes=array();
        $json_arr_aes["data"]=$date;

        $json_str_data=json_encode($json_arr_aes);*/

###############AES#####################


        $verifyKey4Server = File::get(storage_path('app/test.cer'));  //公钥加密AES
        $pem = chunk_split(base64_encode($verifyKey4Server), 64, "\n");//转换为pem格式的公钥
        $public_key = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
        $pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt($rands, $encryptKey, $pu_key);//公钥加密
        $encryptKey = base64_encode($encryptKey);

        $url = "https://apis.5upay.com/icc/order";


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 1); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/vnd.5upay-v3.0+json',
            'encryptKey: ' . $encryptKey,
            'merchantId: ' . $merchantId,
            'requestId: ' . $requestId
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_POST, true); // post传输数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $date);// post传输数据
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证

        $responseText = curl_exec($curl);

//        dd($responseText);
        if (curl_errno($curl) || $responseText === false) {
            curl_close($curl);
            throw new InvalidRequestException(array(
                'error_description' => 'Request Error'
            ));
        }
        curl_close($curl);
        //$aaa=explode("\r\n",$responseText);如果下面的正则表达式不好用！
        preg_match_all('/(encryptkey|merchantid|data"):(\s+|")([^"\s]+)/s', $responseText, $m);
        list($encryptKey, $merchantId, $data) = $m[3];
        $responsedata = array("data" => $data, "encryptKey" => $encryptKey, "merchantId" => $merchantId);

//        dd($responsedata);

        $encryptKey = $responsedata['encryptKey'];
        $pubKey = File::get(storage_path('app/siyao.pfx'));
        $results = array();
        $worked = openssl_pkcs12_read($pubKey, $results, 'a1b2c3d4e5');
//        dd($results);
        $private_key = $results['pkey'];
        $pi_key = openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id'
//        dd($encryptKey);
        openssl_private_decrypt(base64_decode($encryptKey), $decrypted, $pi_key);//私钥解密
        //echo $decrypted,"\n";
//        dd($decrypted);
        //echo "<br>";
        //echo $decrypted;
        $responsedatadata = $responsedata['data'];
//        dd($responsedatadata);
        //echo "<br>";
        //$date = base64_decode($responsedatadata);
        //$screct_key = $decrypted;
        //$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);
        //$encrypt_str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $date, MCRYPT_MODE_ECB, $iv);
        //$encrypt_str = preg_replace('/[\x00-\x1F]/','',$encrypt_str);
        //$encrypt_str = json_decode($encrypt_str,true);

        //print_r($encrypt_str);


        //PHP 7.0 以上AES解密用此方法
        $screct_key = $decrypted;
//        dd($screct_key);
        $encrypt_str = openssl_decrypt($responsedatadata, "AES-128-ECB", $screct_key);
        $encrypt_str = preg_replace('/[\x00-\x1F]/', '', $encrypt_str);
        $encrypt_str = json_decode($encrypt_str, true);

        //print_r($encrypt_str);

//        dd($encrypt_str);
        if ($encrypt_str["status"] == 'SUCCESS') {
            header("Location: {$encrypt_str['redirectUrl']}");
            exit;
        }


    }

    public function addPKCS7Padding($string, $blocksize = 16)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;

    }

    public function payStep3(Request $request)
    {
        dd($request);
    }
}
