<?php
/**
 * Bucket/container driver for Amazon S3
 * Based on markguinn/silverstripe-cloudassets-rackspace
 *
 * @author Ed Linklater <ss@ed.geek.nz>
 * @package cloudassets
 * @subpackage buckets
 */
use Aws\Common\Aws;
use Aws\Common\Enum\Size;
use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\S3Client;
use Aws\S3\Model\MultipartUpload\UploadBuilder;

class S3Bucket extends CloudBucket
{
	const CONTAINER   = 'Container';
	const REGION      = 'Region';
	const API_KEY     = 'ApiKey';
	const API_SECRET  = 'ApiSecret';
	const FORCE_DL    = 'ForceDownload';

	protected $client;


	/**
	 * @param string $path
	 * @param array  $cfg
	 * @throws Exception
	 */
	public function __construct($path, array $cfg=array()) {
		parent::__construct($path, $cfg);
		if (empty($cfg[self::CONTAINER]))  throw new Exception('S3Bucket: missing configuration key - ' . self::CONTAINER);
		if (empty($cfg[self::REGION]))     throw new Exception('S3Bucket: missing configuration key - ' . self::REGION);
		if (empty($cfg[self::API_KEY]))    throw new Exception('S3Bucket: missing configuration key - ' . self::API_KEY);
		if (empty($cfg[self::API_SECRET])) throw new Exception('S3Bucket: missing configuration key - ' . self::API_SECRET);
		$this->containerName = $this->config[self::CONTAINER];

		$this->client = S3Client::factory(array(
			'key'    => $this->config[self::API_KEY],
			'secret' => $this->config[self::API_SECRET],
			'region' => $this->config[self::REGION]
		));
	}


	/**
	 * @param File $f
	 * @throws Exception
	 */
	public function put(File $f) {
		$fp = fopen($f->getFullPath(), 'r');
		if (!$fp) throw new Exception('Unable to open file: ' . $f->getFilename());

		$uploader = UploadBuilder::newInstance()
			->setClient($this->client)
			->setSource($f->getFullPath())
			->setBucket($this->containerName)
			->setKey($this->getRelativeLinkFor($f))
			->build();

		try {
			$uploader->upload();
		} catch (MultipartUploadException $e) {
			$uploader->abort();
		}
	}


	/**
	 * @param File|string $f
	 */
	public function delete($f) {
		$this->client->deleteObject(array(
			'Bucket'     => $this->containerName,
			'Key'        => $f->getFilename(),
		));
	}

	/**
	 * @param File $f
	 * @param string $beforeName - contents of the Filename property (i.e. relative to site root)
	 * @param string $afterName - contents of the Filename property (i.e. relative to site root)
	 */
	public function rename(File $f, $beforeName, $afterName) {
		$obj = $this->getFileObjectFor($f);
		$result = $this->client->copyObject(array(
			'Bucket'     => $this->containerName,
			'CopySource' => urlencode($this->containerName . '/' . $beforeName),
			'Key'        => $afterName,
		));
		if($result) $this->client->deleteObject(array(
			'Bucket'     => $this->containerName,
			'Key'        => $beforeName,
		));
	}


	/**
	 * @param File $f
	 * @return string
	 */
	public function getContents(File $f) {
		$obj = $this->getFileObjectFor($f);
		return $obj['Body'];
	}


	/**
	 * This version just returns a normal link. I'm assuming most
	 * buckets will implement this but I want it to be optional.
	 * NOTE: I'm not sure how reliably this is working.
	 *
	 * @param File|string $f
	 * @param int $expires [optional] - Expiration time in seconds
	 * @return string
	 */
	public function getTemporaryLinkFor($f, $expires=3600) {
		$obj = $this->getFileObjectFor($this->getRelativeLinkFor($f));
		return $obj['Body']->getUri();
	}


	/**
	 * @param $f - File object or filename
	 * @return bool
	 */
	public function checkExists(File $f) {
		return $this->client->doesObjectExist(
			$this->containerName,
			$f->getFilename()
		);
	}


	/**
	 * @param $f - File object or filename
	 * @return int - if file doesn't exist, returns -1
	 */
	public function getFileSize(File $f) {
		if($obj = $this->getFileObjectFor($f)) {
			return $obj['ContentLength'];
		} else {
			return -1;
		}
	}


	/**
	 * @param File|string $f
	 * @return \Guzzle\Http\EntityBody
	 */
	protected function getFileObjectFor(File $f) {
		try {
			$result = $this->client->getObject(array(
				'Bucket' => $this->containerName,
				'Key'    => $f->getFilename()
			));
			return $result;
		} catch (\Aws\S3\Exception\NoSuchKeyException $e) {
			return -1;
		}
	}
}
