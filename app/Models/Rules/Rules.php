<?php

namespace App\Models\Rules;

use App\Layer\Layer;
use App\Models\Sector\Sector;

class Rules extends Layer
{
    /**
     * Table name in Database
     *
     * @var string
    */
    protected $table = 'Framework_Rules';

    /**
     * Primary Key
     *
     * @var string
    */
    protected $prefix = 'Framework_Rule';

    /**
     * @param App\Models\Sector\Sector $sector
     * @return null|object
    */
    public function getRulesBySector(Sector $sector): ?object
    {
        return $this->findBy($sector->Framework_Rule)->first();
    }
}
