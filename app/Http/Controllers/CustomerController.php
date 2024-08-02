<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
        // Get users with role customer
        // $customers = Role::findByName('customer')->users;
        $filter = $request->query('filter') ? $request->query('filter') : 'all';
        $per_page = $request->query('per_page') ? $request->query('per_page') : 15;
        $page = $request->page ? $request->page : 1;

        $customers = User::role('customer')->paginate($per_page, ['*'], 'page', $page)->appends(request()->query());

        return view('customers.index', compact('customers', 'filter', 'per_page', 'page'));
    }

    /**
     * Show the form for creating a new customer.
     *
     * @return void
     */
    public function create()
    {
        return view('customers.create');
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
        ]);

        // Add new user with customer role
        $customer = new User;

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);

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

        return view('customers.show', ['customer' => $customer]);
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

        return view('customers.edit', ['customer' => $customer, 'user' => $customer]);
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
            'name' => 'required|unique:users|max:255',
            'email' => 'required|email',
        ]);

        $customer = User::find($id);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        $customer->save();
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
        $search = $request->search;
        $customers = User::where('name', 'like', '%' . $search . '%')->paginate(15);
        return view('customers.index', compact('customers'));
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
