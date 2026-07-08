<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Folder;
use App\Models\Store;
use App\Services\MediaFileService;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalleryController extends Controller
{
    public function customer(Customer $customer): View
    {
        $stores = $customer->stores()->latest()->get();

        return view('public.gallery.customer', compact('customer', 'stores'));
    }

    public function store(Customer $customer, Store $store): View
    {
        $events = $store->events()->latest()->get();

        return view('public.gallery.store', compact('customer', 'store', 'events'));
    }

    public function event(Customer $customer, Store $store, Event $event): View
    {
        $folders = $event->folders()->where('is_public', true)->latest()->get();

        return view('public.gallery.event', compact('customer', 'store', 'event', 'folders'));
    }

    public function folder(Customer $customer, Store $store, Event $event, Folder $folder, MediaFileService $mediaFileService): View
    {
        if (! $folder->is_public) {
            throw new NotFoundHttpException;
        }

        $mediaFiles = $mediaFileService->syncFolder($folder);

        return view('public.gallery.folder', compact('customer', 'store', 'event', 'folder', 'mediaFiles'));
    }
}
