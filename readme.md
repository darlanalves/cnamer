# cnamer

*Pure* DNS domain redirects using CNAME, TXT or A records

Documentation @ [cnamer.com](http://cnamer.com)

* Requires PHP 5.4. 
* Point all requests of *.{cnamer-domain} to cnamer/cnamer.php
* Point all requests of {cnamer-domain} to static/index.php. 
* data folder should be writable by PHP, this includes all the logs, stats and 
    cache data.
* Put your configuration stuff in bootstrap.php
* Set meta/statistics.php to run every however long (5 mins is good) to generate
    stats on the domains + redirects performed.
* The static folder isn't static but static is a pretty word

# license

Copyright (C) 2012 - 2013 Samuel Ryan (citricsquid)

Permission is hereby granted, free of charge, to any person obtaining a copy of 
this software and associated documentation files (the "Software"), to deal in 
the Software without restriction, including without limitation the rights to use, 
copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the 
Software, and to permit persons to whom the Software is furnished to do so, 
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN 
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.