<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TeamImageController;
use App\Services\APIService;
use App\Helpers\ImageBuilder;

class HomeController extends Controller
{
    protected $teamImageController;

    public function __construct()
    {
        $this->teamImageController = new TeamImageController(new APIService, new ImageBuilder);
    }

    public function index(Request $request)
    {
        // ... other code ...

        return view('pages.home', [
            'selectedDate' => $selectedDate,
            'todayMatches' => $todayMatches,
            'teamImageController' => $this->teamImageController
        ]);
    }
}
