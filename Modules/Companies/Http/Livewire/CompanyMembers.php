<?php

namespace Modules\Companies\Http\Livewire;

use Livewire\Component;
use Modules\Crm\Services\UserService;
use Modules\Companies\Models\User;
use Modules\Companies\Models\Companies;
use Role;


class CompanyMembers extends Component
{
    public $members = [];         
    public $selectedNewMembers = [];     
    public $availableRoles = [];
    
    public $showMemberModal = false;
    public $memberOption = 'search';
    
    public $searchMember = null;
    public $searchQuery = '';
    public $searchResults = [];
    public $isSearching = false;
    public $showSearchField = true;
    
    public $newmember = [
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'invite' => true,
        'password' => '',
    ];
    
    public $createErrors = [];
    public $submitLoading = false;
    
    protected $userService;
    
    protected $rules = [
        'newmember.first_name' => 'required|min:2',
        'newmember.email' => 'required|email',
        'newmember.phone' => 'nullable',
    ];
    
    protected $messages = [
        'newmember.first_name.required' => 'Fornavn er påkrævet',
        'newmember.first_name.min' => 'Fornavn skal være mindst 2 tegn',
        'newmember.email.required' => 'Email er påkrævet',
        'newmember.email.email' => 'Indtast en gyldig email',
    ];
    

    public function __construct()
    {

        parent::__construct();

        $this->userService = app(UserService::class);

    }
    

    public function mount($members = [], $availableRoles = [])
    {


        $this->members = $members;

        $this->availableRoles =  Role::where(["guard_name" => "api"])->get();

    }
    

    public function getHasMembersProperty()
    {

        return $this->members && count($this->members) > 0 || count($this->selectedNewMembers) > 0;

    }
    

    // Debounced søgning - FIXET VERSION
    public function updatedSearchQuery($value)
    {

        $this->searchMember = null;

        $this->searchResults = [];
        

        if (strlen($value) < 2) {

            $this->isSearching = false;

            return;

        }

        
        $this->isSearching = true;
        
        // Brug Livewire's debounce i stedet for usleep
        // Debounce logikken håndteres nu i Blade med wire:model.debounce
        // Vi udfører bare søgningen direkte
        
        $this->searchResults = User::where(function($query) use ($value) {
                $query->where('first_name', 'LIKE', "%{$value}%")
                      ->orWhere('last_name', 'LIKE', "%{$value}%")
                      ->orWhere('email', 'LIKE', "%{$value}%");
            })
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                    'full_data' => $user->toArray()
                ];
            })
            ->toArray();
        

        $this->isSearching = false;

    }
    

    // Vælg bruger fra søgeresultater
    public function selectUser($userId)
    {
        
        $this->searchMember = User::find($userId);
        $this->searchQuery = $this->searchMember->first_name . ' ' . $this->searchMember->last_name;
        $this->searchResults = [];
        $this->showSearchField = false;

    }
    

    // Fjern valgt bruger
    public function clearSelectedUser()
    {

        $this->searchMember = null;
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->showSearchField = true;

    }

    
    public function submitMember()
    {

        $this->submitLoading = true;
        

        if ($this->memberOption === 'search') {

            if (!$this->searchMember) {

                $this->addError('searchMember', 'Vælg en bruger fra søgeresultaterne');

                $this->submitLoading = false;

                return;

            }
            

            // Check om brugeren allerede er tilføjet
            $existingMember = collect($this->members)->firstWhere('id', $this->searchMember->id);

            if ($existingMember) {

                $this->addError('searchMember', 'Brugeren er allerede tilføjet');

                $this->submitLoading = false;

                return;

            }
         
//$this->searchMember;
            $this->members[] = [
                'id' => $this->searchMember->id,
                'first_name' => $this->searchMember->first_name,
                'middle_name' => $this->searchMember->middle_name,
                'last_name' => $this->searchMember->last_name,
                'email' => $this->searchMember->email,
                'phone' => $this->searchMember->phone ?? '',
                'is_new' => false,
                'role_id' => $this->availableRoles->first()->id ?? 0
            ];
            
            
            /*;
            */
            
        } else {
            
            // Valider først
            $this->validate();
            
            // Tjek om email allerede er i systemet
            $existingUser = User::where('email', $this->newmember['email'])->first();
            if ($existingUser) {
                $this->addError('newmember.email', 'Denne email er allerede registreret i systemet');
                $this->submitLoading = false;
                return;
            }
            
            // Opret bruger med UserService
            try {
                // Forbered data til UserService
                $userData = [
                    'first_name' => $this->newmember['first_name'],
                    'middle_name' => $this->newmember['middle_name'],
                    'last_name' => $this->newmember['last_name'],
                    'email' => $this->newmember['email'],
                    'contact' => [
                        'phone' => $this->newmember['phone'],
                    ],
                    'invite' => $this->newmember['invite'],
                    'email_verified' => !$this->newmember['invite'], // Hvis ikke invite, så er email verified
                    'send_activation' => false, // Bruger invite i stedet
                ];
                
                // Tilføj password hvis det er angivet
                if (!empty($this->newmember['password'])) {
                    $userData['password'] = $this->newmember['password'];
                }
                
                // Opret bruger med UserService
                $newUser = $this->userService->create($userData);
                
                // Tilføj den nye bruger til listen
                $userDataForList = [
                    'id' => $newUser->id,
                    'first_name' => $newUser->first_name,
                    'middle_name' => $newUser->middle_name,
                    'last_name' => $newUser->last_name,
                    'email' => $newUser->email,
                    'phone' => $newUser->phone ?? '',
                    'is_new' => true,
                    'invite' => $this->newmember['invite'],
                ];
                
                $this->selectedNewMembers[] = $userDataForList;
                $this->members[] = $userDataForList;
                
            } catch (\Exception $e) {
                $this->addError('newmember.email', 'Kunne ikke oprette bruger: ' . $e->getMessage());
                $this->submitLoading = false;
                return;
            }
        }
        
        // Emit opdatering
        $this->emitUp('membersUpdated', $this->members);
        
        // Reset og luk modal
        $this->resetForm();
        $this->showMemberModal = false;
        $this->submitLoading = false;
    }
    
    public function removeExisting($id)
    {
        // Fjern fra både members og selectedNewMembers
        $this->members = collect($this->members)->filter(function($user) use ($id) {
            return $user['id'] != $id;
        })->values()->toArray();
        
        $this->selectedNewMembers = collect($this->selectedNewMembers)->filter(function($user) use ($id) {
            return $user['id'] != $id;
        })->values()->toArray();
        
        $this->emitUp('membersUpdated', $this->members);
    }
    
    public function resetForm()
    {

        $this->memberOption = 'search';
        $this->searchMember = null;
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->isSearching = false;
        $this->showSearchField = true;
        $this->submitLoading = false;
        
        $this->newmember = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'invite' => true,
            'password' => '',
        ];
        
        $this->createErrors = [];
        $this->resetErrorBag();
        
        // DETTE MANGEDE: Luk modalen
        $this->showMemberModal = false;
        
    }
    
    // Validering i realtid for email
    public function updatedNewmemberEmail($value)
    {
        $this->validateOnly('newmember.email');
    }
    
    // Validering i realtid for fornavn
    public function updatedNewmemberFirstName($value)
    {
        $this->validateOnly('newmember.first_name');
    }
    
    public function render()
    {
        return view('companies::livewire.company-members');
    }
}
