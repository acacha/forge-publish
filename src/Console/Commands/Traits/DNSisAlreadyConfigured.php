<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait DNSisAlreadyConfigured
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait DNSisAlreadyConfigured
{
    /**
     * Check if DNS is already configured.
     *
     * @return bool
     */
    protected function dnsIsAlreadyConfigured()
    {
        $domain = env('ACACHA_FORGE_DOMAIN',null);
        $ip = env('ACACHA_FORGE_IP_ADDRESS',null);

        if ($domain != null && $ip != null ) {
            $resolved_ip = gethostbyname ($domain);
            if ( $resolved_ip != $domain && $resolved_ip == $ip ) {
                $this->dnsAlreadyConfigured = true;
                return true;
            }
        }
        return false;
    }
}