<?php 
namespace App\Security;

use App\Entity\Articulo;
use App\Entity\Usuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticuloVoter extends Voter
{
    // these strings are just invented: you can use anything
    const EDIT = 'edit';
    const DELETE = 'delete';

    //attribute = ROLE_USER or edit
    //subject = null, Object
    // This voter should return true if the attribute is
    //some of the const (edit) and if the object is a Articulo instance.
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, 
        [
            self::EDIT,
            self::DELETE
        ])) {
            return false;
        }

        // only vote on `Articulo` objects
        if (!$subject instanceof Articulo) {
            return false;
        }

        return true;
    }
    //If supports() return true, then this method is called.
    //Should return true to allow access and false to deny access.
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Usuario) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Articulo object, thanks to `supports()`
        /** @var Articulo $articulo */
        $articulo = $subject;

        switch ($attribute) {


            case self::EDIT:
                return $this->canEdit($articulo, $user);   

            case self::DELETE:
                return $this->canDelete($articulo, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Articulo $articulo, Usuario $usuario)
    {
        // this assumes that the Articulo object has a `getAutor()` method
        return $usuario === $articulo->getAutor();
    }

    private function canDelete(Articulo $articulo, Usuario $usuario)
    {
        // this assumes that the Articulo object has a `getAutor()` method
        return $usuario === $articulo->getAutor();
    }
}