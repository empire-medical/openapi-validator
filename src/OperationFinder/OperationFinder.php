<?php


namespace Mmal\OpenapiValidator\OperationFinder;


use Mmal\OpenapiValidator\OperationInterface;

interface OperationFinder
{
    public function find(): OperationInterface;
}