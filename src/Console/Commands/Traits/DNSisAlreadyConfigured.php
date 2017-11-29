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
     * @param null $domain
     * @param null $ip
     * @return bool
     */
    protected function dnsResolutionIsOk($domain = null, $ip = null)
    {
        if ($this->dnsAlreadyResolved) {
            return true;
        }

        $domain = $domain ? $domain: $this->obtainDomain();
        $ip = $ip ? $ip: $this->obtainIp();

        if ($domain != null && $ip != null) {
            $resolved_ip = gethostbyname($domain);
            if ($resolved_ip != $domain && $resolved_ip == $ip) {
                $this->dnsAlreadyResolved = true;
                return true;
            }
        }
        return false;
    }

    /**
     * Obtain domain.
     *
     * @return mixed
     */
    protected function obtainDomain()
    {
        return $this->domain ? $this->domain : fp_env('ACACHA_FORGE_DOMAIN', null);
    }

    /**
     * Obtain IP.
     *
     * @return mixed
     */
    protected function obtainIp()
    {
        return $this->ip ? $this->ip : fp_env('ACACHA_FORGE_IP_ADDRESS', null);
    }
}
