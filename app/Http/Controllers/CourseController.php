<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show(Course $course)
    {
        // $course = Course::where('slug', $slug)->firstOrFail();
        // return view('courses.show', compact('course'));
        return view('courses.show', compact('course'));
    }
}
