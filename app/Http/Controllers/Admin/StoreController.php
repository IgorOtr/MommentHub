<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['logo'] = $this->storeLogo($request);

        $store = Store::create($data);

        return redirect()
            ->route('admin.store.show', $store)
            ->with('success', 'Unidade criada com sucesso.');
    }

    public function show(Store $store): View
    {
        $events = $store->events()->withCount('folders')->latest()->get();

        return view('admin.store.show', compact('store', 'events'));
    }

    public function update(StoreRequest $request, Store $store): RedirectResponse
    {
        $data = $request->validated();

        if ($logo = $this->storeLogo($request)) {
            $data['logo'] = $logo;
        }

        $store->update($data);

        return redirect()
            ->route('admin.store.show', $store)
            ->with('success', 'Unidade atualizada com sucesso.');
    }

    public function destroy(Store $store): RedirectResponse
    {
        $customer = $store->customer;

        $store->delete();

        return redirect()
            ->route('admin.customer.show', $customer)
            ->with('success', 'Unidade removida com sucesso.');
    }

    protected function storeLogo(StoreRequest $request): ?string
    {
        return $request->hasFile('logo')
            ? $request->file('logo')->store('logos', 'public')
            : null;
    }
}
