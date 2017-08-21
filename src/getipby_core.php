<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Short description for getipby_core.php
 *
 * @package getipby_core
 * @author hIMEI <hIMEI@hiddentemple>
 * @version 0.1
 * @copyright (C) 2017 hIMEI <hIMEI@hiddentemple>
 * @license MIT
 */

error_reporting(E_ALL);

/**
 * GetIp 
 * 
 * @package 
 * @version $id$
 * @copyright hIMEI
 * @author hIMEI <himei@tuta.io> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class GetIp
{
    /**
     * city_link 
     * 
     * @var string
     * @access private
     */
    private $city_link = 'https://suip.biz/ru/?act=iploc';
    
    /**
     * country_link 
     * 
     * @var string
     * @access private
     */
    private $country_link = 'https://suip.biz/ru/?act=ipcountry';

    /**
     * isp_link 
     * 
     * @var string
     * @access private
     */
    private $isp_link = 'https://suip.biz/ru/?act=ipintpr';

    /**
     * params 
     * 
     * @var array
     * @access private
     */
    private $params = array();

    /**
     * __construct 
     * 
     * @param mixed $params 
     * @access public
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * help 
     * 
     * @access public
     * @return void
     */
    public function help()
    {
        $name = "getipby.php";
        print(GRN."\n[*] Get Ip Ranges by city, country or ISP\n");
        print("[+] Coded by hIMEI\n");
        print("[*]".BOLD.YEL." Usage:\n\n".RESET);
        print("\t\$ $name <type> <out>\n\n");
        print("[*]".BOLD.YEL." Example:\n\n".RESET);
        print("\t\$ $name city output.txt\n\n".RESET);
        exit(0);
    }

    /**
     * getCityLink 
     * 
     * @access public
     * @return void
     */
    public function getCityLink()
    {
        return $this->city_link;
    }
 
    /**
     * getCountryLink 
     * 
     * @access public
     * @return void
     */
    public function getCountryLink()
    {
        return $this->country_link;
    }

    /**
     * getIspLink 
     * 
     * @access public
     * @return void
     */
    public function getIspLink()
    {
        return $this->isp_link;
    }

    /**
     * getparams 
     * 
     * @access public
     * @return void
     */
    public function getParams()
    {
        return $this->params;
    }

    public function getLink()
    {
        $params = $this->params;
        $link = null;
        if ($params['type'] === 'city') {
            $link = $this->city_link;
        }
 
        if ($params['type'] === 'country') {
            $link = $this->country_link;
        }

        if ($params['type'] === 'isp') {
            $link = $this->isp_link;
        }

        return $link;
    }  
 
    /**
     * Set random "user agent" value from 1034 values
     * @param  void
     * @return string $user_agent
     */
    public  function userAgent()
    {
        $ua_file    = file(__DIR__.'/agents');
        $random_num = random_int(0, 1034);
        $user_agent = $ua_file[$random_num];
    
        return $user_agent;
    }

    /**
     * getIps 
     * 
     * @access public
     * @return void
     */
    public function getIps()
    {
        $post_data = array();
        $params = $this->getParams();
        $user_agent = $this->userAgent();
        $link = $this->getLink();
        $post_data['request'] = $params['request'];
        $post_data['action']  = $params['action'];
        foreach ($post_data as $key => $value) {
            $post_items[] = $key.'='.$value;
        }

        $post_string = implode ('&', $post_items);
        $session = curl_init();

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($session, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($session, CURLOPT_URL, $link);
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, 1);
              
        $result = curl_exec($session);
//        print_r(curl_getinfo($session));
        print(curl_errno($session) . '-' .curl_error($session));
        curl_close($session);
        
        return $result;
    }

    /**
     * prepHtml 
     * 
     * @param mixed $result 
     * @access public
     * @return void
     */
    function prepHtml($result)
    {
        $trimmed = strip_tags($result, '<pre>');
        $trimmed = explode("pre", $trimmed);

        return $trimmed[2];
    }

    /**
     * outPut 
     * 
     * @param mixed $result 
     * @access public
     * @return void
     */
    public function outPut($result)
    {
        $options = $this->getparams();
        $file = $params['out'];
        $output = fopen($file, "w");
        fwrite($output, $result);
        fclose($output);
    }
}
