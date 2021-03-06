# GetIpByIsp

Simple cURL based console application for getting IP ranges from https://suip.biz [suip.biz](https://suip.biz) web-services by city, country (very big size!! i'm really afraid) or ISP. In case of ISP you may specify single ip or web-site url of that provider as argument of script. 

## Documentation

See full documentation on [GitHubPages](https://hlmel.github.io/getIpbyIsp/)

## Dependencies

* pear/console_commandline

## Installation

#### Install via Composer:

```shell
composer require himei/get-ip-by-isp
```

#### For manual installation clone repo

```shell
git clone https://github.com/hlmel/getIpbyIsp.git
```

##### Then install dependencies

```shell
php composer.phar install
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

## Examples

To get all IP ranges of **Serbia**

```shell
php getipbyisp.php country RS
```

To get all IP ranges of **London** and save it to **file.txt**

```shell
php getipbyisp.php city london -o file.txt
```

To get all IP ranges of **Beeline** ISP

```shell
php getipbyisp.php isp beeline.ru
```

or

```shell
php getipbyisp.php isp 217.118.85.19
```

**Warning!!!** In case of request by city, write the name of the city **carefully and accurately**, as much as possible. If an error occurs in the name, the search on the uncleaned database is activated, and the result includes **ALL** IP ranges from **all** possible variants. To get the most accurate result, the city name **must not contain errors**.

## TODO

* Tests

## Contact

For bug reports or any other purpose you may contact me via email [himei at tuta dot io].

