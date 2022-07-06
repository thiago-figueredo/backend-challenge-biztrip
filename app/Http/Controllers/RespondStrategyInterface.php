<?php

namespace App\Http\Controllers;

interface RespondStrategyInterface
{
    public function ok(array $options = []);
    public function badRequest(array $options = []);
    public function noContent(array $options = []);
    public function created(array $options = []);
}
