<?php

namespace TurFramework\Core\Support;

use TurFramework\Core\Support\Session;
 

class Redirect
{
    protected $url;
    protected $statusCode = 302;
    protected $withData = [];

    public function __construct($url = null)
    {
        $this->url = $url;
    }

    public  function to($url)
    {
        $this->url = $url;
        return $this;
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

    public function status($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
   /**
     * back.
     *
     * @access	public
     *
     */
    public function back()
    {
        $this->url = $_SERVER['HTTP_REFERER'];

        return $this;
    }

    public function __destruct()
    {
        foreach ($this->withData as $key => $value) {
            Session::flash($key,$value);
        }

        header('Location: '.$this->url, true, $this->statusCode);
        exit();
    }
}
