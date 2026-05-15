<?php

namespace Modules\Companies\Services;

use Modules\Companies\Models\Companies;
use Modules\Teams\Models\Team;
use Modules\Teams\Models\User;
use Modules\Crm\Services\UserService;

class CompanyService
{
    /**
     * Opret et nyt firma med alle tilhørende data
     */
    public function createCompany(array $data): Companies
    {

        // Opret hovedfirma
        $company = Companies::create([
            "name" => ucfirst($data['name'] ?? ''),
            "vat" => $data['vat'] ?? null,
            "is_primary" => $data['is_primary'] ?? true,
        ]);


        // Behandle tilhørende data
        $this->handleRelatedData($company, $data);

        return $company;

    }

    /**
     * Opdater et eksisterende firma
     */
    public function updateCompany(Companies $company, array $data): Companies
    {

        // Opdater hoveddata
        $company->update([
            "name" => ucfirst($data['name'] ?? $company->name),
            "vat" => $data['vat'] ?? $company->vat,
            "is_primary" => $data['is_primary'] ?? $company->is_primary,
        ]);

        // Behandle tilhørende data
        $this->handleRelatedData($company, $data);

        return $company;

    }

    /**
     * Behandler alle relaterede data for et firma
     */
    protected function handleRelatedData(Companies $company, array $data): void
    {
        
        $team = $company->ensureHasTeam();

        
        // Datterselskaber
        if (isset($data['subsidiaries'])) {
            $company->setSubsidiaries($data['subsidiaries']);
        }

        // Team medlemmer
        if (isset($data['members']) || isset($data['new_members'])) {

            $this->handleTeamMembers($team, $data);

        }

       /*
        if (isset($data['address'])) {
            $company->setAddress($data['address']);
        }

        // Flere adresser
        if (isset($data['addresses'])) {
            $company->setAddresses($data['addresses']);
        }

        // Kontakt (primær)
        if (isset($data['contact'])) {
            $company->setContact($data['contact']);
        }

        // Flere kontakter
        if (isset($data['contacts'])) {
            $company->setContacts($data['contacts']);
        }
*/
        // Billeder (logo)
        if (isset($data['images']) && !empty($data['images'])) {
            $this->saveImages($company, $data['images']);
        }

    }

    /**
     * Gem billeder/logo
     */
    protected function saveImages(Companies $company, array $images): void
    {

        try {
            foreach ($images as $image) {
                if ($image) {
                    $company->addMedia($image)->toMediaCollection('logo');
                }
            }
        } catch (\Exception $e) {
            \Log::error("Kunne ikke uploade logo: {$e->getMessage()}");
        }

    }

    /**
     * Håndter team medlemmer (eksisterende og nye)
     */
    protected function handleTeamMembers(?Team $team, array $data): void
    {

        if (!$team) {
            return;
        }

        // Fjern alle eksisterende medlemmer
        $team->removeAllMembers();


        // Tilføj eksisterende medlemmer
        if (isset($data['members'])) {
            foreach ($data['members'] as $member) {
                $user = User::find($member['id'] ?? null);
                if ($user) {
                    $team->addUserToTeam($user, $member['role_id'] ?? null);
                }
            }
        }

        // Opret og tilføj nye medlemmer
        if (isset($data['new_members'])) {
            foreach ($data['new_members'] as $memberData) {
                $user = $this->createNewUser($memberData);
                $team->addUserToTeam($user, $memberData['role_id'] ?? null);
            }
        }
    }

    /**
     * Opret en ny bruger
     */
    protected function createNewUser(array $memberData): User
    {

        $userService = app(UserService::class);

        $user = $userService->create($memberData);

        return User::find($user->id);
    }

    /**
     * Returnerer standard relations til eager loading
     */
    protected function getDefaultRelations(): array
    {
        return [
            'addresses',
            'address',
            'contacts',
            'contact',
            'images',
            'team',
            'team.members',
            'team.members.roles',
            'team.groups',
            'members',
            'members.roles',
            'subsidiaries',
            'subsidiaries.address',
            'subsidiaries.contact',
            'subsidiaries.members',
            'subsidiaries.user.address',
            'subsidiaries.user.contact',
        ];
    }

    /**
     * Opret et datterselskab
     */
    public function createSubsidiary(array $data): Companies
    {
        // Opret datterselskab (is_primary = false)
        $subsidiary = Companies::create([
            "name" => ucfirst($data['name'] ?? ''),
            "vat" => $data['vat'] ?? null,
            "is_primary" => false,
        ]);

        // Behandle adresse og kontakt
        $addressData = array_filter($data['address'] ?? []);
        $contactData = array_filter($data['contact'] ?? []);

        if (!empty($addressData)) {
            $subsidiary->setAddress($addressData);
        }

        if (!empty($contactData)) {
            $subsidiary->setContact($contactData);
        }

        // Gem billeder
        if (isset($data['images']) && !empty($data['images'])) {
            $this->saveImages($subsidiary, $data['images']);
        }

        return $subsidiary->load(['address', 'contact', 'media']);

    }
}
