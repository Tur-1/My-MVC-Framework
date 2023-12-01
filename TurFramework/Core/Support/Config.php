<?php

namespace TurFramework\Core\Support;

class Config
{
    public $configFiles = [];
    private $default = null;

    public function __construct($configFiles)
    {
        foreach ($configFiles as $key => $value) {
            $this->configFiles[$key] = $value;
        }
    }

    public function get($key)
    {
        $keys = explode('.', $key);

        $item = $this->configFiles;


        foreach ($keys as $value) {
            if (isset($item[$value])) {
                $item = $item[$value];
            } else {
                $item = $this->default;
                break;
            }
        }

        return $item;
    }
}
