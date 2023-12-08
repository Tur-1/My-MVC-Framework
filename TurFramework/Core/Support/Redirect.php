<?php

namespace TurFramework\Core\Support;

use TurFramework\Core\Facades\Request;
use TurFramework\Core\Http\RedirectResponse;


class Redirect
{
    protected $url;
    protected $statusCode = 302;
    protected $withData = [];
    protected $lastMethodCalled;


    public  function to($url)
    {
        $this->url = $url;

        return $this->createRedirect();
    }

    public function createRedirect()
    {
        return new RedirectResponse();
    }

    /**
     * back.
     *
     * @access	public
     *
     */
    public function back()
    {

        $this->url = (new Request)->previousUrl();

        return $this->createRedirect();
    }

    public function __destruct()
    {

        header('Location: ' . $this->url, true, $this->statusCode);
        exit();
    }
}
