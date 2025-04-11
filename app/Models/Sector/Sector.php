<?php

namespace App\Models\Sector;

use App\Layer\Layer;
use App\Models\Entity\User;
use App\Models\Rules\Rules;

class Sector extends Layer
{
    /**
     * Table Name in Database.
     *
     * @var string
     */
    protected $table = 'Framework_Sectors';

    /**
     * Primary Key in table.
     *
     * @var string
     */
    protected $prefix = 'Framework_Sector';


    public function getSectorByUser(User $user): ?object
    {
        return $this
            ->find('Framework_Sectors.*, TICKETS_DEPARTAMENTOS.LIBERAR_ABERTURA')
            ->left('TICKETS_DEPARTAMENTOS', 'TICKETS_DEPARTAMENTOS.NOME', '=', 'Framework_Sectors.Name')
            ->where(['Framework_Sectors.Framework_Sector' => $user->Framework_Sector])
            ->first();
    }

    public function getSectorById(int $id): ?object
    {
        return $this->findBy($id)->first();
    }

    public function getAllSectors(): ?array
    {
        return $this->find()->all();
    }

    public function getAllSectorsAndUser(): ?array
    {
        return $this->find('Framework_Users.Framework_User, Framework_Users.Username, Framework_Sectors.Name Sector, Framework_Sectors.Framework_Sector')
            ->join('Framework_Users', 'Framework_Users.Framework_Sector', '=', 'Framework_Sectors.Framework_Sector')
            ->orderBy('Framework_Users.Framework_User')
            ->all();
    }

    public function store(array $data)
    {
        $rules = (new Rules());
        $rules->Rule_Create = $data['rule_create'];
        $rules->Rule_Read = $data['rule_read'];
        $rules->Rule_Update = $data['rule_update'];
        $rules->Rule_Delete = $data['rule_delete'];

        $rules->save();
        $ruleId = \App\Layer\Instance\Db::getInstance()->lastInsertId();

        $sector = (new Sector());
        $sector->Name = $data['name'];
        $sector->Framework_Rule = (int) $ruleId;

        return $sector->save();
    }

    public function updateByParam(array $data): bool
    {
        $sector = (new Sector())->findBy($data['id'])->first();
        $sector->Framework_Sector = $data['id'];
        $sector->Name = $data['name'];
        $sector->save();

        $rule = (new Rules())->getRulesBySector($sector);
        $rule->Framework_Rule = $sector->Framework_Rule;
        $rule->Rule_Read = $data['rule_read'];
        $rule->Rule_Create = $data['rule_create'];
        $rule->Rule_Update = $data['rule_update'];
        $rule->Rule_Delete = $data['rule_delete'];

        return $rule->save();
    }
}
