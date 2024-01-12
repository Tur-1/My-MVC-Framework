<?php

namespace TurFramework\Http;

use TurFramework\Facades\Session;

class RedirectResponse
{
    protected $withData = [];
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

    /**
     * Send the HTTP response.
     *
     * @return void
     */
    public function send($url, $status_code)
    {
        $this->flashToSession();

        return header('Location: ' . $url, true, $status_code);
    }
}
