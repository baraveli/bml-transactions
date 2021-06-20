<?php

namespace Baraveli\BMLTransaction;

use Baraveli\BMLTransaction\Exceptions\AuthenticationFailedException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Cookie\SessionCookieJar;
use GuzzleHttp\Exception\RequestException;

class Client extends GuzzleClient
{
    public const UNAUTHORIZED_CODE = 401;

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
    public function postRequest(array $params, string $route): array
    {
        try {
            $response = $this->request('POST', $this->BML_API.$route, [
                'form_params' => $params,
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                switch ($e->getCode()) {
                    case $this::UNAUTHORIZED_CODE:
                        throw new AuthenticationFailedException();
                        break;
                    default:
                        throw new \Exception($e->getMessage());
                        break;
                }
            }
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
    public function getRequest(string $route): array
    {
        try {
            $response = $this->request('GET', $this->BML_API.$route);

            return json_decode($response->getBody(), true)['payload'];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                switch ($e->getCode()) {
                    case $this::UNAUTHORIZED_CODE:
                        throw new AuthenticationFailedException();
                        break;
                    default:
                        throw new \Exception($e->getMessage());
                        break;
                }
            }
        }
    }
}
