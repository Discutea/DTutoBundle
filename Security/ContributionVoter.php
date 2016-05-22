<?php
namespace Discutea\DTutoBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Discutea\DTutoBundle\Entity\Contribution;

/**
 * Contribution Voter 
 * 
 * @package  DTutoBundle
 * @author   David Verdier <contact@discutea.com>
 * https://www.linkedin.com/in/verdierdavid
 *
 */
class ContributionVoter extends Voter
{
    
    const CANEDITCONTRIBUTION = 'CanEditContribution';
    const CANREADCONTRIB      = 'CanReadContrib';
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
    
    protected function supports($attribute, $contribution)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CANEDITCONTRIBUTION, self::CANREADCONTRIB))) {
            return false;
        }

        // only vote on Contribution objects inside this voter
        if (!$contribution instanceof Contribution) {
            return true;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $contribution, TokenInterface $token)
    {

        switch($attribute) {
            case self::CANEDITCONTRIBUTION:
                return $this->canEditContribution($contribution, $token);
            case self::CANREADCONTRIB:
                return $this->canReadContrib($contribution, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * 
     * Control if user's is autorized to Read turorial
     * 
     * @param Tutorial $contribution
     * @param TokenInterface $token
     * @return boolean
     */
    public function canEditContribution(Contribution $contribution, TokenInterface $token)
    {
        
        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }

        /* rappel status contributions
         * 
         * 0 = InProgress ( contribution being written )
         * 1 = Rejected   ( Contribution rejected not validated 'Requires reason $rejected' )
         * 2 = Submitted  ( Contribution submitted and not verified by a moderator )
         * 3 = Validated  ( Contribution submitted and approved by a moderator visible by all )
         * 
         */

        if ( ($contribution->getStatus() !== 3) && ($contribution->getContributor() === $token->getUser()) ) {
            return true;
        } 

        return false;
    }

    public function canReadContrib(Contribution $contribution, TokenInterface $token)
    {
        /* rappel status contributions
         * 
         * 0 = InProgress ( contribution being written )
         * 1 = Rejected   ( Contribution rejected not validated 'Requires reason $rejected' )
         * 2 = Submitted  ( Contribution submitted and not verified by a moderator )
         * 3 = Validated  ( Contribution submitted and approved by a moderator visible by all )
         * 
         */
        
        if ($contribution->getStatus() === 3) {
            return true;
        }

        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }

        if ($contribution->getContributor() === $token->getUser()) {
            return true;
        }

        return false;
    }
}
