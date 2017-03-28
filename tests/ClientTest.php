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
        $this->client->setTenantDomain(API_TENANT_DOMAIN);
        parent::setUp();
    }
    /**
     * Set token for client requests
     */
    private function setClientToken()
    {
        $this->client->setAccessToken(API_TOKEN);
    }


    /**
     *
     */
    public function testAuthGetToken()
    {
        $data = $this->client->authGetToken([
            'login' => API_LOGIN,
            'password' => API_PASSWORD
        ]);
        $this->assertInternalType('array', $data);
    }


    /**
     *
     */
    public function testUserList()
    {
        $this->setClientToken();
        $data = $this->client->userList(['roles[]' => 'admin']);
        $this->assertInternalType('array', $data);
    }


    /**
     *
     */
    public function testTenantRegisterDemo()
    {
        $data = $this->client->tenantRegisterDemo(['type' => 'cafe']);
        $this->assertInternalType('array', $data);
    }
}