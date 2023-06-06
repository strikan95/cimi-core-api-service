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
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
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

        if (!$user instanceof AppUserEntity) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var PropertyListingEntity $post */
        $propertyListing = $subject;

        return match($attribute) {
            self::VIEW => $this->canView($propertyListing, $user),
            self::DELETE => $this->canDelete($propertyListing, $user),
            self::EDIT => $this->canEdit($propertyListing, $user),
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

    private function canDelete(PropertyListingEntity $post, AppUserEntity $user): bool
    {
        if ($this->canEdit($post, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit(PropertyListingEntity $post, AppUserEntity $user): bool
    {
        return $user === $post->getOwner();
    }
}