<?php

namespace App\Models\Ticket;

use App\Layer\Layer;

class Departament extends Layer
{
    /**
     * Table Name in Database.
     *
     * @var string
     */
    protected $table = 'TICKETS_DEPARTAMENTOS';

    /**
     * Primary Key in table.
     *
     * @var string
     */
    protected $prefix = 'TICKET_DEPARTAMENTO';


    /**
     * @param string $words
     * @return null|array
     */
    public function likeByWords(string $words): ?array
    {
        return $this->find('*, TICKETS_DEPARTAMENTOS.NOME DEPARTAMENTO_NOME')
            ->join('TICKETS_CATEGORIAS', 'TICKETS_CATEGORIAS.TICKET_DEPARTAMENTO', '=', 'TICKETS_DEPARTAMENTOS.TICKET_DEPARTAMENTO')
            ->join('TICKETS_SUB_CATEGORIAS', 'TICKETS_SUB_CATEGORIAS.TICKET_CATEGORIA', '=', 'TICKETS_CATEGORIAS.TICKET_CATEGORIA')
            ->orWhere('TICKETS_SUB_CATEGORIAS.NOME', 'LIKE', '\'%' . $words . '%\'')
            ->orWhere('TICKETS_SUB_CATEGORIAS.DESCRICAO', 'LIKE', '\'%' . $words . '%\'')
            ->orWhere('TICKETS_DEPARTAMENTOS.NOME', 'LIKE', '\'%' . $words . '%\'')
            ->all();
    }

    /**
     * @param App\Models\Ticket\Category $category
     * @return null|object
     */
    public function getDepartmentByCategoryId(Category $category): ?object
    {
        return $this->find()->where(['TICKET_DEPARTAMENTO' => $category->TICKET_DEPARTAMENTO])->first();
    }

    public function getDepartment(string $name): ?object
    {
        return $this->find()->where(['NOME' => $name])->first();
    }
}
