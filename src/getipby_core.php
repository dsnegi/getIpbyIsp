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
class GetIpCore
{
    /**
     * city_link Web service's url to get IP ranges by city.
     *
     * @var string
     * @access private
     */
    private $city_link = 'https://suip.biz/ru/?act=iploc';
    
    /**
     * country_link Web service's url to get IP ranges by country.
     *
     * @var string
     * @access private
     */
    private $country_link = 'https://suip.biz/ru/?act=ipcountry';

    /**
     * isp_link Web service's url to get IP ranges by internet service provider.
     *
     * @var string
     * @access private
     */
    private $isp_link = 'https://suip.biz/ru/?act=ipintpr';

    /**
     * params Main application parameters.
     *
     * @var array
     * @access private
     */
    private $params = array();

    /**
     * __construct Creates object of GetIpCore class.
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
     * getparams Gets private attribute.
     *
     * @access public
     * @return void
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * getLink Parses given request type (city, country, ISP).
     *
     * @param  void
     * @access public
     * @return string $link Url to make request.
     */
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
     * userAgent Sets random "user agent" value from 1034 values. User agent strings
     * given from SqlMap package.
     *
     * @param  void
     * @return string $user_agent
     */
    public function userAgent()
    {
        $ua_file    = file(__DIR__.'/agents');
        $random_num = random_int(0, 1034);
        $user_agent = trim($ua_file[$random_num]);
    
        return $user_agent;
    }

    /**
     * getIps Creates request with given params to service web-site.
     *
     * @param  void
     * @access public
     * @return string $result Response from web-service.
     */
    public function getIps()
    {
        $post_data = array();
        $params = $this->getParams();
        $user_agent = $this->userAgent();
        $link = $this->getLink();
        $post_data['url'] = $params['url'];
        $post_data['action']  = $params['action'];
        foreach ($post_data as $key => $value) {
            $post_items[] = $key.'='.$value;
        }

        $post_string = implode('&', $post_items);
        $session = curl_init();

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($session, CURLOPT_URL, $link);
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, 1);
              
        $result = curl_exec($session);
        print(curl_errno($session) . '-' .curl_error($session));
        curl_close($session);
        
        return $result;
    }

    /**
     * prepHtml Trims all unwanted HTML content.
     *
     * @param string $result HTML source of result page - result of  prev function.
     * @access public
     * @return string $res_trimmed String with list of IPs.
     */
    public function prepHtml($result)
    {
        $trimmed = strip_tags($result, '<pre>');
        $trimmed = explode("pre", $trimmed);
        $res_trimmed = substr_replace($trimmed[2], '', 0, 1);
        $res_trimmed = substr_replace($res_trimmed, '', -2, 2);

        return $res_trimmed;
    }

    /**
     * outPut Main output; returns IP's list, prints it to STDOUT,
     * optionaly saves it to file.
     *
     * @param void
     * @access public
     * @return string $result Main result.
     */
    public function outPut()
    {
        $params = $this->params;
        $result = $this->getIps();
        $result = $this->prepHtml($result);
        if (isset($params['output'])) {
            $file = $params['output'];
            $output = fopen($file, "w");

            if ($output === false) {
                die(RED.BOLD."Error opening file\n".RESET);
            }

            fwrite($output, $result);
            fclose($output);
        }

        print($result);
        return $result;
    }
}
