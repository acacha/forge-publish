<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ChecksToken.
 * 
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait ChecksToken
{
    /**
     * Check token.
     *
     * @param $token
     * @return bool
     */
    protected function checkToken($token) {

        try {
            $response = $this->http->get($this->checkTokenURL(), [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);
            $content = json_decode($response->getBody()->getContents());
            if (isset($content->message)) {
                if ($content->message === 'Token is valid') return true;
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    /**
     * Get api url endpoint.
     */
    protected function checkTokenURL()
    {
        return config('forge-publish.url') . config('forge-publish.get_check_token_uri');
    }
}