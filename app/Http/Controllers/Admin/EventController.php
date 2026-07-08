<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    public function store(EventRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $store = Store::findOrFail($data['store_id']);

        $data['customer_id'] = $store->customer_id;
        $data['description'] ??= $store->description;
        $data['address'] ??= $store->address;
        $data['phone'] ??= $store->phone;
        $data['email'] ??= $store->email;
        $data['logo'] = $this->storeLogo($request) ?? $store->logo;
        $data['cover_image'] = $this->storeCoverImage($request);

        $event = Event::create($data);

        return redirect()
            ->route('admin.event.show', $event)
            ->with('success', 'Evento criado com sucesso.');
    }

    public function show(Event $event): View
    {
        $folders = $event->folders()->withCount('mediaFiles')->latest()->get();

        return view('admin.event.show', compact('event', 'folders'));
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        if ($logo = $this->storeLogo($request)) {
            $data['logo'] = $logo;
        }

        if ($coverImage = $this->storeCoverImage($request)) {
            $data['cover_image'] = $coverImage;
        }

        $event->update($data);

        return redirect()
            ->route('admin.event.show', $event)
            ->with('success', 'Evento atualizado com sucesso.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $store = $event->store;

        $event->delete();

        return redirect()
            ->route('admin.store.show', $store)
            ->with('success', 'Evento removido com sucesso.');
    }

    protected function storeLogo(EventRequest $request): ?string
    {
        return $request->hasFile('logo')
            ? $request->file('logo')->store('logos', 'public')
            : null;
    }

    protected function storeCoverImage(EventRequest $request): ?string
    {
        return $request->hasFile('cover_image')
            ? $request->file('cover_image')->store('covers', 'public')
            : null;
    }
}
