<?php

namespace App\Security\Voter;

use App\AppUser\Entity\AppUser;
use App\Security\User\CurrentUserProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AppUserVoter extends Voter
{
    const VIEW = 'view:profile';
    const EDIT = 'update:profile';


    public function __construct(private readonly CurrentUserProvider $currentUserProvider)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof AppUser) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->currentUserProvider->fromToken($token);

        return match($attribute) {
            self::VIEW => $this->canView($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(AppUser $profile, AppUser $user): bool
    {
        return true;
        // if they can edit, they can view
        /*        if ($this->canEdit($post, $user)) {
                    return true;
                }

                return !$post->isPrivate();*/
    }

    private function canEdit(AppUser $profile, AppUser $user): bool
    {
        if(!$this->isLoggedIn($user))
            return false;

        return $user->getId() === $profile->getId();
    }

    private function canDelete(AppUser $profile, AppUser $user): bool
    {
        // If they can edit they can delete
        if ($this->canEdit($profile, $user)) {
            return true;
        }

        return false;
    }

    private function isLoggedIn($user): bool
    {
        if (!$user instanceof AppUser) {
            // User must have created a profile
            return false;
        }

        return true;
    }
}