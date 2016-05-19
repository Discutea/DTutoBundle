<?php
namespace Discutea\DTutoBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\RequestStack;
use Discutea\DTutoBundle\Entity\Tutorial;

/**
 * Tutorial Voter 
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class TutorialVoter extends Voter
{
    
    const CANREADTUTORIAL = 'CanReadTutorial';
    
    /**
     *
     * @var object Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface
     * 
     */
    private $decisionManager;
    
    private $request;

    /**
     * 
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, RequestStack $request)
    {
        $this->decisionManager = $decisionManager;
        $this->request = $request->getCurrentRequest();
    }
    
    protected function supports($attribute, $tutorial)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CANREADTUTORIAL))) {
            return false;
        }

        // only vote on Forum objects inside this voter
        if (!$tutorial instanceof Tutorial) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $tutorial, TokenInterface $token)
    {

        switch($attribute) {
            case self::CANREADTUTORIAL:
                return $this->canReadTutorial($tutorial, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * 
     * Control if user's is autorized to Read forum
     * 
     * @param Forum $tutorial
     * @param TokenInterface $token
     * @return boolean
     */
    public function canReadTutorial(Tutorial $tutorial, TokenInterface $token)
    {

        $locale = $this->request->getLocale();
        $contrib = $tutorial->getCurrent();

        if ( ($contrib->getStatus() === 1) && ($locale == $contrib->getLocale()) ) {
            
            return true;
            
        } else {
            
            if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
                return true;
            }

            if ($contrib->getAuthor() === $token->getUser()) {
                return true;
            }

        }

        return false;
    }

}
