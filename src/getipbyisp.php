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
    /**
     * parser Oblect of Pear::Console_CommandLine package's main class. 
     * 
     * @var mixed
     * @access private
     */
    private $parser;

    /**
     * country_codes Array of 2-letters country codes.
     * 
     * @var array
     * @access private
     */
    private $country_codes = array(
                                'AD','AE','AF','AG','AI','AL','AM','AO','AQ','AR','AS',
                                'AT','AU','AW','AX','AZ','BA','BB','BD','BE','BF','BG',
                                'BH','BI','BJ','BL','BM','BN','BO','BQ','BR','BS','BT',
                                'BV','BW','BY','BZ','CA','CC','CD','CF','CG','CH','CI',
                                'CK','CL','CM','CN','CO','CR','CU','CV','CW','CX','CY',
                                'CZ','DE','DJ','DK','DM','DO','DZ','EC','EE','EG','EH',
                                'ER','ES','ET','FI','FJ','FK','FM','FO','FR','GA','GB',
                                'GD','GE','GF','GG','GH','GI','GL','GM','GN','GP','GQ',
                                'GR','GS','GT','GU','GW','GY','HK','HM','HN','HR','HT',
                                'HU','ID','IE','IL','IM','IN','IO','IQ','IR','IS','IT',
                                'JE','JM','JO','JP','KE','KG','KH','KI','KM','KN','KP',
                                'KR','KW','KY','KZ','LA','LB','LC','LI','LK','LR','LS',
                                'LT','LU','LV','LY','MA','MC','MD','ME','MF','MG','MH',
                                'MK','ML','MM','MN','MO','MP','MQ','MR','MS','MT','MU',
                                'MV','MW','MX','MY','MZ','NA','NC','NE','NF','NG','NI',
                                'NL','NO','NP','NR','NU','NZ','OM','PA','PE','PF','PG',
                                'PH','PK','PL','PM','PN','PR','PS','PT','PW','PY','QA',
                                'RE','RO','RS','RU','RW','SA','SB','SC','SD','SE','SG',
                                'SH','SI','SJ','SK','SL','SM','SN','SO','SR','SS','ST',
                                'SV','SX','SY','SZ','TC','TD','TF','TG','TH','TJ','TK',
                                'TL','TM','TN','TO','TR','TT','TV','TW','TZ','UA','UG',
                                'UM','US','UY','UZ','VA','VC','VE','VG','VI','VN','VU',
                                'WF','WS','YE','YT','ZA','ZM','ZW'
                                );

    /**
     * __construct Creates object of main application class, initialise the parser.
     * 
     * @access public
     * @return void
     */
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

    /**
     * getParser Gets private attribute.
     * 
     * @access public
     * @return void
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * getCountryCodes Gets private attribute.
     * 
     * @access public
     * @return void
     */
    public function getCountryCodes()
    {
        return $this->country_codes;
    }
  
    /**
     * cliParse Parses CLI arguments.
     * 
     * @access public
     * @return array $params Array of app's parameters.
     */
    public function cliParse()
    {
        $params = array();

        $parser = $this->getParser();

        $country_codes = $this->getCountryCodes();

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

            if ($parsed->args['type'] === 'country') {
                foreach ($country_codes as $code) {
                    if (($parsed->args['request']) !== $code) {
                        die(RED.BOLD."Invalid country code! Here is no such country in our world. May be in Middle Earth?\n".RESET);
                    }
                }
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
$get_ip = new GetIpCore($params);
$full_res = $get_ip->outPut();
