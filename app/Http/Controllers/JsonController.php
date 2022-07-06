<?php

namespace App\Http\Controllers;

class JsonController extends Controller
{
    protected RespondStrategyInterface $respond;

    public function __construct()
    {
        $this->respond = new RespondJson();
    }

    public function respondOk(array $options = [])
    {
        return $this->respond->ok($options);
    }

    public function respondBadRequest(array $options = [])
    {
        return $this->respond->badRequest($options);
    }

    public function respondNoContent()
    {
        return $this->respond->noContent();
    }

    public function respondCreated()
    {
        return $this->respond->created();
    }
}
