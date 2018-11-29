<?php

namespace EduOauth;

/**
 * Interface AuthContract
 * @package EduOauth
 */
interface AuthContract
{
    /**
     * @param $redirectUrl
     * @return string
     */
    public function getOauthLink($redirectUrl);

    /**
     * @param $code
     * @return array
     */
    public function getUserInfo($code);
}