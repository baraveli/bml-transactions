<?php

namespace Baraveli\BMLTransaction;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\SessionCookieJar;

class Client extends GuzzleClient
{
    protected $BML_API = 'https://www.bankofmaldives.com.mv/internetbanking/api/';

    public function __construct()
    {
        $jar = new SessionCookieJar('PHPSESSID', true);
        parent::__construct(['cookies' => $jar]);
    }

    /**
     * post.
     *
     *  Send post to BML API with array of form params
     *
     *   ['j_username' => $username,'j_password' => $password]
     *
     * @param mixed $params
     * @param mixed $route
     *
     * @return array
     */
    public function PostRequest(array $params, string $route): array
    {
        try {
            $response = $this->request('POST', $this->BML_API.$route, [
                'form_params' => $params,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            throw new \Exception('Error communicating with API');
        }
    }

    /**
     * get.
     *
     *  Send Get request to BML API
     *
     * @param mixed $route
     *
     * @return array
     */
    public function GetRequest(string $route): array
    {
        $response = $this->request('GET', $this->BML_API.$route);

        return json_decode($response->getBody(), true)['payload'];
    }
}
