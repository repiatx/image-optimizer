<?php


namespace Spatie\ImageOptimizer\OutputParser;


use Spatie\ImageOptimizer\Image;
use Spatie\ImageOptimizer\IOutput;

class JpegoptimOutput implements IOutput
{
    protected $output;

    protected $image;

    protected $resolution;

    public function __construct($output)
    {
        $this->output = $output;
        $this->parseOutput();
    }

    public function getOutput():string
    {
        return $this->output;
    }

    public function getOriginalSize():string
    {
        // TODO: Implement getSize() method.
    }

    public function getCompressedSize():string
    {
        // TODO: Implement getCompressedSize() method.
    }

    private function parseOutput()
    {
//        //example.jpg 1920x1080 24bit N JFIF  [OK] 221683 --> 221683 bytes (0.00%), skipped.
//        $parsedOutput = explode(' ',$this->output);
//        $this->image = new Image($parsedOutput[0]);
//        $this->resolution = $parsedOutput[1];

    }


    public function getCompressRate(): string
    {
        // TODO: Implement getCompressRate() method.
    }
}
