<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\FolderResource;
use App\Http\Resources\StoreResource;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Folder;
use App\Models\Store;
use App\Services\MediaFileService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalleryController extends Controller
{
    public function customer(Customer $customer): CustomerResource
    {
        $customer->load(['stores.events.folders' => fn ($query) => $query->where('is_public', true)]);

        return CustomerResource::make($customer);
    }

    public function store(Customer $customer, Store $store): StoreResource
    {
        $store->load('events');

        return StoreResource::make($store);
    }

    public function event(Customer $customer, Store $store, Event $event): EventResource
    {
        $event->load(['folders' => fn ($query) => $query->where('is_public', true)]);

        return EventResource::make($event);
    }

    public function folder(Customer $customer, Store $store, Event $event, Folder $folder, MediaFileService $mediaFileService): FolderResource
    {
        if (! $folder->is_public) {
            throw new NotFoundHttpException;
        }

        $folder->setRelation('mediaFiles', $mediaFileService->syncFolder($folder));

        return FolderResource::make($folder);
    }
}
