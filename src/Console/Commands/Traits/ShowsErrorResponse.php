<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ShowsErrorResponse
 *
 * @package Acacha\ForgePublish\Commands
 */
trait ShowsErrorResponse
{
    /**
     * Show error and die.
     *
     * @param \Exception $e
     */
    protected function showErrorAndDie(\Exception $e)
    {
        $this->error('And error occurs connecting to the api url: ' . $this->url);
        if ($e->getResponse()) {
            $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase());
        } else {
            $this->error('Exception occurs. Message: ' . $e->getMessage());
        }

        die();
    }
}
