<?php

namespace Spatie\ImageOptimizer;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class OptimizerChain
{
    /* @var \Spatie\ImageOptimizer\IOptimizer[] */
    protected $optimizers = [];

    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    /** @var int */
    protected $timeout = 60;

    public function __construct()
    {
        $this->useLogger(new DummyLogger());
    }

    public function getOptimizers(): array
    {
        return $this->optimizers;
    }

    public function addOptimizer(IOptimizer $optimizer)
    {
        $this->optimizers[] = $optimizer;

        return $this;
    }

    public function setOptimizers(array $optimizers)
    {
        $this->optimizers = [];

        foreach ($optimizers as $optimizer) {
            $this->addOptimizer($optimizer);
        }

        return $this;
    }

    /*
     * Set the amount of seconds each separate optimizer may use.
     */
    public function setTimeout(int $timeoutInSeconds)
    {
        $this->timeout = $timeoutInSeconds;

        return $this;
    }

    public function useLogger(LoggerInterface $log)
    {
        $this->logger = $log;

        return $this;
    }

    /**
     * @param string $pathToImage
     * @param string|null $pathToOutput
     * @return IOutput[] OutputObject array
     */
    public function optimize(string $pathToImage, string $pathToOutput = null): array
    {
        $outputObjects = [];

        $image = new Image($pathToImage);

        $this->logger->info("Start optimizing {$pathToImage}");

        foreach ($this->optimizers as $optimizer) {
            $outputObjects[] = $this->applyOptimizer($optimizer, $image);
        }

        return $outputObjects;
    }

    protected function applyOptimizer(IOptimizer $optimizer, Image $image)
    {
        if ( ! $optimizer->canHandle($image)) {
            return;
        }

        $optimizerClass = get_class($optimizer);

        $this->logger->info("Using optimizer: `{$optimizerClass}`");

        $optimizer->setImagePath($image->path());

        $command = $optimizer->getCommand();

        $this->logger->info("Executing `{$command}`");

        $process = Process::fromShellCommandline($command);

        $process
            ->setTimeout($this->timeout)
            ->run();

        $this->logResult($process);

        return $optimizer->parseOutput($process->getOutput());
    }

    protected function logResult(Process $process)
    {
        if ( ! $process->isSuccessful()) {
            $this->logger->error("Process errored with `{$process->getErrorOutput()}`");

            return;
        }

        $this->logger->info("Process successfully ended with output `{$process->getOutput()}`");
    }
}
