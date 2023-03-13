<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener {
    private UserPasswordHasherInterface $haser;

    public function __construct(UserPasswordHasherInterface $ph) {
        $this->haser = $ph;
    }
    
    public function prePersist(User $user) {
        $this->encodePassword($user);
    }

    public function preUpdate(User $user) {
        $this->encodePassword($user);
    }
    /**
     * Encode the password based on plain password.
     *
     * @param User $user
     * @return void
     */
    public function encodePassword(User $user) {
        if ($user->getPlainPassword() === null) {
            return;
        }
        $user->setPassword($this->haser->hashPassword($user , $user->getPlainPassword()));
    }
}