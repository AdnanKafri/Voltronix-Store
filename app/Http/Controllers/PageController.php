<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Show the contact page
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Here you can implement email sending logic
            // For now, we'll just return a success response
            
            return redirect()->route('contact')
                ->with('success', __('app.contact.message_sent'));
        } catch (\Exception $e) {
            return redirect()->route('contact')
                ->with('error', __('app.contact.message_failed'))
                ->withInput();
        }
    }

    /**
     * Show the terms of use page
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Show the privacy policy page
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Show the refund policy page
     */
    public function refund()
    {
        return view('pages.refund');
    }
}
