<?php

namespace App\Models\Entity;

use App\Layer\Layer;

class User extends Layer
{
    /**
     * Table Name in Database.
     *
     * @var string
    */
    protected $table = 'Framework_Users';

    /**
     * Primary Key in table.
     *
     * @var string
    */
    protected $prefix = 'Framework_User';

    /**
     * @param int $id
     * @return null|object
    */
    public function getUserById(int $id): ?object
    {
        return $this->findBy($id)->first();
    }

    /**
     * @param string $username
     * @return null|object
    */
    public function getUserByUsername(string $username): ?object
    {
        return $this->find()->where(['Username' => $username])->first();
    }

    /**
     * @return null|array
    */
    public function getAllUser(): ?array
    {
        return $this->find('Framework_Sectors.Name Sector, Framework_Users.*')
        ->join('Framework_Sectors', 'Framework_Sectors.Framework_Sector', '=', 'Framework_Users.Framework_Sector')
        ->orderBy('Framework_Sector')
        ->all();
    }

    /**
     * @param array $data
     * @return bool
    */
    public function createUserByData(array $data): bool
    {
        $user = (new User());
        $user->Name = mb_convert_case($data['name'], MB_CASE_TITLE, 'UTF-8');
        $user->Username = mb_strtolower($data['username']);
        $user->Password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->Email = $data['email'];
        $user->Framework_Sector = (int) $data['sector'];

        return $user->save();
    }

    /**
     * @param int $id
     * @param string $password
     * @return bool
    */
    public function updatePasswordByUserId(int $id, string $password): bool
    {
        $user = $this->findBy($id)->first();
        $user->Framework_User = $id;
        $user->Password = password_hash($password, PASSWORD_DEFAULT);
        $user->Pending_Password = 'S';
        return $user->save();
    }


    /**
     * @param array $data
     * @return bool
    */
    public function updateUserByData(array $data): bool
    {
        $user = (new User())->findBy($data['user_id'])->first();

        $user->Name = mb_convert_case($data['name'], MB_CASE_TITLE, 'UTF-8');
        $user->Email = $data['email'];
        $user->Framework_Sector = $data['sector'];

        if (!empty($data['password'])) {
            //$this->updatePasswordByUserId($data['user_id'], $data['password']);
            $user->Password = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $user->save();
    }
}
