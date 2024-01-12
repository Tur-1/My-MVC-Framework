<?php

namespace TurFramework\Router;

use TurFramework\Facades\Request;
use TurFramework\Http\RedirectResponse;


class Redirector
{
    protected $url;
    protected $status_code = 302;

    /**
     * Set the URL to redirect to.
     *
     * @param string $url
     * @param int $status
     * @return \TurFramework\Http\RedirectResponse The RedirectResponse instance.
     */
    public function to(string $url, $status = 302)
    {
        $this->status_code = $status;
        return $this->createRedirect($url, $status);
    }

    /**
     * Redirect back to the previous URL.
     *
     * @return \TurFramework\Http\RedirectResponse
     */
    public function back()
    {
        return $this->createRedirect((new Request)->previousUrl());
    }

    /**
     * Create a RedirectResponse instance.
     *
     * @return \TurFramework\Http\RedirectResponse
     */
    public function createRedirect($url)
    {
        $response = new RedirectResponse();
        $response->send($url, $this->status_code);
        exit();
    }
}
