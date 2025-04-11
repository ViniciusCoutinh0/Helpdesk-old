<?php

namespace App\Models\Ticket;

use App\Layer\Layer;

class Category extends Layer
{
    /**
     * Primary Key
     *
     * @var string
    */
    protected $table = 'TICKETS_CATEGORIAS';

    /**
     * Primary Key
     *
     * @var string
    */
    protected $prefix = 'TICKET_CATEGORIA';


    /**
     * @param int $id
     * @return null|object
     */
    public function joinDepartament(int $id): ?object
    {
        return $this->find('TICKETS_CATEGORIAS.NOME AS CATEGORIA_NOME, TICKETS_DEPARTAMENTOS.*')
        ->join('TICKETS_DEPARTAMENTOS', 'TICKETS_DEPARTAMENTOS.TICKET_DEPARTAMENTO', '=', ' TICKETS_CATEGORIAS.TICKET_DEPARTAMENTO')
        ->where(['TICKETS_CATEGORIAS.TICKET_CATEGORIA' => $id])
        ->first();
    }

    /**
     * @param int $id
     * @return null|object
    */
    public function getCategoryBySubCategory(SubCategory $subcategory): ?object
    {
        return $this->find()->where(['TICKET_CATEGORIA' => $subcategory->TICKET_CATEGORIA])->first();
    }
}
