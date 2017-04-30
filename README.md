# speedmon
Network speed monitor &amp; logger
![screenshot](http://i.imgur.com/OxBOFwK.png)

Crontab needs ````*/10 * * * * php /path/to/speedtest.php >> /path/to/netspeed.csv````ss

Requires [pacbard/gChartPhp](https://github.com/pacbard/gChartPhp), prefrebly installed using composer. A composer.json is included.

Requires [speedtest-cli](https://pypi.python.org/pypi/speedtest-cli/). You can install this using pip (`pip install speedtest-cli`).

Also requires Bootstrap CSS for the web component to display correctly.
