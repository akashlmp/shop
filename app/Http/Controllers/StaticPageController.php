<?php

namespace App\Http\Controllers;

use App\Models\DeliveryArea;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function contact()
    {
        $deliveryAreas = DeliveryArea::where('is_active', true)->get();

        return view('storefront.pages.contact', compact('deliveryAreas'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! Your message has been received. Our support team will get in touch shortly.');
    }

    public function about()
    {
        return view('storefront.pages.about');
    }

    public function privacy()
    {
        return view('storefront.pages.privacy');
    }

    public function terms()
    {
        return view('storefront.pages.terms');
    }
}
