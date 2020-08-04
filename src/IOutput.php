<?php


namespace Spatie\ImageOptimizer;


interface IOutput
{
    public function __construct($output);

    public function getOutput():string;

    public function getOriginalSize():string;

    public function getCompressedSize():string;

    public function getCompressRate():string;


}
