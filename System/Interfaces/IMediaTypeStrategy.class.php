<?php
namespace System\Interfaces;

use System\Classes\Request;

interface IMediaTypeStrategy
{
    public function validate(Request $request) : bool;

    public function decode($rawData);
}
