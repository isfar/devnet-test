<?php

namespace Mo\Queue;

interface QueueInterface
{
    public function send(string $message);
    public function consume(callable $callback);
}
