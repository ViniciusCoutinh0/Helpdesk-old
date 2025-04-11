<?php

namespace App\Http\Controllers;

use App\Models\Ticket\Departament;
use App\Models\Ticket\SubCategory;
use App\Models\Entity\Seller;

class HttpController
{
    public function category(): void
    {
        $stream = $this->stream();

        if ($stream === null) {
            echo json_encode([
                'message' => 'Falha na requisição enviada, por favor tente novamente.',
                'result' => false
            ]);
            return;
        }

        $word = trim(strip_tags($stream->words));

        if (mb_strlen($word) <= 2) {
            echo json_encode([
                'message' => 'Digite 3 ou mais caracteres para continuar a busca.',
                'result'  => false
            ]);
            return;
        }

        $departament = new Departament();
        $items = $departament->likeByWords($word);

        if (!$items) {
            echo json_encode([
                'message' => 'Pesquise o Assunto do chamado ou entre em contato com T.i, para cadastrar assunto não encontrado',
                'result' => false
            ]);
            return;
        }

        $list = [];
        foreach ($items as $item) {
            if ($item->ATIVO == 'S') {
                $list['items'][] = [
                    'departament' => mb_convert_case($item->DEPARTAMENTO_NOME, MB_CASE_TITLE, 'UTF-8'),
                    'category_name' => mb_convert_case($item->NOME, MB_CASE_TITLE, 'UTF-8'),
                    'category_description' => mb_convert_case($item->DESCRICAO, MB_CASE_TITLE, 'UTF-8'),
                    'sub_category' => (int) $item->TICKET_SUB_CATEGORIA
                ];
            }
        }

        $list['result'] = true;
        echo json_encode($list);
    }

    public function fields(): void
    {
        $stream = $this->stream();

        if ($stream === null) {
            echo json_encode([
                'message' => 'Falha na requisição enviada, por favor tente novamente.',
                'result' => false
            ]);
            return;
        }

        $subcategory = new SubCategory();
        $fields = $subcategory->fieldsById((int) $stream->id);

        if (!$fields) {
            echo json_encode([
                'message' => 'Nenhum campo personalizado cadastrado para essa categoria.',
                'result' => false
            ]);
            return;
        }

        $list = [];
        foreach ($fields as $field) {
            if ($field->ATIVO === 'S') {
                $list['fields'][] = [
                    'field_name'        => str_replace(' ', '_', mb_strtolower($field->NOME)),
                    'field_description' => mb_convert_case($field->DESCRICAO_CAMPO, MB_CASE_TITLE, 'UTF-8'),
                    'field_required'    => ($field->REQUERIDO == 'S' ? true : false),
                    'category_description' => mb_convert_case($field->DESCRICAO, MB_CASE_TITLE, 'UTF-8'),
                    'sub_category'      => (int) $field->TICKET_SUB_CATEGORIA
                ];
            }
        }

        $list['result'] = true;
        echo json_encode($list);
    }


    public function entity(): void
    {
        $stream = $this->stream();

        if ($stream === null) {
            echo json_encode([
                'message' => 'Falha na requisição enviada, por favor tente novamente.',
                'result' => false
            ]);
            return;
        }

        $entity = $stream->entity;

        if (empty($entity)) {
            echo json_encode([
                'message' => 'Digite número do balconista.',
                'result' => false
            ]);
            return;
        }

        $seller = new Seller();
        $find = $seller->sellerByNumber((int) $entity);

        if (!$find) {
            echo json_encode([
                'message' => 'Nenhum vendedor encontrado.',
                'result' => false
            ]);
            return;
        }

        echo json_encode([
            'entity'    => (int) $find->VENDEDOR,
            'name'      => mb_convert_case(strip_tags($find->NOME), MB_CASE_TITLE, 'UTF-8'),
            'store'     => (int) $find->EMPRESA,
            'result'    => true
        ]);
    }

    private function stream(): ?object
    {
        $stream = file_get_contents('php://input');

        if ($stream === false) {
            return null;
        }

        return json_decode($stream);
    }
}
