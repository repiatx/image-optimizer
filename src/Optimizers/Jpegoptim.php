<?php

namespace Spatie\ImageOptimizer\Optimizers;

use Spatie\ImageOptimizer\Image;
use Spatie\ImageOptimizer\IOutput;
use Spatie\ImageOptimizer\OutputParser\JpegoptimOutput;

class Jpegoptim extends BaseOptimizer
{
    public $binaryName = 'jpegoptim';

    public static function builder(): Jpegoptim
    {
        return new static;
    }

    public function canHandle(Image $image): bool
    {
        return $image->mime() === 'image/jpeg';
    }

    public function setQuality($qualityPercentage): self
    {
        $this->options[] = '-m ' . $qualityPercentage;
        return $this;
    }

    public function setOutputPath($outputPath): self
    {
        $this->options[] = '-d ' . $outputPath;
        return $this;
    }

    public function overwrite(): self
    {
        $this->options[] = '-o';
        return $this;
    }

    public function force(): self
    {
        $this->options[] = '-f';
        return $this;
    }

    public function parseOutput($output): IOutput
    {
        return new JpegoptimOutput($output);
    }
}
