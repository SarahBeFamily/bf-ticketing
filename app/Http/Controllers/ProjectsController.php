<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use App\Models\SystemSetting;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the projects.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter') ? $request->query('filter') : '';
        $per_page = $request->query('per_page') ? $request->query('per_page') : 15;
        $page = $request->page ? $request->page : 1;
        $settings = SystemSetting::allSettingsCollection();
        $users = User::role('team')->get();

        if (auth()->user()->hasRole('customer')) {
            $companies = Company::where('workers', 'LIKE', '%'.auth()->user()->id.'%')->first();
            // $projects = $companies ? Project::where('company_id', $companies->id)->paginate($per_page, ['*'], 'page', $page) : [];
            if ($companies) {
                $projects = QueryBuilder::for(Project::where('company_id', $companies->id))
                ->allowedFilters(['status', 'division', 'company_id', 'assigned_to'])
                ->allowedSorts('started_at', 'deadline', '-started_at', '-deadline')
                ->paginate($per_page, ['*'], 'page', $page)
                ->appends(request()->query());
            } else {
                return Redirect::route('dashboard')->with('error', 'Non sei stato assegnato ad alcuna azienda cliente, contatta l\'amministratore per risolvere!');
            }

        } else {
            $companies = Company::all();
            $projects = QueryBuilder::for(Project::class)
                ->allowedFilters(['status', 'division', 'company_id', 'assigned_to'])
                ->allowedSorts('started_at', 'deadline', '-started_at', '-deadline')
                ->paginate($per_page, ['*'], 'page', $page)
                ->appends(request()->query());
            
        }

        return view('projects.index', compact('projects', 'companies', 'users', 'settings', 'filter'));
    }

    /**
     * Enable filtering of the projects.
     * 
     * @param Request $request
     * @return RedirectResponse
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

        if ($request->has('company_id') && $request->company_id != '') {
            $slug[] = 'filter[company_id]='.$request->company_id;
        }

        if ($request->has('assigned_to') && $request->assigned_to != '') {
            $slug[] = 'filter[assigned_to]='.$request->assigned_to;
        }

        if ($request->has('sort') && $request->sort != '') {
            $slug[] = 'sort='.$request->sort;
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
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (auth()->user()->hasRole('customer')) {
            return redirect()->route('projects.index')->with('error', 'Non hai i permessi per creare un progetto!');
        }

        $project = new Project();
        $customers = $project->customers();
        $team = $project->team_members();
        $settings = SystemSetting::allSettingsCollection();
        $companies = Company::all();
        return view('projects.create', compact('customers', 'companies', 'team', 'settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request) : RedirectResponse
    {
        // Validate the request...
        $request->validate([
            'name' => 'required|unique:projects|max:255',
            'company_id' => 'required|exists:companies,id',
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
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('projects.show', ['project' => Project::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified project.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $customers = $project->customers();
        $team = $project->team_members();
        $settings = SystemSetting::allSettingsCollection();
        $companies = Company::all();
        
        return view('projects.edit', compact('project', 'companies', 'customers', 'team', 'settings'));
    }

    /**
     * Update project in storage.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request) : RedirectResponse
    {
        $project = $request->id;
        $data = Project::findOrFail($project);

        // Validate the request...
        $request->validate([
            'name' => 'required|max:255',
            'company_id' => 'required|exists:companies,id',
            'assigned_to' => 'required|array',
            'status' => 'required',
            'division' => 'required',
        ]);

        $data->update($request->all());

        return Redirect::route('projects.index', $data)->with('success', 'Progetto aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // Project::destroy($id);
        $project = Project::find($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Progetto eliminato con successo!');
    }

}
