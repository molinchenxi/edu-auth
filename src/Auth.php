<?php

namespace EduOauth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use LogicException;

class Auth implements AuthContract
{
    /**
     * @var $baseUri string
     */
    protected $baseUri;

    /**
     * @var $callback string
     */
    protected $callback;

    /**
     * @var $client Client
     */
    protected $client;

    protected $clientId;

    protected $clientSecret;

    /**
     * ProfileAbstract constructor.
     */
    public function __construct($baseUri = 'http://cas2.edu.sh.cn', $clientId = 'clientId', $clientSecret = 'clientSecret')
    {
        // $this->baseUri = 'http://cas2.edu.sh.cn';
        $this->baseUri = $baseUri;


        $this->clientId = $clientId;

        $this->clientSecret = $clientSecret;

        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    /**
     * get oauth link
     * @param $redirectUrl
     * @return string
     */
    public function getOauthLink($redirectUrl)
    {
        if (empty($this->callback)) {
            $this->setCallBack($redirectUrl);
        }

        return  "{$this->baseUri}/CAS/oauth2.0/authorize?client_id={$this->clientId}&redirect_uri={$this->callback}";
    }

    /**
     * @param $url
     * @return $this
     */
    public function setCallBack($url): AuthContract
    {
        $this->callback = rtrim($url, '/') . '/';

        return $this;
    }

    /**
     * get user info by code
     * @param $code
     * @return array
     */
    public function getUserInfo($code): array
    {
        return $this->getInfo($this->getAccessToken($code));
    }

    /**
     * get access token from oauth server
     * @param $code
     * @return string
     */
    private function getAccessToken($code): string
    {
        try {
            $this->hasCallBack();

            $response = $this->client->get(sprintf(
                "CAS/oauth2.0/accessToken?client_id=%s&client_secret=%s&redirect_uri=%s&code=%s",
                $this->clientId,
                $this->clientSecret,
                $this->callback,
                $code
            ));
        } catch (ClientException $exception) {
            throw new LogicException($exception->getResponse()->getBody()->getContents());
        }

        parse_str($response->getBody(), $output);

        return $output['access_token'];
    }

    /**
     * require callback
     */
    private function hasCallBack(): void
    {
        if (empty($this->callback)) {
            throw new LogicException('need call back url');
        }
    }

    /**
     * get user info by access token
     * @param $accessToken
     * @return array
     */
    private function getInfo($accessToken): array
    {
        try {
            $response = $this->client->get(sprintf("CAS/oauth2.0/profile?access_token=%s", $accessToken));
        } catch (ClientException $exception) {
            throw new LogicException($exception->getResponse()->getBody()->getContents());
        }

        $profile = json_decode($response->getBody(), true);

        $attrs = $profile["attributes"];
        $items = [];

        foreach ($attrs as $attr) {
            foreach ($attr as $key => $value) {
                $items[$key] = $value;
            }
        }

        return $items;
    }
}
