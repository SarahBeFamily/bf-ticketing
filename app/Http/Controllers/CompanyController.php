<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the companies.
     * 
     * @return void
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter') ? $request->query('filter') : 'all';
        $per_page = $request->query('per_page') ? $request->query('per_page') : 15;
        $page = $request->page ? $request->page : 1;

        $companies = Company::paginate($per_page, ['*'], 'page', $page)->appends(request()->query());

        return view('companies.index', compact('companies', 'filter', 'per_page', 'page'));
    }

    /**
     * Paginate the companies.
     * 
     * @param Request $request
     */
    public function paginate(Request $request)
    {
        // $companies = Company::paginate($request->per_page);
        // return response()->json($companies);
        $page = $request->page;
        $companies = Company::paginate(15, ['*'], 'page', $page);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     * 
     * @return void
     */
    public function create()
    {
        // Add collection of users with role customer
        $users = User::role('customer')->get();
        return view('companies.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'name' => 'required|unique:companies|max:255',
            'logo' => 'mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $company = new Company;
        $company->name = $request->name;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $fileSize = $logo->getSize();
            $fileName = str_replace(' ', '_', $company->name).'-logo-'.time() . '.' . $logo->getClientOriginalExtension();
            
            $filePath = $logo->storeAs('logo_aziende', $fileName, 'public_uploads');
            $company->logo = $filePath;

            $attachment = Attachment::create([
                'filename' => $fileName,
                'path' => $filePath,
                'mime_type' => $logo->getClientMimeType(),
                'size' => $fileSize,
                'ticket_id' => null,
                'user_id' => auth()->user()->id,
                'project_id' => null,
                'company_id' => $company->id,
            ]);

            $attachment->setCompanyAttribute($company->id);
        }

        if ($request->has('workers')) {
            $company->workers = json_encode($request->workers);
        }

        if ($company->workers) {
            $workers = json_decode($company->workers);
            // Assegno l'azienda ai dipendenti
            foreach ($workers as $worker) {
                $user = User::find($worker);
                $user->company = $company->id;
                $user->save();
            }
        }

        $company->save();

        return redirect()->route('companies.index')->with('success', 'Azienda creata con successo.');
    }

    /**
     * Display the specified company.
     * 
     * @param Company $company
     * @return void
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified company.
     * 
     * @param Company $company
     * @return void
     */
    public function edit(Company $company)
    {
        $users = User::role('customer')->get();
        return view('companies.edit', compact('company', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request
     * @param Company $company
     * @return void
     */
    public function update(Request $request, Company $company)
    {
        // Validate the request...
        $request->validate([
            'name' => 'required|unique:companies,name,' . $company->id . '|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            'workers' => 'required',
        ]);

        $company->name = $request->name;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $fileSize = $logo->getSize();
            $fileName = str_replace(' ', '_', $company->name).'-logo-'.time() . '.' . $logo->getClientOriginalExtension();
            
            $filePath = $logo->storeAs('logo_aziende', $fileName, 'public_uploads');
            $company->logo = $filePath;

            // Salvo il logo tra gli allegati
            $attachment = Attachment::create([
                'filename' => $fileName,
                'path' => $filePath,
                'mime_type' => $logo->getClientMimeType(),
                'size' => $fileSize,
                'ticket_id' => null,
                'user_id' => auth()->user()->id,
                'project_id' => null,
                'company_id' => $company->id,
            ]);

            $attachment->setCompanyAttribute($company->id);
        }

        if ($request->has('workers')) {
            $company->workers = json_encode($request->workers);
            // Assegno l'azienda ai dipendenti
            foreach ($request->workers as $worker) {
                $user = User::find($worker);
                $user->company = $company->id;
                $user->save();

                // Se ne ho deselezionati alcuni, rimuovo l'azienda se presente
                $user_old_company = User::where('company', $company->id)->whereNotIn('id', $request->workers)->first();
                if ($user_old_company) {
                    $user_old_company->company = null;
                    $user_old_company->save();
                }
            }
        }

        $company->save();

        return redirect()->route('companies.index')->with('success', 'Azienda aggiornata con successo.');
    }

    /**
     * Delete the company's logo.
     * 
     * @param Company $company
     * @return void
     */
    public function delete_logo(Company $company)
    {
        // Delete the file from the storage
        Storage::delete($company->logo);
        
        if (file_exists(public_path('/storage/logo_aziende/'.$company->logo))) {
            unlink(public_path('/storage/logo_aziende/'.$company->logo));
        }
        $company->logo = null;

        $company->save();
        return redirect()->route('companies.edit', $company)->with('success', 'Logo eliminato con successo.');
    }

    /**
     * Search for a company.
     * 
     * @param Request $request
     * @return void
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $companies = Company::where('name', 'like', "%$search%")->paginate(10);
        return view('companies.search', compact('companies'));
    }
}
