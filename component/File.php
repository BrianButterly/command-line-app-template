<?php

namespace Clat\Component;


use ArrayObject;
use Rych\ByteSize\ByteSize;

class File extends ArrayObject
{
    private $fileInfo;

    public function __construct($file)
    {
        $this->fileInfo = new \SplFileInfo($file);
        parent::__construct(
            $this->init()
        );
    }

    private function init()
    {
        return [
            'name' => $this->fileInfo->getFilename(),
            'type' => $this->fileInfo->getType(),
            'size' => $this->fileInfo->getSize(),
            'date' => $this->fileInfo->getCTime()
        ];
    }

    public function format()
    {
        $output = [];
        foreach ($this as $k => $v){
            $output[$k] = $this->propertyFormat($k, $v);
        }
        return $output;
    }

    private function propertyFormat($name, $value)
    {
        switch ($name)
        {
            case 'size':
                $byteSize = new ByteSize;
                return $byteSize->format($value);
            case 'date':
                return date("d.m.Y H:i:s", $value);
            default:
                break;
        }
        return $value;
    }

    
    
    
   
}