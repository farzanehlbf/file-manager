<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index(Request $request)
    {
        $filters = $request->only('name');
        $tags = $this->tagService->getAllTags(15, $filters);
        return TagResource::collection($tags);
    }

    public function store(StoreTagRequest $request)
    {
        $tag = $this->tagService->createTag($request->validated());
        return response()->json([
            'message' => 'Tag created successfully.',
            'data' => new TagResource($tag),
        ], Response::HTTP_CREATED);
    }

    public function update(StoreTagRequest $request, $id)
    {
        $tag = $this->tagService->updateTag($request->validated(), $id);
        if (!$tag) {
            return response()->json(['message' => 'Tag not found or update failed.'], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'message' => 'Tag updated successfully.',
            'data' => new TagResource($tag),
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $deleted = $this->tagService->deleteTag($id);
        if (!$deleted) {
            return response()->json(['message' => 'Tag not found or delete failed.'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['message' => 'Tag deleted successfully.'], Response::HTTP_OK);
    }
}
