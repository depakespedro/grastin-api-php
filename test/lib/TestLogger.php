<?php namespace Depakespedro\Grastin\Test;

use Psr\Log\AbstractLogger;

class TestLogger extends AbstractLogger
{
    private $log = [];

    public function log($level, $message, array $context = [])
    {
        $this->log[] = $message;
    }

    public function getLog($index = null)
    {
        if (null !== $index) {
            return $this->log[$index];
        }
        return $this->log;
    }
}
