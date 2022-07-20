<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToolRequest;
use App\Traits\ApiJsonResponse;
use Illuminate\Http\Request;
use App\Models\Tool;

class ToolController
{
    use ApiJsonResponse;

    public function index(Request $request)
    {
        $tag = $request->tag;

        if (!$tag) {
            $tools = Tool::all()->toArray();

            return empty($tools) ? $this->ok("[]") : $this->ok($tools);
        }

        $tools_filtered_by_tag = array_values(
            Tool::all()
                ->filter(fn (Tool $tool) => in_array($tag, $tool->getAttribute("tags")))
                ->toArray()
        );

        return empty($tools_filtered_by_tag) ? $this->badRequest([
            "error" => true,
            "message" => "tool with tag $tag not found"
        ]) : $tools_filtered_by_tag;
    }

    public function store(StoreToolRequest $request)
    {
        return Tool::create($request->validated());
    }

    public function destroy(Request $request, string $id)
    {
        $tool = Tool::find($id);

        if (empty($tool)) {
            return $this->badRequest([
                "error" => true,
                "message" => "Tool with id $id not found"
            ]);
        }

        $tool->delete();
        return $this->ok();
    }
}
