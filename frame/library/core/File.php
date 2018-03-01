<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: File.php
 * Date: 2018/3/1
 * Time: 17:46
 */

namespace frame;


class File
{
    private $files;

    public function dirTree($path)
    {
        if(!is_dir($path)) return [];
        $files = [];
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $key => $file){
            if($file->getFilename() == '.' || $file->getFilename() == '..'){
                continue;
            }
            $files[] = $file->getPathname();
            if($file->isDir()){
                $files = array_merge($files, $this->dirTree($file->getPathname()));
            }
        }
        $this->files = $files;
        return $files;
    }

    public function readDir($path)
    {
        if(!is_dir($path)){
            return [];
        }

        $files = [];
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $key => $file){
            if($file->getFilename() == '.' || $file->getFilename() == '..'){
                continue;
            }

            if(!$file->isDir()){
                $files[] = $file->getPathname();
            }
        }
        $this->files = $files;
        return $files;
    }
}