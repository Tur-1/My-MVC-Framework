<?php

namespace TurFramework\Core\Http;

use TurFramework\Core\Support\Session;

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

    public function __destruct()
    {
        foreach ($this->withData as $key => $value) {
            Session::flash($key, $value);
        }
    }
}
