<?php

namespace Modules\Companies\Http\Controllers\Api;

use App\Http\Controllers\OrionRelationController;
use Orion\Http\Requests\Request as OrionRequest;
use Modules\Companies\Models\User;
use Modules\Companies\Models\Companies;
use Modules\Companies\Http\Requests\CompanyRequest;
use Modules\Crm\Services\UserService;


class UserCompanyController extends OrionRelationController
{

    protected $model = User::class;

    protected $request = CompanyRequest::class;

    protected $alwaysIncludes = ["companies"];

    protected $relation = 'companies';



    /**
     * Opret og tilknyt virksomhed til bruger
     */
    public function store(OrionRequest $request, ...$args)
    {


        $companyData = $request->validated();

        $user = User::findOrFail($user_id);


        $company = Companies::create([
            'name' => $companyData['name'] ?? null,
            'vat'  => $companyData['vat'] ?? null,
        ]);


        if (!empty($companyData['address'])) {

            $company->setAddress($companyData['address']);

        }


        if (!empty($companyData['contact'])) {

            $company->setContact($companyData['contact']);

        }


        $company->addOwner($user);


        return $user->load('companies');

    }


    /**
     * Opdater virksomhedsdata tilknyttet en bruger
     */
    public function update(OrionRequest $request, ...$args)
    {


        $user = User::findOrFail($user_id);

        $company = Companies::findOrFail($company_id);

        $companyData = $request->validated();


        if ($companyData) {

            $company->update(
                collect($companyData)->only('name', 'vat')->toArray()
            );

            if (!empty($companyData['address'])) {
                
                $company->setAddress($companyData['address']);

            }


            if (!empty($companyData['contact'])) {

                $company->setContact($companyData['contact']);

            }

        }

        return $user->load('companies');

    }


    /**
     * Fjern relation og evt. slet virksomhed
     */
    public function destroy(OrionRequest $request, ...$args)
    {

        $user = User::findOrFail($user_id);

        $company = Companies::findOrFail($company_id);

        $company->members()->detach($user->id);


        if ($company->members()->count() === 0) {

            $company->delete();

        }


        return response()->json(['message' => 'Virksomhed fjernet fra bruger.']);


    }

    
}
