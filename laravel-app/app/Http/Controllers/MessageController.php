<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Message::query();

        if ($request->has('is_read') && $request->is_read !== null) {
            $query->where('is_read', $request->is_read);
        }

        $messages = $query->orderBy('created_at', 'desc')->get();

        return response()->json($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = Message::create($validator->validated());

        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = Message::findOrFail($id);
        return response()->json($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $message = Message::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'email|max:255',
            'subject' => 'string|max:255',
            'message' => 'string',
            'is_read' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message->update($validator->validated());

        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json(['message' => 'Message deleted successfully']);
    }

    /**
     * Contact form submission
     */
    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = Message::create($validator->validated());

        return response()->json([
            'message' => 'Your message has been sent successfully. We will get back to you soon.',
            'data' => $message
        ], 201);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(string $id)
    {
        $message = Message::findOrFail($id);
        $message->update(['is_read' => true]);

        return response()->json($message);
    }
}
