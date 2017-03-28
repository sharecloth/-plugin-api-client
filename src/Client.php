<?php

namespace Plugin\ApiClient;

use GuzzleHttp\Psr7\Response;
use Plugin\ApiClient\Exception\BadResponseException;
use Plugin\ApiClient\Response\JsonResponseFactory;


/**
 * Class Client
 *
 * @author Petr Marochkin <petun911@gmail.com>
 */
class Client
{

    /** @var  \GuzzleHttp\Client */
    protected $httpClient;

    /** @var string */
    protected $baseUri = 'http://plugin-web.globedrobe.com/api/v1/';

    /** @var  integer */
    protected $timeout = 300;

    /** @var  string */
    protected $accessToken;

    /**
     * Client constructor.
     *
     * @param array $httpClientConfig
     *
     * @throws BadResponseException
     */
    public function __construct($httpClientConfig = [])
    {
        $this->initHttpClient($httpClientConfig);
    }

    /**
     * @param $httpClientConfig
     */
    protected function initHttpClient($httpClientConfig)
    {
        $config = array_merge([
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
            'headers' => [
                'User-Agent' => 'plugin-api-client',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ], $httpClientConfig);
        $this->httpClient = new \GuzzleHttp\Client($config);
    }

    /**
     * @param $token
     *
     * @return mixed
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * API method /v1/cache/clear
     *
     * @param array $options
     *
     * @return mixed
     */
    public function cacheClear(array $options)
    {
        return $this->callMethod('v1/cache/clear', 'POST', $options);
    }

    /**
     * API method /v1/curve/save
     *
     * @param array $options
     *
     * @return mixed
     */
    public function curveSave(array $options)
    {
        return $this->callMethod('v1/curve/save', 'POST', $options);
    }

    /**
     * API method /v1/texture/save
     *
     * @param array $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function textureSave(array $options)
    {
        return $this->callMethod('v1/texture/save', 'POST', $options);
    }


    /**
     * API method /v1/avatar/upload
     *
     * @param array $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarUpload(array $options)
    {
        return $this->callMethod('v1/avatar/upload', 'POST', $options);
    }

    /**
     * API method /v1/avatar/create
     *
     * @param array $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarCreate(array $options)
    {
        return $this->callMethod('v1/avatar/create', 'POST', $options);
    }


    /**
     * API method /v1/avatar/update
     *
     * @param array $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarUpdate(array $options)
    {
        return $this->callMethod('v1/avatar/update', 'POST', $options);
    }


    /**
     * @param array $options
     */
    public function gdcbFileSave(array $options)
    {
        //todo?
    }


    /**
     * Basic method for all api calls
     *
     * @param        $method
     * @param string $uri
     * @param array  $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    protected function callMethod($uri, $method = 'GET', $options = [])
    {
        $headers = [];
        if ($this->accessToken) {
            $headers['Access-Token'] = $this->accessToken;
        }

        try {
            $requestKeyOptions = [
                'GET' => 'query',
                'POST' => 'json',
                'PUT' => 'json',
            ];
            $response = $this->httpClient->request($method, $uri,
                [
                    'headers' => $headers,
                    $requestKeyOptions[$method] => $options
                ]);
            return $this->parseResponse($response);
        } catch (\Exception $e) {
            throw new BadResponseException($e->getMessage());
        }
    }

    /**
     * Returns api method data (now at json format)
     *
     * @param Response $response
     *
     * @return array
     */
    protected function parseResponse(Response $response)
    {
        $data = $response->getBody()->getContents();
        $factory = new JsonResponseFactory();
        return $factory->parseData($data);
    }


}