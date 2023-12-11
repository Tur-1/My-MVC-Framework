<?php

namespace TurFramework\Core\Router;

use TurFramework\Core\Facades\Request;
use TurFramework\Core\Http\RedirectResponse;


class Redirector
{
    protected $url;
    protected $status_code = 302;

    /**
     * Set the URL to redirect to.
     *
     * @param string $url
     * @param int $status
     * @return \TurFramework\Core\Http\RedirectResponse The RedirectResponse instance.
     */
    public function to(string $url, $status = 302)
    {
        $this->url = $url;
        $this->status_code = $status;

        return $this->createRedirect();
    }

    /**
     * Redirect back to the previous URL.
     *
     * @return \TurFramework\Core\Http\RedirectResponse
     */
    public function back()
    {
        $this->url = (new Request)->previousUrl();
        return $this->createRedirect();
    }

    /**
     * Create a RedirectResponse instance.
     *
     * @return \TurFramework\Core\Http\RedirectResponse
     */
    public function createRedirect()
    {
        return new RedirectResponse();
    }

    /**
     * Perform the actual redirection upon object destruction.
     * Sends a header to the client with the redirect location and status code.
     */
    public function __destruct()
    {
        header('Location: ' . $this->url, true, $this->status_code);
        exit();
    }
}
