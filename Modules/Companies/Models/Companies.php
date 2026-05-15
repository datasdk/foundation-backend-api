<?php

namespace Modules\Companies\Models;


use DataSDK\Addresses\Traits\HasAddresses;
use DataSDK\Addresses\Traits\HasContacts;
use Spatie\Tags\HasTags;
use App\Models\User;
use ActionModel;
use Modules\Media\Traits\InteractsWithMedia;
use Modules\Media\Contracts\HasMedia;
use DataSDK\Tools\Traits\Nestable;
use Modules\Companies\Http\Scopes\CompanyScopes;
use Modules\Companies\Models\CompanyApplication;
use Modules\Teams\Models\Team;
use Modules\Teams\Traits\HasTeamMembers;
use Modules\Teams\Traits\HasTeams;


class Companies extends ActionModel implements HasMedia
{

    // Traits to add functionality for addresses, contacts, tags, and media interaction
    use HasAddresses;
    use HasContacts;
    use HasTags;
    use InteractsWithMedia;
    use Nestable;
    use CompanyScopes;
    use HasTeamMembers;
    use HasTeams;
 
    

    // Define sluggable attribute (slug will be generated from the name)
    public $sluggable = 'name';

    // Define translatable attributes (currently none defined)
    public $translatable = ['slug'];

    // Define fillable attributes for mass assignment
    protected $fillable = [
        "name",    // Company name
        "vat",     // VAT number
        "slug",
        "logo",    // Company logo
        "is_primary" // Whether the company is primary or not
    ];

    // Define appends to include additional attributes not stored in the database
    protected $appends = ['country'];



    public function createTeamName(){

        return $this->name . " / cvr. ".$this->vat;

    }

    
    public function getMorphClass()
    {
        return self::class;
    }
    /**
     * Get the country attribute.
     * This is a dynamic attribute that always returns "dk".
     *
     * @return string
     */
    public function getCountryAttribute()
    {
        return "dk";  // This always returns "dk" for Denmark
    }



    public function setSubsidiaries(array $ids){


        $this->unsetAllsubsidiaries();
  

        foreach($ids as $id){

            $company = Companies::find($id);
            
           // $company->fixTree();

            if($company && $company->parent_id == null){
                
                $company->parent_id = $this->id;

                $company->save();
           
            }
            
        }


        return $this;

    }



    public function hasChild($id): bool
    {

        return $this->subsidiaries()->where('id', $id)->exists();

    }


    public function unsetAllsubsidiaries()
    {

        return $this->subsidiaries()->update(["parent_id" => null]);

    }


    public function subsidiaries(){

        return $this->children();

    }


    public function applications()
    {

        return $this->hasMany(CompanyApplication::class, 'company_id');

    }


    /**
     * Get the primary company.
     * This method finds the primary company, or the first company if none is primary.
     *
     * @return \Modules\Companies\Models\Companies|null
     */

    public static function getPrimary()
    {

        $c = Companies::where("is_primary", 1)->first();

        if (!$c) {
            $c = Companies::first(); // If no primary company, return the first company
        }

        if (!$c) {
            return null; // Return null if no company exists
        }

        return $c;
    }


   

    /**
     * Find a company by its VAT number.
     *
     * @param string $vat
     * @return \Modules\Companies\Models\Companies|null
     */
    public static function findByVat($vat)
    {

        return Companies::where('vat', $vat)->first(); // Find company by VAT number

    }


    /**
     * Check if a company with the given VAT number exists.
     *
     * @param string $vat
     * @return bool
     */
    public static function exists($vat)
    {

        return Companies::where('vat', $vat)->exists(); // Check if a company exists with the given VAT number

    }


}
