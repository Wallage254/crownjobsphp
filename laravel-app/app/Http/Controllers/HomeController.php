<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $testimonials = Testimonial::where('is_active', true)->latest()->get();
        
        return view('home', compact('categories', 'testimonials'));
    }

    public function jobs(Request $request)
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
        $categories = Category::where('is_active', true)->get();

        return view('jobs', compact('jobs', 'categories'));
    }

    public function jobDetail($id)
    {
        $job = Job::findOrFail($id);
        return view('job-detail', compact('job'));
    }

    public function admin()
    {
        $jobs = Job::latest()->get();
        $applications = \App\Models\Application::with('job')->latest()->get();
        $testimonials = Testimonial::latest()->get();
        $messages = \App\Models\Message::latest()->get();
        
        return view('admin', compact('jobs', 'applications', 'testimonials', 'messages'));
    }
}
