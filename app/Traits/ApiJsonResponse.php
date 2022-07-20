<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiJsonResponse
{
    private static function respond(array|string $data = "{}", int $status = Response::HTTP_OK)
    {

        if (is_array($data)) {
            $response = [];

            foreach ($data as $key => $value)
                $response[$key] = $value;

            return response()->json($response, $status);
        }


        return response($data, $status);
    }

    public function ok(array|string $data = "{}")
    {
        return $this->respond($data, Response::HTTP_OK);
    }

    public function badRequest(array|string $data = "{}")
    {
        return $this->respond($data, Response::HTTP_BAD_REQUEST);
    }

    public function noContent(array|string $data = "{}")
    {
        return $this->respond($data, Response::HTTP_NO_CONTENT);
    }

    public function created(array|string $data = "{}")
    {
        return $this->respond($data, Response::HTTP_CREATED);
    }

    public function unauthorized(array|string $data = "{}")
    {
        return $this->respond($data, Response::HTTP_UNAUTHORIZED);
    }

    public function serverError(array|string $data = "{}")
    {
        return $this->respond($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function conflict(array|string $data = "{}")
    {
        return $this->respond($data, Response::HTTP_CONFLICT);
    }
}
