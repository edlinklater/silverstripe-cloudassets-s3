<?php

/**
 * Requires a mapping in your config that includes assets/Uploads
 */

class CloudAssetsS3Test extends SapphireTest
{

    private $bucket;

    public function setUp()
    {
        $this->bucket = CloudAssets::inst()->map('assets/Uploads/CloudAssetsS3Test.txt');
        parent::setUpOnce();
    }

    public function testInstantiateBucket()
    {
        $this->assertInstanceOf('S3Bucket', $this->bucket);
    }

    public function testGetFile()
    {
        if (!$this->bucket) {
            $this->fail('Cannot continue without valid bucket');
        }

        $file = new File();
        $file->setName('S3ConnectionTest.txt');
        try {
            $size = $this->bucket->getFileSize($file);
        } catch (Exception $e) {
            $error = true;
            echo $e->getMessage();
        }

        $this->assertFalse(isset($error));
    }
}
