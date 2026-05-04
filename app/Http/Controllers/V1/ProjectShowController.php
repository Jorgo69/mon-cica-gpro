<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectShowController extends Controller
{
    public function index(string $projectId){

        return view('pages.project.show', [
            'projectId' => $projectId,
        ]);
    }
}
