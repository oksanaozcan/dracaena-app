<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;

class TrustHosts
{
    /**
     * The config repository instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }

    /**
     * Get the pattern for all of the application's subdomains.
     *
     * @return string|null
     */
    protected function allSubdomainsOfApplicationUrl()
    {
        $url = $this->config->get('app.url');
        if ($host = parse_url($url, PHP_URL_HOST)) {
            return '^(.+\.)?' . preg_quote($host) . '$';
        }
    }
}
