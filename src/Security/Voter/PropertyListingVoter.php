<?php

namespace App\Security\Voter;

use App\AppUser\Entity\AppUser as AppUserEntity;
use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\Security\User\CurrentUserProvider;
use App\Security\User\TokenUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PropertyListingVoter extends Voter
{
    const VIEW = 'view:listing';
    const EDIT = 'update:listing';

    const DELETE = 'delete:listing';

    public function __construct(private readonly CurrentUserProvider $currentUserProvider)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof PropertyListingEntity) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->currentUserProvider->fromToken($token);

        return match($attribute) {
            self::VIEW => $this->canView($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(PropertyListingEntity $post, AppUserEntity $user): bool
    {
        return true;
        // if they can edit, they can view
/*        if ($this->canEdit($post, $user)) {
            return true;
        }

        return !$post->isPrivate();*/
    }

    private function canEdit(PropertyListingEntity $post, AppUserEntity $user): bool
    {
        if(!$this->isLoggedIn($user))
            return false;

        return $user === $post->getOwner();
    }

    private function canDelete(PropertyListingEntity $post, AppUserEntity $user): bool
    {
        // If they can edit they can delete
        if ($this->canEdit($post, $user)) {
            return true;
        }

        return false;
    }

    private function isLoggedIn($user): bool
    {
        if (!$user instanceof AppUserEntity) {
            // User must have created a profile
            return false;
        }

        return true;
    }
}