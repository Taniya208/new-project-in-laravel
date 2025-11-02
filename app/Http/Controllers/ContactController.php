<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display the main contact page.
     */
    public function index()
    {
        return view('contacts.index'); // contacts/index.blade.php
    }

    /**
     * Fetch all contacts.
     * If logged in, only fetch user's contacts. Otherwise, fetch all.
     */
    public function fetch()
    {
        if (Auth::check()) {
            $contacts = Contact::where('user_id', Auth::id())
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $contacts = Contact::orderBy('id', 'desc')->get();
        }

        return response()->json([
            'success' => true,
            'contacts' => $contacts
        ]);
    }

    /**
     * Store a new contact.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['name', 'email', 'description']);
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $contact = Contact::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Contact saved successfully!',
            'contact' => $contact
        ]);
    }

    /**
     * Update an existing contact.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::findOrFail($id);

        // Optional: restrict to user's own contacts
        if (Auth::check() && $contact->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $contact->update($request->only(['name', 'email', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully!',
            'data' => $contact
        ]);
    }

    /**
     * Delete a contact.
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found'
            ], 404);
        }

        // Optional: restrict deletion to owner's contact
        if (Auth::check() && $contact->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully!'
        ]);
    }
}
