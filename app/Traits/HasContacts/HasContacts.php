<?php

namespace App\Traits\HasContacts;

use Lecturize\Addresses\Traits\HasContacts as OriginalHasContacts;
use Lecturize\Addresses\Models\Contact;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

trait HasContacts {

    use OriginalHasContacts;

    /**
     * Retrieve the first or create a new contact associated with the model.
     *
     * @return mixed
     */
    public function getContact() {
        // Ensure a contact is associated with the model, or create a new one
        return $this->contacts()->firstOrCreate();
    }

    /**
     * Set the contact details for the model.
     * This method handles the contact input and adds or updates the contact information.
     *
     * @param array $contact Contact data to set for the model.
     * @return $this
     */
    public function setContact($contact) {
        // Ensure contact is an array and not empty
        if (!is_array($contact) || empty($contact)) {
            return $this;
        }

        // If the contact is wrapped in a 'contact' key, unwrap it
        if (!empty($contact["contact"])) {
            $contact = $contact["contact"];
        }

        
        // Get valid contact columns
        $contactColumns = $this->getAllowedCollums();

        $contact = collect($contact)->only($contactColumns)->toArray();

        
        // Set the contact as primary by default if not provided
        if (!isset($contact["is_primary"])) {
            $contact["is_primary"] = true;
        }

        // Update existing contact or add a new one
        if ($this->hasContacts() && $currentContact = $this->getContact()) {
            $this->updateContact($currentContact, $contact);
        } else {
            $this->addContact($contact);
        }

        return $this;
    }

    /**
     * Define the relation to retrieve the model's contact.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function contact()
    {
        return $this->morphOne(Contact::class, 'contactable')->withDefault([
            'type' => 'default',
            'first_name' => null,
            'middle_name' => null,
            'last_name' => null,
            'company' => null,
            'vat_id' => null,
            'position' => null,
            'phone' => null,
            'mobile' => null,
            'fax' => null,
            'email' => $this->email,
            'website' => null,
            'address_id' => null,
            'is_public' => 0,
            'is_primary' => 0,
            'notes' => null,
        ]);
    }


    private function getAllowedCollums(){

        return [
            'type',
            'first_name',
            'middle_name',
            'last_name',
            'company',
            'vat_id',
            'position',
            'phone',
            'mobile',
            'fax',
            'email',
            'website',
            'is_public',
            'is_primary',
            'notes',
        ];

    }

    
    
}
