<?php

namespace App\Models\Admin;

use App\Models\Rules\Rules;
use App\Models\Sector\Sector;
use App\Models\Ticket\Ticket;
use App\Models\User\User;
use App\Traits\Verify;

class Admin extends User
{
    use Verify;

    protected function getTicketsByPeriod(string $min, string $max)
    {
        return (new Ticket())
        ->find('USUARIOS.NOME AS PROCFIT_USUARIO, TICKETS_CHAMADOS.*')
        ->join([
            [
                'JOIN' => 'INNER JOIN',
                'TABLE' => 'USUARIOS',
                'ON' => 'USUARIOS.USUARIO = TICKETS_CHAMADOS.RESPONSAVEL_ARTIA'
            ]
        ])
        ->operator('WHERE')
        ->between('CONVERT(DATE, INICIALIZACAO)', [$min, $max])
        ->fetch(true);
    }

    /**
     * Lista todos os usuários cadastrados no Banco.
     *
     * @return array|null
     */
    protected function listAllUsers(): ?array
    {
        return (new User())
        ->find()
        ->orderBy('Framework_Sector', 'ASC')
        ->fetch(true);
    }

    /**
     * Retorna todos os setores cadastrados no Banco.
     *
     * @return array|null
     */
    protected function listAllSectors(): ?array
    {
        return (new Sector())
        ->find()
        ->orderBy('Framework_Sector', 'ASC')
        ->fetch(true);
    }

    /**
     * Busca as regras pelo id do setor.
     *
     * @param integer $sectorId
     * @return object|null
     */
    protected function getSectorJoinRules(int $sectorId): ?object
    {
        return (new Sector())->find()
        ->join([
            [
                'JOIN' => 'INNER JOIN',
                'TABLE' => 'Framework_Rules',
                'ON' => 'Framework_Rules.Framework_Rule = Framework_Sectors.Framework_Rule'
            ]
        ])
        ->where([
            'Framework_Sectors.Framework_Sector' => intval($sectorId)
        ])
        ->fetch();
    }
    /**
     * Registra um usuário no Banco.
     *
     * @param array $fields
     * @return null|bool
     */
    protected function registerUser(array $fields): ?bool
    {
        if ($this->findEmail($fields['email'], (new User()))) {
            return null;
        }

        if ($this->findUsername($fields['username'], (new User()))) {
            return null;
        }

        $register = (new User());
        $register->Username = mb_strtolower(trim($fields['username']));
        $register->Password = password_hash(trim($fields['password']), PASSWORD_DEFAULT);
        $register->Name = mb_convert_case(trim($fields['name']), MB_CASE_TITLE, 'UTF-8');
        $register->Email = trim($fields['email']);
        $register->Framework_Sector = trim($fields['sector']);

        return ($register->save() ? true : null);
    }

    /**
     * Altera as informações básicas do usuário, Envia um Email caso altere a senha.
     *
     * @param array $fields
     * @param App\Models\User\User $user
     * @return null|bool
     */
    protected function updateUser(array $fields, User $user): ?bool
    {
        $find = $user->findBy($fields['user_id'])->fetch();

        if (!$find) {
            return null;
        }

        $find->Framework_User = $fields['user_id'];
        $find->Name = mb_convert_case(trim($fields['name']), MB_CASE_TITLE, 'UTF-8');
        $find->Username = mb_strtolower(trim($fields['username']));
        $find->Email = trim($fields['email']);
        $find->Framework_Sector = trim($fields['sector']);
        $find->State = trim($fields['state']);
        $find->Updated_at = dateFormat()->format('Y-m-d H:i:s');

        if (isset($fields['password'])) {
            $find->Password = password_hash($fields['password'], PASSWORD_DEFAULT);
            $find->Pending_Password = 'S';
        }

        return ($find->save() ? true : null);
    }

    /**
     * Cadastra um setor no banco.
     *
     * @param array $fields
     * @return null|bool
     */
    protected function registrySector(array $fields): ?bool
    {
        if ($this->findSector($fields['sector'], (new Sector()))) {
            return null;
        }

        $rule = new Rules();
        $rule->Rule_Create  = $fields['create'];
        $rule->Rule_Read    = $fields['read'];
        $rule->Rule_Update  = $fields['update'];
        $rule->Rule_Delete  = $fields['delete'];

        if ($rule->save()) {
            $sector = new Sector();
            $sector->Name = $fields['sector'];
            $sector->Framework_Rule = (int) $this->lastId();
            return ($sector->save() ? true : null);
        }
    }

    /**
     * Altera as informações do setor.
     *
     * @param array $fields
     * @param App\Models\Sector\Sector $sector
     * @param App\Models\Rules\Rules $rules
     * @return null|bool
     */
    protected function updateSector(array $fields, Sector $sector, Rules $rules): ?bool
    {
        $findSector = $sector->findBy($fields['sector_id'])->fetch();

        if (!$findSector) {
            return null;
        }

        $findRules = $rules->findBy((int) $findSector->Framework_Rule)->fetch();

        if (!$findRules) {
            return null;
        }

        $findSector->Framework_Sector = $fields['sector_id'];
        $findSector->Name = trim($fields['sector']);
        $findSector->Updated_at = dateFormat()->format('Y-m-d H:i:s');

        if ($findSector->save()) {
            $findRules->Framework_Rule   = (int) $findSector->Framework_Rule;
            $findRules->Rule_Create      = $fields['create'];
            $findRules->Rule_Read        = $fields['read'];
            $findRules->Rule_Update      = $fields['update'];
            $findRules->Rule_Delete      = $fields['delete'];

            return ($findRules->save() ? true : null);
        }
    }

    private function findUsername(string $username, User $user): bool
    {
        $findByUser = $user->find()->where(['Username' => $username])->count();

        if ($findByUser) {
            return true;
        }
        return false;
    }

    private function findEmail(string $email, User $user): bool
    {
        $findByEmail = $user->find()->where(['Email' => $email])->count();

        if ($findByEmail) {
            return true;
        }
        return false;
    }

    private function findSector(string $name, Sector $sector): bool
    {
        $findByName = $sector->find()->where(['Name' => $name])->count();

        if ($findByName) {
            return true;
        }
        return false;
    }
}
