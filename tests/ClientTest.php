<?php

use Baraveli\BMLTransaction\Client;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->client->PostRequest(['j_username' => $_ENV['BML_USERNAME'], 'j_password' =>  $_ENV['BML_PASSWORD']], 'm/login');
    }

    /** @test */
    public function can_send_get_request()
    {
        $response = $this->client->GetRequest('profile');
        $this->assertIsArray($response);
    }

    /** @test */
    public function can_send_post_request()
    {
        $response = $this->client->PostRequest(['j_username' => $_ENV['BML_USERNAME'], 'j_password' =>  $_ENV['BML_PASSWORD']], 'm/login');
        $this->assertIsArray($response);
        $this->assertEquals(true, $response['authenticated']);
    }
}
