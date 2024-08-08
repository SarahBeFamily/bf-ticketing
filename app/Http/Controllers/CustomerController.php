<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\User;
use App\Models\Project;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter') ? $request->query('filter') : 'all';
        $per_page = $request->query('per_page') ? $request->query('per_page') : 15;
        $page = $request->page ? $request->page : 1;
        $companies = Company::all();

        $customers = QueryBuilder::for(User::role('customer'))
            ->allowedFilters(['company'])
            ->allowedSorts('order', 'name', 'created_at')
            ->paginate($per_page, ['*'], 'page', $page)
            ->appends(request()->query());

        return view('customers.index', compact('customers', 'companies', 'filter', 'per_page', 'page'));
    }

    /**
     * Show the form for creating a new customer.
     *
     * @return void
     */
    public function create()
    {
        $projects = Project::all();
        $companies = Company::all();
        return view('customers.create', compact('projects', 'companies'));
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
            'name' => 'required|unique:users|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'company' => 'required|company.id',
        ]);

        // Add new user with customer role
        $customer = new User;

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->phone = $request->phone ?? null;
        $customer->address = $request->address ?? null;
        $customer->company = $request->company;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatarPath = $avatar->storeAs('avatars', $avatarName, 'public');
            $avatarPath = $avatar->move(public_path('uploads'), $fileName);
            $customer->avatar = $avatarPath;
        }

        // Assign customer role to new user
        $customer->assignRole('customer');

        $customer->save();

        // Redirect to the customer index page with password in message
        return redirect()->route('customers.index')->with('password', $customer->password);
    }

    /**
     * Display the specified customer.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        $customer = User::find($id);
        $company = Company::find($customer->company);

        return view('customers.show', ['customer' => $customer, 'company' => $company]);
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        $customer = User::find($id);
        $companies = Company::all();

        return view('customers.edit', ['customer' => $customer, 'user' => $customer, 'companies' => $companies]);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        // Validate the request...
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:unique:users,email,' . $id,
        ]);

        $customer = User::find($id);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->company = $request->company;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatarPath = $avatar->storeAs('avatars', $avatarName, 'public');
            $avatarPath = $avatar->move(public_path('uploads'), $fileName);
            $customer->avatar = $avatarPath;
        }

        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Cliente aggiornato con successo.');
    }

    /**
     * Paginate the customers.
     * 
     * @param Request $request
     * @return void
     */
    public function paginate(Request $request)
    {
        $page = $request->page;
        $customers = User::paginate(15, ['*'], 'page', $page);
        return view('customers.index', compact('customers'));
    }

    /**
     * Search for a customer.
     * 
     * @param Request $request
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $customers = User::where('name', 'like', "%$search%")->paginate(15);
        $companies = Company::all();
        return view('customers.search', compact('customers', 'companies'));
    }

    /**
     * Enable filtering of the customers.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function filter(Request $request): RedirectResponse
    {
        $slug = [];
        
        if ($request->has('company') && $request->company != '') {
            $slug[] = 'filter[company]='.$request->company;
        }

        $slug = implode('&', $slug);

        return redirect()->route('customers.index', $slug);
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $customer = User::find($id);
        $customer->delete();
    }
}
