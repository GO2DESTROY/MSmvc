<?php

namespace MSmvc\system;

use system\MS_view;

/**
 * Class MS_response
 * @package MSmvc\system
 */
class MS_response
{
    private static $responseCollection;
    private static $responseMaster = NULL;
    private static $data;
    private static $responseType;//view|download|json
    private static $headers = [];

    /**
     * we will send a view so we set the response type to view
     */
    public static function view()
    {
        self::$responseType = 'view';
    }

    /**
     * we will send a file for the client to download so we set the response type to view
     *
     * @param $file : the file to download
     */
    public static function download($file)
    {
        self::$responseType = 'download';
        self::$data = $file;
    }

    /**
     * we will send a json encoded response
     *
     * @param $data : the array to json_encode
     */
    public static function json($data)
    {
        self::$responseType = 'json';
        self::$data = $data;
    }

    /**
     * we will overwrite the master view for our response
     *
     * @param      $view : the view to use for overwriting
     */
    public static function overwriteMasterView($view)
    {
        self::$responseMaster = ['view' => $view];
    }

    /**
     * @param string     $view : the view to use
     * @param array|null $data : the data that the view has access to
     */
    public static function addViewToCollection(string $view, array $data = NULL)
    {
        self::$responseCollection = ['view' => $view, 'data' => $data];
    }

    /**
     * we return the specified response or we won't return anything
     */
    public function returnResponse()
    {
        switch (self::$responseType) {
            case 'view':
                $this->viewHeaderResponse();
                $this->returnViewResponseBody();
                break;
            case 'json':
                $this->jsonHeaderResponse();
                echo json_encode(self::$data);
                break;
            case 'download':
                $this->downloadResponse();
                break;
            default:
                break;
        }
    }

    /**
     * we will set the headers to the
     *
     * @param array $defaultHeaders : the default fallback headers to use
     */
    private function setHeader($defaultHeaders = [])
    {
        foreach ($defaultHeaders as $header) {
            header($header);
        }
        if (self::$headers !== NULL) {
            foreach (self::$headers as $header) {
                header($header);
            }
        }
    }

    public static function addToHeader($header)
    {
        self::$headers[] = $header;
    }

    /**
     * we will have a download response we set the header and send the download
     */
    private function downloadResponse()
    {
        $this->setHeader(['Content-Type: application/octet-stream', 'Content-Disposition: attachment; filename="' . self::$data . '"']);
    }

    /**
     * we have a view response we set the header for html
     */
    private function viewHeaderResponse()
    {
        $this->setHeader(['Content-Type: text/html; charset=utf-8']);
    }

    /**
     * we have a json response we set the json header
     */
    private function jsonHeaderResponse()
    {
        $this->setHeader(['Content-Type: application/json']);
    }

    /**
     * we return the MS_view response body
     */
    private function returnViewResponseBody()
    {
        $response = new MS_view();

        MS_view::$view = self::$responseCollection;
        MS_view::$layout = self::$responseMaster;
        $response->loadView();
    }
}