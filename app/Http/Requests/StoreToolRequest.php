<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreToolRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "title" => "required|string|unique:tools,title",
            "description" => "required|string",
            "link" => "required|string",
            "tags" => "required|array"
        ];
    }
}
