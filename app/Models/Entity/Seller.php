<?php

namespace App\Models\Entity;

use App\Layer\Layer;

class Seller extends Layer
{
    /**
     * Primary Key
     *
     * @var string
    */
    protected $table = 'VENDEDORES';

    /**
     * Primary Key
     *
     * @var string
    */
    protected $prefix = 'VENDEDOR';

    /**
     * @param int $entity
     * @return null|object
     */
    public function sellerByNumber(int $entity): ?object
    {
        return $this->findBy($entity)->first();
    }
}
