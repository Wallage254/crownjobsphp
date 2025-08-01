<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Application::with('job');

        if ($request->has('job_id') && $request->job_id) {
            $query->where('job_id', $request->job_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:crown_jobs,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'current_location' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
            'experience' => 'nullable|string',
            'previous_role' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('uploads', 'public');
        }

        if ($request->hasFile('cv_file')) {
            $data['cv_file'] = $request->file('cv_file')->store('uploads', 'public');
        }

        $application = Application::create($data);

        return response()->json($application->load('job'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $application = Application::with('job')->findOrFail($id);
        return response()->json($application);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $application = Application::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'email|max:255',
            'phone' => 'string|max:255',
            'current_location' => 'string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
            'experience' => 'nullable|string',
            'previous_role' => 'nullable|string|max:255',
            'status' => 'in:pending,reviewed,accepted,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            if ($application->profile_photo) {
                Storage::disk('public')->delete($application->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('uploads', 'public');
        }

        if ($request->hasFile('cv_file')) {
            if ($application->cv_file) {
                Storage::disk('public')->delete($application->cv_file);
            }
            $data['cv_file'] = $request->file('cv_file')->store('uploads', 'public');
        }

        $application->update($data);

        return response()->json($application->load('job'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $application = Application::findOrFail($id);
        
        // Delete associated files
        if ($application->profile_photo) {
            Storage::disk('public')->delete($application->profile_photo);
        }

        if ($application->cv_file) {
            Storage::disk('public')->delete($application->cv_file);
        }

        $application->delete();

        return response()->json(['message' => 'Application deleted successfully']);
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,reviewed,accepted,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $application->update(['status' => $request->status]);

        return response()->json($application->load('job'));
    }
}
