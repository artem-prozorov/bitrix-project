<?php

namespace App\Services\ImageSyncer;

class SimpleClient
{
    /**
     * file_get_contents wrapper
     *
     * @access	public
     * @param	string	$address	
     * @return	bool|string
     */
    public function getContents(string $address)
    {
        return file_get_contents($address);
    }
}
