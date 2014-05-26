Cloud Assets Module
===================
Amazon S3 CloudFiles Driver
---------------------------

CloudAssets module allows you to host all or part of the assets folder on a cloud storage container (CDN).
You can find more details about how it works here: <https://github.com/markguinn/silverstripe-cloudassets>

This driver gives you the bucket type S3Bucket for connecting to CloudFiles.

This module can happily co-exist with other bucket driver modules.


Requirements
------------
- Silverstripe 3.1+
- Cloud Assets module
- AWS PHP SDK

Best way to install by far is `composer require edlinklater/silverstripe-cloudassets-s3`.


Example
-------
Assuming you have an S3 bucket called site-uploads:

*mysite/_config/cloudassets.yml:*
```
---
name: assetsconfig
---
CloudAssets:
  map:
    'assets/Uploads':
      Type: S3Bucket
      BaseURL: 'http://yourcdnbaseurl.com/'
      Container: site-uploads
      Region: ap-southeast-2
      ApiKey: yourkey
      ApiSecret: yoursecret
      LocalCopy: true           # Doesn't work without LocalCopy at present
```


Developer(s)
------------
- Ed Linklater <ss@ed.geek.nz>
- derived from markguinn\silverstripe-cloudassets-rackspace by Mark Guinn <mark@adaircreative.com>

Contributions welcome by pull request and/or bug report.
Please follow Silverstripe code standards (tests would be nice).


License (MIT)
-------------
Copyright (c) 2014 Mark Guinn, Ed Linklater

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
