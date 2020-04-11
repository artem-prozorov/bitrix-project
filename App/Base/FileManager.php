<?php

namespace App\Base;

class FileManager
{
    /**
     * file_put_contents wrapper
     *
     * @access	public
     * @param	string	$filename	
     * @param	mixed 	$data    	
     * @return	mixed
     */
    public function putContents(string $filename, $data)
    {
        return file_put_contents($filename, $data);
    }

    /**
     * Returns unique filename in temp folder
     *
     * @access	public
     * @return	string
     */
    public function getTempFileName(): string
    {
        return tempnam(sys_get_temp_dir(), 'php');
    }
}
