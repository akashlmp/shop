<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lunar\Models\Address;
use Lunar\Models\Country;
use Lunar\Models\Order;

class AccountController extends Controller
{
    /**
     * Account dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $customer = $user->latestCustomer();
        $addresses = $customer ? $customer->addresses : collect();

        return view('storefront.account.index', compact('user', 'orders', 'addresses'));
    }

    /**
     * Order history
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::with(['lines', 'shippingAddress'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('storefront.account.orders', compact('orders'));
    }

    /**
     * Order detail view
     */
    public function orderDetail(string $reference)
    {
        $user = Auth::user();

        $order = Order::with([
            'lines.purchasable',
            'shippingAddress',
            'billingAddress',
            'transactions',
        ])
            ->where('reference', $reference)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('storefront.account.order_detail', compact('order'));
    }

    /**
     * Saved delivery addresses
     */
    public function addresses()
    {
        $user = Auth::user();
        $customer = $user->latestCustomer();
        $addresses = $customer ? $customer->addresses : collect();
        $countries = Country::orderBy('name')->get();

        return view('storefront.account.addresses', compact('user', 'addresses', 'countries'));
    }

    /**
     * Store a saved delivery address
     */
    public function saveAddress(Request $request)
    {
        $user = Auth::user();
        $customer = $user->latestCustomer();

        if (! $customer) {
            $parts = explode(' ', $user->name, 2);
            $customer = \Lunar\Models\Customer::create([
                'first_name' => $parts[0] ?? $user->name,
                'last_name' => $parts[1] ?? '',
            ]);
            $customer->users()->attach($user->id);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'line_one' => 'required|string|max:255',
            'line_two' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'country_id' => 'required|exists:lunar_countries,id',
            'contact_phone' => 'nullable|string|max:30',
            'contact_email' => 'nullable|email|max:150',
            'delivery_instructions' => 'nullable|string|max:500',
        ]);

        Address::create([
            'customer_id' => $customer->id,
            'country_id' => $validated['country_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'line_one' => $validated['line_one'],
            'line_two' => $validated['line_two'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postcode' => $validated['postcode'],
            'contact_phone' => $validated['contact_phone'] ?? null,
            'contact_email' => $validated['contact_email'] ?? $user->email,
            'delivery_instructions' => $validated['delivery_instructions'] ?? null,
            'shipping_default' => $customer->addresses()->count() === 0,
        ]);

        return back()->with('success', 'Delivery address saved successfully!');
    }
}

