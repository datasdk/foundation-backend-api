<?php

namespace Modules\Companies\Http\Controllers;

use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;
use Modules\Companies\Http\Requests\CompanyRequest;
use Modules\Companies\Models\Companies;
use Modules\Teams\Models\Team;
use Modules\Teams\Models\User;
use Modules\Crm\Services\UserService;
use Illuminate\Support\Facades\Log;

class CompaniesController extends OrionBaseController
{
    protected $model = Companies::class;
    protected $request = CompanyRequest::class;

    protected $includes = [
        "addresses",
        "address",
        "contacts",
        "contact",
        "images",
        "team",
        "team.members",
        "team.members.roles",
        "team.groups",
        "members",
        "members.roles",
        "subsidiaries",
        "subsidiaries.address",
        "subsidiaries.contact",
        "subsidiaries.members",
        "subsidiaries.user.address",
        "subsidiaries.user.contact",
    ];

    /**
     * Display a listing of companies.
     */
    public function index(Request $req)
    {
        return view('companies::index');
    }

    /**
     * Show form to create a company.
     */
    public function create(Request $req)
    {
        return view('companies::create');
    }

     /**
     * Show a specific company.
     */
    public function show(Request $req, ...$args)
    {

        $company = Companies::with(["address","contact","members"])->findOrFail($args[0]);

        return view('companies::show', compact('company'));

    }

    /**
     * Show form to edit a company.
     */
    public function edit(Request $req, ...$args)
    {

        $company = Companies::findOrFail($args[0]);

        return view('companies::edit', compact('company'));

    }



    /**
     * Store a new company.
     */
    public function store(Request $req)
    {

        $company = Companies::create([
            'name' => ucfirst($req->name ?? ''),
            'vat' => $req->vat ?? null,
            'is_primary' => true,
        ]);




        $this->handleRelatedData($company, $req);


        return redirect()->route('companies.index')
                         ->with('success', 'Company created successfully.');
    }



    /**
     * Update a company.
     */
    public function update(Request $req, ...$args)
    {

        $company = Companies::findOrFail($args[0]);

        $company->update([
            'name' => ucfirst($req->name ?? $company->name),
            'vat' => $req->vat ?? $company->vat,
            'is_primary' => $req->is_primary ?? $company->is_primary,
        ]);

       
        $this->handleRelatedData($company, $req);


        return redirect()->route('companies.index')
                         ->with('success', 'Company updated successfully.');
    }

    /**
     * Delete a company.
     */
    public function destroy(Request $req, ...$args)
    {
        $company = Companies::findOrFail($args[0]);
        $company->delete();

        return redirect()->route('companies.index')
                         ->with('success', 'Company deleted successfully.');
    }


    /**
     * Håndter relaterede data direkte i controlleren
     */
    protected function handleRelatedData(Companies $company, $req): void
    {

        $team = $company->ensureHasTeam();


        // Adresse og kontakt
        if ($req->filled('address')) $company->setAddress($req->address);

        if ($req->filled('addresses')) $company->setAddresses($req->addresses);

        if ($req->filled('contact')) $company->setContact($req->contact);

        if ($req->filled('contacts')) $company->setContacts($req->contacts);


        // Datterselskaber
        if ($req->filled('subsidiaries')) $company->setSubsidiaries($req->subsidiaries);


        
        
        $team->removeAllMembers();


        // Team & medlemmer
        if ($req->filled('members') || $req->filled('new_members')) {

            
            // Tilføj eksisterende medlemmer
            if ($req->filled('members')) {


                foreach ($req->members as $member) {

                    if (!empty($member['id']) && $user = User::find($member['id'])) {

                        $team->addUserToTeam($user, $member['role_id'] ?? null);

                    }

                }


            }



            // Tilføj nye medlemmer
            if ($req->filled('new_members')) {

                foreach ($req->new_members as $memberData) {

                    $user = app(UserService::class)->create($memberData);

                    $team->addUserToTeam(User::find($user->id), $memberData['role_id'] ?? null);

                }

            }

        }


        // Billeder/logo
        if ($req->filled('images')) {

            foreach ($req->images as $image) {

                if ($image) {

                    try {

                        $company->addMedia($image)->toMediaCollection('logo');

                    } catch (\Exception $e) {

                        Log::error("Kunne ikke uploade logo: {$e->getMessage()}");

                    }

                }

            }

        }


    }


}
