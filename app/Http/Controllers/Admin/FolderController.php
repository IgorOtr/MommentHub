<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FolderRequest;
use App\Models\Folder;
use App\Services\MediaFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FolderController extends Controller
{
    public function store(FolderRequest $request): RedirectResponse
    {
        $folder = Folder::create($request->validated());

        return redirect()
            ->route('admin.folder.show', $folder)
            ->with('success', 'Pasta criada com sucesso.');
    }

    public function show(Folder $folder, MediaFileService $mediaFileService): View
    {
        $mediaFiles = $mediaFileService->syncFolder($folder);

        return view('admin.folder.show', compact('folder', 'mediaFiles'));
    }

    public function update(FolderRequest $request, Folder $folder): RedirectResponse
    {
        $folder->update($request->validated());

        return redirect()
            ->route('admin.folder.show', $folder)
            ->with('success', 'Pasta atualizada com sucesso.');
    }

    public function destroy(Folder $folder): RedirectResponse
    {
        $event = $folder->event;

        $folder->delete();

        return redirect()
            ->route('admin.event.show', $event)
            ->with('success', 'Pasta removida com sucesso.');
    }
}
