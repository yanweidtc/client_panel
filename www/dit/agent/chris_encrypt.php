<?
/*
function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'Mf4QmQHKxFbYFGsLdVdbBMTUGR4CzeWvtcq';
    $secret_iv = 'chrisatzazeen';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}*/

function rc4two($data, $salt, $encrypt = true)
{
        $key = array();
        $result = "";
        $state = array();
        $salt = md5(str_rot13($salt));
        $len = strlen($salt);

        if ($encrypt)
        {
                $data = str_rot13($data);
        }
        else
        {
                $data = base64_decode($data);
        }

        $ii = -1;

        while (++$ii < 256)
        {
                $key[$ii] = ord(substr($salt, (($ii % $len) + 1), 1));
                $state[$ii] = $ii;
        }

        $ii = -1;
        $j = 0;

        while (++$ii < 256)
        {
                $j = ($j + $key[$ii] + $state[$ii]) % 255;
                $t = $state[$j];

                $state[$ii] = $state[$j];
                $state[$j] = $t;
        }

        $len = strlen($data);
        $ii = -1;
        $j = 0;
        $k = 0;

        while (++$ii < $len)
        {
                $j = ($j + 1) % 256;
                $k = ($k + $state[$j]) % 255;
                $t = $key[$j];

                $state[$j] = $state[$k];
                $state[$k] = $t;

                $x = $state[(($state[$j] + $state[$k]) % 255)];
                $result .= chr(ord($data[$ii]) ^ $x);
        }

        if ($encrypt)
        {
                $result = base64_encode($result);
        }
        else
        {
                $result = str_rot13($result);
        }

        return $result;
}

?>
