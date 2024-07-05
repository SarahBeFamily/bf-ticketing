<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Project;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter') ? $request->query('filter') : 'all';
        $per_page = $request->query('per_page') ? $request->query('per_page') : 15;
        $page = $request->page ? $request->page : 1;

        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters(['status', 'division', 'user_id', 'assigned_to'])
            ->allowedSorts('order', 'name', 'created_at', 'updated_at')
            ->paginate($per_page, ['*'], 'page', $page)
            ->appends(request()->query());
        
        return view('projects.index', compact('projects', 'filter'));
    }

    /**
     * Enable sorting of the projects.
     */
    public function sort(Request $request)
    {
        $projects = Project::orderBy($request->sort_by, $request->sort_order)->paginate(15);
        return view('projects.index', compact('projects'));
    }

    /**
     * Enable filtering of the projects.
     */
    public function filter(Request $request): RedirectResponse
    {
        $slug = [];
        
        if ($request->has('division') && $request->division != '') {
            $slug[] = 'filter[division]='.$request->division;
        }

        if ($request->has('status') && $request->status != '') {
            $slug[] = 'filter[status]='.$request->status;
        }

        if ($request->has('user_id') && $request->user_id != '') {
            $slug[] = 'filter[user_id]='.$request->user_id;
        }

        if ($request->has('assigned_to') && $request->assigned_to != '') {
            $slug[] = 'filter[assigned_to]='.$request->assigned_to;
        }

        $slug = implode('&', $slug);

        return Redirect::route('projects.index', $slug);
    }

    /**
     * Paginate the projects.
     */
    public function paginate(Request $request)
    {
        $page = $request->page;
        $projects = Project::paginate(15, ['*'], 'page', $page);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     * Passing data to the view
     */
    public function create()
    {
        $project = new Project();
        $customers = $project->customers();
        $team = $project->team_members();
        return view('projects.create', compact('customers', 'team'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'name' => 'required|unique:projects|max:255',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',
            'assigned_to' => 'required|array',
            'status' => 'required',
            'division' => 'required',
        ]);

        // Check if project already exists
        $id = $request->id;
        if ($id) {
            Project::where('id', $id)->update($request->all());
        } else {
            // Store the record, using the validated data...
            $project = Project::create($request->all());
        }

        return Redirect::route('projects.index', $project)->with('success', 'Progetto creato con successo!');
    }

    /**
     * Display the specified project.
     *
     * @param int $id
     */
    public function show($id)
    {
        return view('projects.show');
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param int $id
     */

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $customers = $project->customers();
        $team = $project->team_members();
        
        return view('projects.edit', compact('project', 'customers', 'team'));
    }

    /**
     * Update project in storage.
     */
    public function update(Request $request) : RedirectResponse
    {
        $project = $request->id;
        $data = Project::findOrFail($project);
        $data->update($request->all());

        return Redirect::route('projects.index', $data)->with('success', 'Progetto aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        // Project::destroy($id);
        $project = Project::find($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Progetto eliminato con successo!');
    }

}
