<?php

namespace App\Security\Voter;

use App\Entity\Products;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProductsVoter extends Voter
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    protected function supports(string $attribute, $product): bool {

if (!in_array($attribute, [self::EDIT, self::DELETE])) {
return false;
    }
    if (!$product instanceof Products) {
        return false;
    }

    return true;

}



    protected function voteOnAttribute($attribute, $product, TokenInterface $token): bool
    {
        //On récupère l'utilisateur à partir du token
        $user = $token->getUser();

        if (!$user instanceof UserInterface) return false;
        

    //On vérifie si l'utilisateur est admin
    if ($this->security->isGranted('ROLE_ADMIN')) return true;

    // On vérifie les permissions
    switch ($attribute) {
        case self::EDIT:
            // on vérifie si l'uilisateur peut éditer
            return $this->canEdit();
            break;
        case self::DELETE:
            // on vérifie si l'utilisateur peut supprimer
            return $this->canDelete();
            break;
    }
}
    


private function canEdit() {
    return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
}


private function canDelete() {
    return $this->security->isGranted('ROLE_PRODUCT_ ADMIN');
}
    


}


