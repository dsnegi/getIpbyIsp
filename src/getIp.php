<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Short description for 
 *
 * @package 
 * @author hIMEI <hIMEI@tuta.io>
 * @version 0.1
 * @copyright (C) 2017 hIMEI <hIMEI@tuta.io>
 * @license MIT
 */

error_reporting(E_ALL);

class GetIps
{
    private $city_link = 'https://suip.biz/ru/?act=iploc';
    
    private $country_link = 'https://suip.biz/ru/?act=ipcountry';

    private $prov_link = 'https://suip.biz/ru/?act=ipintpr';

    private $options = array(
                        'link'   => null,
                        'out'    => null,
                        'url'    => 'url',
                        'action' => 'Отправить'
                        );

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getCityLink()
    {
        return $this->city_link;
    }
 
    public function getCountryLink()
    {
        return $this->country_link;
    }

    public function getProvLink()
    {
        return $this->prov_link;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getIps()
    {
        $post_data = array();
        $options = $this->getOptions();
        $post_data['url']   = $options['url'];
        $post_data['action'] = $options['action'];
        foreach ($post_data as $key => $value) {
            $post_items[] = $key.'='.$value;
        }

        $post_string = implode ('&', $post_items);
        $session = curl_init();

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_USERAGENT,
                         "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        curl_setopt($session, CURLOPT_URL, $options['link']);
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, 1);
              
        $result = curl_exec($session);
//        print_r(curl_getinfo($session));
        print(curl_errno($session) . '-' .curl_error($session));
        curl_close($session);
        
        return $result;
    }

    function prepHtml($result)
    {
        $trimmed = strip_tags($result, '<pre>');
        $trimmed = explode("pre", $trimmed);

        return $trimmed[2];
    }

    public function outPut($result)
    {
        $options = $this->getOptions();
        $file = $options['out'];
        $output = fopen($file, "w");
        fwrite($output, $result);
        fclose($output);
    }
}

$opts = array(
           'link'  => 'https://suip.biz/ru/?act=ipintpr',
           'url'   => 'beeline.ru',
           'action' => 'Отправить'
           );

$get_ip = new GetIps($opts);
$result = $get_ip->getIps();
$f_result = $get_ip->prepHtml($result);
print($f_result);

