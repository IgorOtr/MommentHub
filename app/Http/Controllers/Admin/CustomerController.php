<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::withCount('stores', 'events')->latest()->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['logo'] = $this->storeLogo($request);

        $customer = Customer::create($data);

        return redirect()
            ->route('admin.customer.show', $customer)
            ->with('success', 'Cliente criado com sucesso.');
    }

    public function show(Customer $customer): View
    {
        $stores = $customer->stores()->withCount('events')->latest()->get();

        return view('admin.customer.show', compact('customer', 'stores'));
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();

        if ($logo = $this->storeLogo($request)) {
            $data['logo'] = $logo;
        }

        $customer->update($data);

        return redirect()
            ->route('admin.customer.show', $customer)
            ->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Cliente removido com sucesso.');
    }

    protected function storeLogo(CustomerRequest $request): ?string
    {
        return $request->hasFile('logo')
            ? $request->file('logo')->store('logos', 'public')
            : null;
    }
}
