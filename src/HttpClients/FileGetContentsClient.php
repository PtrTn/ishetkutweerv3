<?php

namespace App\HttpClients;

use App\Interfaces\HttpClient;

class FileGetContentsClient implements HttpClient
{
    public function getData($url)
    {
        return file_get_contents($url);
    }
}
 