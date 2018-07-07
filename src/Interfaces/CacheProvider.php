<?php

namespace App\Interfaces;


interface CacheProvider
{
    public function setCache($key, $value);

    public function getCache($key);

} 