<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectCreateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        // return ProjectResource::collection($request->user()->projects->load('boards', 'boards.tickets'));
        // return $request->user()->projects;

        // return ProjectResource::collection($request->user()->projects);

        $projects = Project::where('user_id', Auth::id())->get();
        return ProjectResource::collection($projects);
        // return ProjectResource::collection($projects->load('boards'));
    }

    public function store(ProjectCreateRequest $request)
    {
        $data = $request->validated();

        // return gettype($data);
        $data['user_id'] = Auth::id();

        $project = Project::create($data);

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        $project->load('boards', 'boards.tickets');

        return new ProjectResource($project);
    }

    public function update(ProjectCreateRequest $request, Project $project)
    {
        $data = $request->validated();

        abort_if($project->user_id !== $request->user()->id, 403, 'You cant not allowed to update this project');

        $project->update($data);

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        abort_if($project->user_id !== Auth::id(), 403, 'You cant not allowed to delete this project');

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
