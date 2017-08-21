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

/* Global constants for colorize output */

const    RED    =    "\x1B[31m";
const    GRN    =    "\x1B[32m";
const    YEL    =    "\x1B[33m";
const    BOLD   =    "\x1B[1m";
const    LINE   =    "\x1B[4m";
const    RESET  =    "\x1B[0m";

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/getipby_core.php');

/**
 * GetIp 
 * 
 * @package 
 * @version $id$
 * @copyright hIMEI
 * @author hIMEI <himei@tuta.io> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class GetIpCli
{
    private $parser;

    public function __construct()
    {
        $parser = new Console_CommandLine(array(
            'description' => YEL."\nConsole application for getting IP ranges 
from suip.biz web-services by city, country or ISP".RESET,
            'version'            => "1.0.0",
            'add_version_option' => false,
            ));

        $parser->addOption('output', array(
            'short_name'  => '-o',
            'long_name'   => '--output',
            'action'      => 'StoreString',
            'description' => GRN."File to store the result\n".RESET
            ));

        $parser->addArgument('type', array(
            'short_name'  => '-t',
            'long_name'   => '--type',
            'action'      => 'StoreString',
            'description' => GRN."Set the type of which IP ranges will 
be requested: city, counrty or isp\n".RESET
            ));

        $parser->addArgument('request', array(
            'short_name'  => '-r',
            'long_name'   => '--request',
            'action'      => 'StoreString',
            'description' => GRN."Request string: for country - 2-letter country code, 
for city - its name, for ISP - single IP or ISP url\n".RESET
            ));            

        $this->parser = $parser;
    }

    public function getParser()
    {
        return $this->parser;
    }
  
    public function cliParse()
    {
        $params = array();

        $parser = $this->getParser();

        try {
            $parsed = $parser->parse();

            if ($parsed->options['output']) {
                $params['output'] = $parsed->options['output'];
            }

            if ((!$parsed->args['type']) || (!$parsed->args['request'])) {
                die(RED.BOLD."Request and IP range's type are required!\n".RESET);
            }

            if (($parsed->args['type'] !== 'city')    &&
                ($parsed->args['type'] !== 'isp')     &&
                ($parsed->args['type'] !== 'country')) {    
                    die(RED.BOLD."Valid values for <type> is 'city', 'isp' or 'country'!\n".RESET);
            }
            
            if (($parsed->args['type'] === 'city') && (ctype_alpha($parsed->args['request']) != true)) {
                die(RED.BOLD."Invalid city name! Only letters must be there.\n".RESET);
            }

            if (($parsed->args['type'] === 'country') && 
               ((strlen($parsed->args['request']) !== 2) || (ctype_alpha($parsed->args['request']) != true))) {
                die(RED.BOLD."Invalid country name! Only 2 english letters must be there.\n".RESET);
            }

            $params['type']    = trim($parsed->args['type']);
            $params['url'] = trim($parsed->args['request']);
            $params['action']  = 'Отправить';
        } catch (Exception $e) {
            $parser->displayError($e->getMessage());
        }

        return $params;
    }
}

$cli = new GetIpCli();
$params = $cli->cliParse();
$get_ip = new GetIp($params);
$result = $get_ip->getIps();
$f_result = $get_ip->prepHtml($result);
print($f_result);


