<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class RespondJson implements RespondStrategyInterface
{
    private function respond(array $options = [], int $status)
    {
        $response = [];

        foreach ($options as $key => $value)
            $response[$key] = $value;

        return response()->json($response, $status);
    }

    public function ok(array $options = [])
    {
        return $this->respond($options, Response::HTTP_OK);
    }

    public function badRequest(array $options = [])
    {
        return $this->respond($options, Response::HTTP_BAD_REQUEST);
    }

    public function noContent(array $options = [])
    {
        return $this->respond($options, Response::HTTP_NO_CONTENT);
    }

    public function created(array $options = [])
    {
        return $this->respond($options, Response::HTTP_CREATED);
    }
}
