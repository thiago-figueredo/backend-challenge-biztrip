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
        $tag = $request->query("tag");
        $tools = Tool::all();

        if (empty($tag)) {
            return $this->ok($tools->toArray());
        }

        $tools_filtered_by_tag = array_values(
            $tools
                ->filter(fn (Tool $tool) => in_array($tag, $tool->getAttribute("tags")))
                ->toArray()
        );

        return empty($tools_filtered_by_tag) ? $this->badRequest([
            "error" => true,
            "message" => "tool with tag $tag not found"
        ]) : $this->ok($tools_filtered_by_tag);
    }

    public function store(StoreToolRequest $request)
    {
        return Tool::create($request->all());
    }

    public function destroy(Request $request, string $id)
    {
        $tool = Tool::find($id);

        if (empty($tool)) {
            return $this->badRequest(["error" => true, "message" => "Tool not found"]);
        }

        $tool->delete();
        return $this->ok();
    }
}
