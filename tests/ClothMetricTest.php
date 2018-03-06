<?php


use Plugin\ApiClient\Client;

class ClothMetricTest extends PHPUnit_Framework_TestCase
{

    /** @var  Client */
    protected $client;


    /**
     *
     */
    public function setUp()
    {
        $this->client = new Client(['base_uri' => API_URI]);
        $this->client->setAccessToken(API_TOKEN);
        parent::setUp();
    }

    public function testClothMetricGet()
    {
        $result = $this->client->clothMetricGet([
            'avatar_id' => 1861,
            'curves' => [33],
            'pose' => 'VPose'
        ]);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('bust', $result);
    }

    public function testClothMetricGetByIdent()
    {
        $result = $this->client->clothMetricGetByIdent('d05e8d5ee9e4d7b6eea29e6c4bccf2ff');

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('bust', $result);
    }



}