<?php

namespace Serz\Framework\Model;


use Serz\Framework\Security\Request;
use Serz\Framework\Security\UserContract;

class User extends Model implements UserContract
{
    public $table = "users";

    public function isGuest(): bool
    {
        return !(bool)$this->id;
    }

    public function getRoles(): array
    {
        $roles = explode(',', $this->roles);
        return (array)$roles;
    }

    public function checkCredentials(Request $request): bool
    {
        return (($this->login === $request->login) && (md5($request->password) === $this->password));
    }
}