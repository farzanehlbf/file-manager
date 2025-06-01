<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        $files = $this->fileService->getAllFiles(15);
        return FileResource::collection($files);
    }

    public function upload(UploadFileRequest $request)
    {
        $folderIds = $request->input('folder_ids', []);

        $file = $this->fileService->upload($request->file('file'), $request->user()->id, $folderIds);

        return new FileResource($file);
    }

    public function update(UpdateFileRequest $request, File $file)
    {
        $updated = $this->fileService->update($file, $request->validated());

        if ($updated) {
            return new FileResource($file->fresh());
        }

        return response()->json(['message' => 'Update failed'], Response::HTTP_BAD_REQUEST);
    }

    public function destroy(File $file)
    {

        $deleted = $this->fileService->delete($file);

        if ($deleted) {
            return response()->json(['message' => 'File deleted successfully']);
        }

        return response()->json(['message' => 'Delete failed'], Response::HTTP_BAD_REQUEST);
    }


    public function move(Request $request, File $file)
    {

        $folderId = $request->input('folder_id');

        $moved = $this->fileService->move($file, $folderId);

        if ($moved) {
            return response()->json(['message' => 'File moved successfully']);
        }

        return response()->json(['message' => 'Move failed'], Response::HTTP_BAD_REQUEST);
    }

    public function copy(Request $request, File $file)
    {
        $folderId = $request->input('folder_id');

        if (is_null($folderId)) {
            return response()->json(['message' => 'folder_id is required'], 422);
        }

        $copiedFile = $this->fileService->copy($file, (int)$folderId);

        if ($copiedFile) {
            return response()->json(['message' => 'File copied successfully', 'file' => $copiedFile]);
        }

        return response()->json(['message' => 'Copy failed'], 400);
    }


    public function download(File $file)
    {

        return $this->fileService->download($file);
    }

    public function share(Request $request, File $file)
    {

        $emails = $request->input('emails');

        $shared = $this->fileService->share($file, $emails);

        if ($shared) {
            return response()->json(['message' => 'File shared successfully']);
        }

        return response()->json(['message' => 'Share failed'], Response::HTTP_BAD_REQUEST);
    }
}
