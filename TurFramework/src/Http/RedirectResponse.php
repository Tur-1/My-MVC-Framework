<?php

namespace TurFramework\Http;

use TurFramework\Facades\Session;

class RedirectResponse
{
    /**
     * The session store instance.
     *
     * @var \TurFramework\Session\Store
     */
    protected $session;
    protected $url;
    protected $statusCode = 302;
    protected $withData = [];

    public function __construct($url = null, $statusCode = 302)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;
    }
    /**
     * Flash a piece of data to the session.
     *
     * @param string|array $key
     * @param mixed        $value
     *
     * @return $this
     */
    public function with($key, $value = null)
    {
        $this->withData = is_array($key) ? $key : [$key => $value];

        return $this;
    }

    public function withInput(array $input)
    {
        $this->withData['input'] = $input;

        return $this;
    }

    public function withErrors(array $errors)
    {
        $this->withData['errors'] = $errors;

        return $this;
    }


    /**
     * Flash the stored data to the session.
     *
     * @return void
     */
    public function flashToSession()
    {
        foreach ($this->withData as $key => $value) {
            Session::flash($key, $value);
        }
    }
    public function __destruct()
    {
        $this->send($this->url, $this->statusCode);
    }
    /**
     * Send the HTTP response.
     *
     * @return void
     */
    public function send()
    {
        $this->flashToSession();

        header('Location: ' . $this->url, true,  $this->statusCode);
        exit();
    }
}
