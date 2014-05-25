Cloud Assets Module
===================
Rackspace CloudFiles Driver
---------------------------

CloudAssets module allows you to host all or part of the assets folder on a cloud storage container (CDN).
You can find more details about how it works here: <https://github.com/markguinn/silverstripe-cloudassets>

This driver gives you the bucket type RackspaceBucket for connecting to CloudFiles.

This module can happily co-exist with other bucket driver modules (which don't exist at the time of this writing).


Requirements
------------
- Silverstripe 3.1+
- Cloud Assets module
- Rackspace php-opencloud

Best way to install by far is `composer require markguinn/silverstripe-cloudassets-rackspace`.


Example
-------
Assuming you have a CloudFiles container called site-uploads:

*mysite/_config/cloudassets.yml:*
```
---
name: assetsconfig
---
CloudAssets:
  map:
    'assets/Uploads':
      Type: RackspaceBucket
      BaseURL: 'http://yourcdnbaseurl.com/'
      Container: site-uploads
      Region: ORD
      Username: yourlogin
      ApiKey: yourkey
      LocalCopy: false
      ServiceNet: false         # use the rackspace servicenet
      ForceDownlad: false       # add Content-disposition headers to uplaoded files
```


Developer(s)
------------
- Mark Guinn <mark@adaircreative.com>

Contributions welcome by pull request and/or bug report.
Please follow Silverstripe code standards (tests would be nice).

I would love for someone to implement some other drivers - S3, Swift, Google, etc.
It's very easy to implement drivers - just extend CloudBucket and implement a few
methods.


License (MIT)
-------------
Copyright (c) 2014 Mark Guinn

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
Software, and to permit persons to whom the Software is furnished to do so, subject
to the following conditions:

The above copyright notice and this permission notice shall be included in all copies
or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.