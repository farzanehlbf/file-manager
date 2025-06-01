<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Resources\FolderResource;
use App\Services\FolderService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class FolderController extends Controller
{
    protected $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    public function index(Request $request)
    {
        $folders = $this->folderService->getFolders([
            'user_id' => $request->user()->id,
            'name' => $request->get('name'),
            'tag' => $request->get('tag'),
        ]);

        return FolderResource::collection($folders);
    }

    public function store(StoreFolderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $folder = $this->folderService->createFolder($data);

        return response()->json([
            'message' => 'Folder created successfully.',
            'entity' => new FolderResource($folder),
        ], HttpResponse::HTTP_CREATED);
    }

    public function update(StoreFolderRequest $request, $id)
    {
        $folder = $this->folderService->findFolder($id);
        if (!$folder || $folder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], HttpResponse::HTTP_FORBIDDEN);
        }

        $folder = $this->folderService->updateFolder($id, $request->validated());

        return response()->json([
            'message' => 'Folder updated successfully.',
            'entity' => new FolderResource($folder),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $folder = $this->folderService->findFolder($id);
        if (!$folder || $folder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], HttpResponse::HTTP_FORBIDDEN);
        }

        $this->folderService->deleteFolder($id);

        return response()->json(['message' => 'Folder deleted successfully.']);
    }

    public function addTags(Request $request, $id)
    {
        $request->validate([
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $folder = $this->folderService->findFolder($id);
        if (!$folder || $folder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], HttpResponse::HTTP_FORBIDDEN);
        }

        $this->folderService->syncTags($folder, $request->tag_ids);

        return response()->json([
            'message' => 'Tags synced successfully.',
            'tags' => $folder->tags,
        ]);
    }

    public function removeTag(Request $request, $id, $tagId)
    {
        $folder = $this->folderService->findFolder($id);
        if (!$folder || $folder->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], HttpResponse::HTTP_FORBIDDEN);
        }

        $this->folderService->detachTag($id, $tagId);

        return response()->json([
            'message' => 'Tag removed successfully.',
            'tags' => $folder->tags()->get(),
        ]);
    }



}
