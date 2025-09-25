<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\Activity;
use App\Models\Content;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getDashboardStats()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_schools' => School::count(),
            'total_activities' => Activity::count(),
            'total_contents' => Content::count()
        ]);
    }
}
