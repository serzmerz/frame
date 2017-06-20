<?php

namespace Serz\Framework\Security;


use Serz\Framework\DI\Conteiner;
use Serz\Framework\DI\Injector;
use Serz\Framework\Model\User;

/**
 * Class Security
 * @package Serz\Framework\Security
 */
class Security
{
    /**
     * @var mixed
     */
    protected $session;

    /**
     * @var null
     */
    protected static $user = null;

    /**
     * Security constructor.
     * @param $session
     */
    public function __construct()
    {
        $this->session = Injector::make('Serz\Framework\Session\Session');
        $this->getUser();
    }

    /**
     * @return UserContract
     */
    public function getUser(): UserContract
    {
        if (empty(self::$user)) {
            $user_id = $this->session->user_id;
            $user = Injector::make(Conteiner::get("config")["auth"]["UserContract"]);
            if ($user_id) {
                $user = $user->find($user_id);
            }
            self::$user = $user;
        }
        return self::$user;
    }

    /**
     * @param UserContract $user
     */
    public function authorize(UserContract $user)
    {
        $this->session->user_id = $user->id;
        $this->session->role = $user->getRoles();
        $this->getUser();
    }

}