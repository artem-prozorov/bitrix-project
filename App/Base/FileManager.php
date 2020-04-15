<?php

namespace App\Base;

use CFile;

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

    /**
     * CFile::MakeFileArray wrapper
     *
     * @access	public
     * @param	string	$path	
     * @return	array
     */
    public function getFileArray(string $path): array
    {
        return CFile::MakeFileArray($path);
    }

    /**
     * rename wrapper
     *
     * @access	public
     * @param	string	$oldnam
     * @param	string	$newname
     * @return	bool
     */
    public function rename(string $oldname , string $newname): bool
    {
        return rename($oldname, $newname);
    }
}
