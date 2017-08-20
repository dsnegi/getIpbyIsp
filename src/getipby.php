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

require_once(__DIR__ . '/../vendor/autoload.php');

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
     * opts 
     * 
     * @var array
     * @access private
     */
    private $opts = array(
                        'link'   => null,
                        'out'    => null,
                        'url'    => 'url',
                        'action' => 'Отправить'
                        );

    /**
     * __construct 
     * 
     * @param mixed $opts 
     * @access public
     * @return void
     */
    public function __construct($opts)
    {
        $this->opts = $opts;
    }

    /**
     * help 
     * 
     * @access public
     * @return void
     */
    public function help()
    {
        $name = $argv[0];
        print "\n[*] Get Ip Ranges by city, country or ISP\n";
        print "[+] Coded by hIMEI\n";
        print "[*] Usage:\n\n";
        print "\t\$ $name <type> <out>\n\n";
        print "[*] Example:\n\n";
        print "\t\$ $name city output.txt\n\n";
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
     * getOpts 
     * 
     * @access public
     * @return void
     */
    public function getOpts()
    {
        return $this->opts;
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
        $opts = $this->getOpts();
        $user_agent = $this->userAgent();
        $post_data['url']   = $opts['url'];
        $post_data['action'] = $opts['action'];
        foreach ($post_data as $key => $value) {
            $post_items[] = $key.'='.$value;
        }

        $post_string = implode ('&', $post_items);
        $session = curl_init();

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($session, CURLOPT_URL, $opts['link']);
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
        $options = $this->getOpts();
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

$get_ip = new GetIp($opts); /*
$result = $get_ip->getIps();
$f_result = $get_ip->prepHtml($result);
print($f_result);
*/
$get_ip->help();
