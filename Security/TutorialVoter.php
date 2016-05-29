<?php
namespace Discutea\DTutoBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Discutea\DTutoBundle\Entity\Tutorial;

/**
 * Tutorial Voter 
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
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


    /**
     * 
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }
    
    protected function supports($attribute, $tutorial)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CANREADTUTORIAL))) {
            return false;
        }

        // only vote on Forum objects inside this voter
        if (!$tutorial instanceof Tutorial) {
            return true;
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
     * Control if user's is autorized to Read turorial
     * 
     * @param Tutorial $tutorial
     * @param TokenInterface $token
     * @return boolean
     */
    public function canReadTutorial(Tutorial $tutorial, TokenInterface $token)
    {

        $contrib = $tutorial->getCurrent();

        /* rappel status contributions
         * 
         * 0 = InProgress ( contribution being written )
         * 1 = Rejected   ( Contribution rejected not validated 'Requires reason $rejected' )
         * 2 = Submitted  ( Contribution submitted and not verified by a moderator )
         * 3 = Validated  ( Contribution submitted and approved by a moderator visible by all )
         * 
         */
        
        if ($contrib->getStatus() === 3) {
            return true;
        }

        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }

        if ($contrib->getContributor() === $token->getUser()) {
            return true;
        }

        return false;
    }

}
