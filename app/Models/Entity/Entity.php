<?php

namespace App\Models\Entity;

use App\Layer\Layer;

class Entity extends Layer
{
     /**
     * Table Name in Database.
     *
     * @var string
    */
    protected $table = 'USUARIOS_ARTIA';

    /**
     * Primary Key in table.
     *
     * @var string
    */
    protected $prefix = 'USUARIO_ARTIA';

    /**
     * @return null|object
    */
    public function getUserByNumber(int $number): ?object
    {
        return $this->find()->where(['COD_PROCFIT' => $number])->first();
    }
}
