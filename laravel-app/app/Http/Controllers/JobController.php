<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Job::query();

        // Apply filters
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('location') && $request->location) {
            $query->where('location', 'ILIKE', '%' . $request->location . '%');
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'ILIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'ILIKE', '%' . $request->search . '%')
                  ->orWhere('company', 'ILIKE', '%' . $request->search . '%');
            });
        }

        if ($request->has('salaryMin') && $request->salaryMin) {
            $query->where('salary_min', '>=', $request->salaryMin);
        }

        if ($request->has('salaryMax') && $request->salaryMax) {
            $query->where('salary_max', '<=', $request->salaryMax);
        }

        $jobs = $query->orderBy('created_at', 'desc')->get();

        return response()->json($jobs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary_min' => 'nullable|integer',
            'salary_max' => 'nullable|integer',
            'job_type' => 'string',
            'is_urgent' => 'boolean',
            'visa_sponsored' => 'boolean',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'workplace_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle file uploads
        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('uploads', 'public');
        }

        if ($request->hasFile('workplace_images')) {
            $workplaceImages = [];
            foreach ($request->file('workplace_images') as $image) {
                $workplaceImages[] = $image->store('uploads', 'public');
            }
            $data['workplace_images'] = $workplaceImages;
        }

        $job = Job::create($data);

        return response()->json($job, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::findOrFail($id);
        return response()->json($job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $job = Job::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'company' => 'string|max:255',
            'category' => 'string|max:255',
            'location' => 'string|max:255',
            'description' => 'string',
            'requirements' => 'string',
            'salary_min' => 'nullable|integer',
            'salary_max' => 'nullable|integer',
            'job_type' => 'string',
            'is_urgent' => 'boolean',
            'visa_sponsored' => 'boolean',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'workplace_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle file uploads
        if ($request->hasFile('company_logo')) {
            if ($job->company_logo) {
                Storage::disk('public')->delete($job->company_logo);
            }
            $data['company_logo'] = $request->file('company_logo')->store('uploads', 'public');
        }

        if ($request->hasFile('workplace_images')) {
            if ($job->workplace_images) {
                foreach ($job->workplace_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $workplaceImages = [];
            foreach ($request->file('workplace_images') as $image) {
                $workplaceImages[] = $image->store('uploads', 'public');
            }
            $data['workplace_images'] = $workplaceImages;
        }

        $job->update($data);

        return response()->json($job);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::findOrFail($id);
        
        // Delete associated files
        if ($job->company_logo) {
            Storage::disk('public')->delete($job->company_logo);
        }

        if ($job->workplace_images) {
            foreach ($job->workplace_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted successfully']);
    }
}
