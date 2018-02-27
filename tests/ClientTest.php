<?php

use Plugin\ApiClient\Client;

/**
 * Class ClientTest
 */
class ClientTest extends PHPUnit_Framework_TestCase
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


    /**
     *
     */
    public function testAvatarCreate()
    {
        $result = $this->client->avatarCreate([
            'name' => 'test male',
            'parameters' =>
                [
                    'metric' =>
                        [
                            'HIPS' => '90',
                            'WAIST' => '60',
                            'HEIGHT' => '170',
                            'BUST' => '90',
                            'NECK_CIRCLE' => '40',
                            'UNDER_BREAST' => '70',
                            'WEIGHT' => '60',
                            'hair_id' => '0',
                            'hair_style' => 'BaletHair',
                            'GENDER' => 'male',
                            'eye_id' => '0',
                        ],
                ],
        ]);

        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('ident', $result);


        $result = $this->client->avatarUpdate($result['id'], [
            'name' => 'test male',
            'parameters' =>
                [
                    'metric' =>
                        [
                            'HIPS' => '90',
                            'WAIST' => '60',
                            'HEIGHT' => '170',
                            'BUST' => '90',
                            'NECK_CIRCLE' => '40',
                            'UNDER_BREAST' => '70',
                            'WEIGHT' => '60',
                            'hair_id' => '0',
                            'hair_style' => 'BaletHair',
                            'GENDER' => 'male',
                            'eye_id' => '0',
                        ],
                ],
        ]);

        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('ident', $result);
    }


    public function testCacheClear()
    {
        $result = $this->client->cacheClear([
            'ident' => 'fake_ident'
        ]);

        $this->assertEquals(0, $result);
    }

    public function testProjectSync()
    {
        $result = $this->client->projectSync(370);
        $this->assertInternalType('array', $result);
    }


    public function testWorkerQueuePush()
    {
        $result = $this->client->workerQueuePush([
            "poses" => ["v_pose"],
            "avatars" => [1],
            "curves" => [13],
        ]);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('count', $result);

        $this->assertNotEmpty($result['id']);
    }

    public function testProductTextureUpload()
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'fake.txt.zip';
        $result = $this->client->productTextureUpload($file, [
            'name' => 'test texture'
        ]);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('curve_id', $result);
        $this->assertArrayHasKey('name', $result);
    }


    public function testCurveUpload()
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'fake.txt.zip';
        $result = $this->client->curveUpload($file, [
            'cloth_type' => 0,
            'filename' => 'test curve filename',
        ]);

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('cloth_type', $result);
        $this->assertArrayHasKey('filename', $result);
    }

}