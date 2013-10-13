This is a basic web-facing server status page.

The commands used to retrieve information assume a Debian or Debian-based Linux system. Some values are hard-coded to my specific hardware and may need to be changed.

Inspired by and uses code from [installgentoo.net](http://installgentoo.net).

Set up the temperature
----------------------
First, you need [this script.](https://github.com/lentinj/dns-nas-utils/blob/master/dns-nas-utils/usr/bin/dns_temp)

Then, I do not know why but I can not call this script directly in PHP. So, to bypass this you have to set a cron which put dns_temp (the above script) result in a file called temp in the same folder than the PHP scripts.