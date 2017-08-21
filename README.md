# GetIpByIsp
======

Simple cURL based console application for getting IP ranges from https://suip.biz [suip.biz](https://suip.biz) web-services by city, country (very big size!! i'm really afraid) or ISP. In case of ISP you may specify single ip or web-site url of that provider as argument of script. 

### Dependencies

* pear/console_commandline

## Installation

Just clone this repo

```shell
git clone https://github.com/hlmel/getIpbyIsp.git
```
Then install dependencies

```shell
composer install
```

## Usage

Type next in your console for getting help:

```shell
php getipbyisp.php -h
```

or

```shell
php getipbyisp.php --help
```

The output:

```shell
Console application for getting IP ranges 
from suip.biz web-services by city, country or ISP

Usage:
  getipbyisp.php [options] type request

Options:
  -o output, --output=output  File to store the result
                              
  -h, --help                  Show this help message and exit

Arguments:
  type     Set the type of which IP ranges will be requested: city, counrty or isp
           
  request  Request string: for country - 2-letter country code, for city - its name, for ISP - single IP or ISP's url
```

## TODO

* Tests
* Documentation

## Contact

For bug reports or any other purpose you may contact me via email [himei at tuta dot io].

