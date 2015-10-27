<?php

/**
 * Project Name     MScurl
 *
 * @author          Maurice Schurink
 * @version         0.1
 *                  Todo:            Improve debug options, Cookie support,ftp over ssl/tls, Kerberos support for ftp,
 *                  SFTP support, ftp file download, ftp download and upload directories, multicurl calls, async
 *                  callback Todo:            optimize it for the MSmvc
 */
namespace system;
class MS_curl
{
	public  $method         = 'VIEW';            // enum POST,GET,DELETE,VIEW,SHOW,PUT,UPLOAD,DOWNLOAD,FTP_SHOW
	public  $url;                        // string
	public  $callBack;                   // function string
	public  $multiCall;                  // bool
	public  $id;                         // int
	public  $idCollection;               // array
	public  $data;                       // string
	public  $dataCollection;             // array
	public  $fileLocation;               // string
	public  $ftpAscii;                   // bool
	public  $ftpUserPwd;                 // username and password example "USERNAME:PASSWORD"
	public  $customHeader;               // string
	public  $allowRedirects = TRUE;      // bool
	public  $customUserAgent;            // string
	public  $proxyAddress;               // string
	public  $proxyUserPwd;               // username and password example "USERNAME:PASSWORD"
	public  $proxyPort;                  // int
	public  $port;                       // int
	public  $debug          = FALSE;              // int
	public  $safeSSL        = TRUE;             // bool only use false for debugging
	public  $caCertLocation;             // string path to the directory the CA certificates are stored
	private $responseInfo;              // internal array
	private $prepareHeader;             // internal array

	private function init() {

		if($this->multiCall === TRUE) { // we will define if we use multi curl handler or just do a single call
			//
			$curl = curl_init();
		}
		else {
			$curl = curl_init();
		}

		$curl = $this->setupCurlMethod($curl);  //we make sure to use the right method
		if(!empty($this->port)) {
			curl_setopt($curl, CURLOPT_PORT, $this->port);  //set an alternative port
		}
		if(!empty($proxyAddress)) {
			$curl = $this->setupProxy($curl);
		}
		if(!empty($this->customUserAgent)) {
			curl_setopt($curl, CURLOPT_USERAGENT, $this->customUserAgent);
		}
		if($this->allowRedirects === TRUE) {
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($curl, CURLOPT_MAXREDIRS, 25); // we set the max redirects to 25 normally we will never get 25 redirects but it's just to stop infinite loops when it's pointing to itself
		}
		else {
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
		}
		if(!empty($this->customHeader)) {
			$this->prepareHeader[] = $this->customHeader;
		}
		if(!empty($this->prepareHeader)) {
			$curl = $this->setHeader($curl);
		}
		if($this->safeSSL === TRUE) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
		}
		else {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		if(!empty($this->caCertLocation)) {
			curl_setopt($curl, CURLOPT_CAPATH, $this->caCertLocation);
		}

		if($this->debug === TRUE) {
			curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
		}

		$output = curl_exec($curl);
		if($this->debug == TRUE) {
			$this->responseInfo = curl_getinfo($curl);
		}
		curl_close($curl);
		return $output;
	}

	private function setupProxy($curl) {
		curl_setopt($curl, CURLOPT_PROXY, $this->proxyAddress);
		if(!empty($this->proxyUserPwd)) {
			curl_setopt($curl, CURLOPT_PROXYUSERPWD, $this->proxyUserPwd);
		}
		if(!empty($this->proxyPort)) {
			curl_setopt($curl, CURLOPT_PORT, $this->proxyPort);  //set an alternative port for the proxy
		}

		return $curl;
	}

	private function setHeader($curl) {
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->customHeader);
		$newHeader = '';
		for($i = 0; $i < count($this->customHeader); $i++) {
			if(is_array($this->customHeader[$i])) {
				for($z = 0; $z < count($this->customHeader[$i]); $z++) {
					$newHeader[] = $this->customHeader[$i][$z];
				}
			}
			else {
				$newHeader[] = $this->customHeader[$i];
			}

		}
		return $curl;
	}

	private function setupCurlMethod($curl) {
		switch($this->method) {
			case "POST":
				$output = $this->postCurl($curl);
				break;
			case "GET":
				$output = $this->getCurl($curl);
				break;
			case "DELETE":
				$output = $this->deleteCurl($curl);
				break;
			case "PUT":
				$output = $this->putCurl($curl);
				break;
			case "UPLOAD":
				$output = $this->uploadCurl($curl);
				break;
			case "FTP_SHOW":
				$output = $this->ftpShowCurl($curl);
				break;
			case "SHOW":
				$output = $this->showCurl($curl);
				break;
			case "VIEW":
				$output = $this->viewCurl($curl);
				break;
			default:
				$output = $this->showCurl($curl);
				break;
		}
		return $output;
	}

	private function postCurl($curl) {

		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		$this->prepareHeader[] = ['Content-Type: application/json', 'Content-Length: ' . strlen($this->data)];
		return $curl;
	}

	private function deleteCurl($curl) {
		curl_setopt($curl, CURLOPT_URL, $this->url . '/' . $this->id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		return $curl;
	}

	private function getCurl($curl) {
		$url = $this->url . '?' . http_build_query($this->data, '', '&');
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

		return $curl;
	}

	private function putCurl($curl) {
		curl_setopt($curl, CURLOPT_URL, $this->url . '/' . $this->id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT"); // note the PUT here

		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		$this->prepareHeader[] = ['Content-Type: application/json', 'Content-Length: ' . strlen($this->data)];
		return $curl;
	}

	private function showCurl($curl) {
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

		return $curl;
	}

	private function viewCurl($curl) {
		curl_setopt($curl, CURLOPT_URL, $this->url . '/' . $this->id);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

		return $curl;
	}

	private function uploadCurl($curl) {
		$fp = fopen($this->fileLocation, "r");

		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_USERPWD, $this->$ftpUserPwd);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_UPLOAD, TRUE);
		curl_setopt($curl, CURLOPT_INFILE, $fp);
		curl_setopt($curl, CURLOPT_INFILESIZE, filesize($this->fileLocation));
		if($this->ftpAscii === TRUE) {
			curl_setopt($curl, CURLOPT_FTPASCII, 1);
		}
		elseif($this->ftpAscii === FALSE) {
			curl_setopt($curl, CURLOPT_FTPASCII, 1);
		}

		return $curl;
	}

	private function ftpShowCurl($curl) {
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_USERPWD, $this->$ftpUserPwd);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

		return $curl;
	}


	public function index() {
		// we should do some check if all the values exist
		if(!empty($this->url)) {
			return $this->init();
		}
		else {
			return 'error no url specified';
		}
	}

	public function infoDump() {
		if(!empty($this->responseInfo)) {
			return $this->responseInfo;
		}
		else {
			return 'Please make sure that you set the "debug" property and use the index method prior to calling this method';
		}
	}
}