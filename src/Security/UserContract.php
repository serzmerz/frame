<?php

namespace Serz\Framework\Security;


interface UserContract
{
    /**
     * Check user guest or no
     *
     * @return bool
     */
    public function isGuest(): bool;

    /**
     * Get user roles
     *
     * @return array
     */
    public function getRoles(): array;

    /**
     * Try to check if can be authorized with provided request data
     *
     * @param Request $request
     * @return bool
     */
    public function checkCredentials(Request $request): bool;
}