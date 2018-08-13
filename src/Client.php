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
        return $this->callMethod('cache/clear', 'POST', $options);
    }


    /**
     * API method /v1/project/sync - System method for sync projects for sharecloth and plugin-web.globedrobe.com
     *
     * @param $id int Cloth id
     * @return mixed
     */
    public function projectSync($id)
    {
        return $this->callMethod('project/sync/' . $id, 'POST', []);
    }


    /**
     * API method /v1/cloth-metric/get
     * @param $options
     * @return mixed
     */
    public function clothMetricGet($options)
    {
        return $this->callMethod('cloth-metric/get', 'POST', $options);
    }

    /**
     * API method /v1/cloth-metric/get
     * @param $ident
     * @return mixed
     */
    public function clothMetricGetByIdent($ident)
    {
        return $this->callMethod('cloth-metric/get-by-ident/' . $ident, 'GET');
    }


    /**
     * API method /v1/mesh/calc
     *
     *
     * @param $options
     * @return mixed
     */
    public function calcMesh($options)
    {
        return $this->callMethod('mesh/calc', 'POST', $options);
    }


    /**
     * API method /v1/worker-queue/push
     *
     * @param array $options
     *
     * @return mixed
     */
    public function workerQueuePush(array $options)
    {
        return $this->callMethod('worker-queue/push', 'POST', $options);
    }

    /**
     * API method /v1/curve/save
     *
     * @param       $pathToFile
     * @param array $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function curveUpload($pathToFile, array $options)
    {
        if (file_exists($pathToFile)) {
            $options = $this->optionsToMultipartData($options);
            $options = array_merge($options, [
                [
                    'name' => 'dataFile',
                    'contents' => fopen($pathToFile, 'r')
                ]
            ]);

            return $this->callMethod('curve/upload', 'POST', $options, 'multipart');
        }

        throw new BadResponseException('File not found ' . $pathToFile);
    }

    /**
     * API method /v1/texture/save
     *
     * @param       $pathToFile
     * @param array $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function productTextureUpload($pathToFile, array $options)
    {
        if (file_exists($pathToFile)) {
            $options = $this->optionsToMultipartData($options);
            $options = array_merge($options, [
                [
                    'name' => 'dataFile',
                    'contents' => fopen($pathToFile, 'r')
                ]
            ]);

            return $this->callMethod('product-texture/upload', 'POST', $options, 'multipart');
        }

        throw new BadResponseException('File not found ' . $pathToFile);
    }

    private function optionsToMultipartData($options)
    {
        $result = [];
        foreach ($options as $key => $value) {
            $result[] = [
                'name' => $key,
                'contents' => $value
            ];
        }

        return $result;
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
        return $this->callMethod('avatar/create', 'POST', $options);
    }


    /**
     * API method /v1/avatar/update
     *
     * @param       $avatarId
     * @param array $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarUpdate($avatarId, array $options)
    {
        return $this->callMethod('avatar/update/' . $avatarId, 'POST', $options);
    }

    /**
     * @param $options
     *
     * @return mixed
     * @throws BadResponseException
     */
    public function cacheStatus($options)
    {
        return $this->callMethod('cache/status', 'POST', $options);
    }


    /**
     * Basic method for all api calls
     *
     * @param string $uri
     * @param string $method
     * @param array  $options
     *
     * @param string $postType
     *
     * @return mixed
     * @throws BadResponseException
     */
    protected function callMethod($uri, $method = 'GET', $options = [], $requestParamType = 'json')
    {
        $headers = [];
        if ($this->accessToken) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }

        try {
            $requestKeyOptions = [
                'GET' => 'query',
                'POST' => 'json',
                'PUT' => 'json',
            ];

            $requestParamType = $requestParamType ? : $requestKeyOptions[$method];

            $response = $this->httpClient->request($method, $uri,
                [
                    'headers' => $headers,
                    $requestParamType => $options
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