<?php

namespace Userfriendly\Bundle\PollBundle\Poll;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Userfriendly\Bundle\PollBundle\Entity\Answer;
use Userfriendly\Bundle\PollBundle\Entity\Participation;
use Userfriendly\Bundle\PollBundle\Entity\Poll;
use Userfriendly\Bundle\PollBundle\Entity\Tally;
use Userfriendly\Bundle\PollBundle\Entity\AnonymousParticipationAttempt;
use Userfriendly\Bundle\PollBundle\Entity\AnonymousParticipationLimit;
use Userfriendly\Bundle\PollBundle\Model\UserInterface;

/**
 * Voting
 *
 * Service that deals with counting votes
 */
class Voting
{
    protected $objectManager;
    protected $configuration;

    /**
     * Constructor
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param array $configuration
     */
    public function __construct( ObjectManager $objectManager, array $configuration = array() )
    {
        $this->objectManager = $objectManager;
        $this->configuration = $configuration;
    }

    /**
     * Check if user can vote in a poll
     *
     * @param Poll $poll
     * @param UserInterface $user
     * @param array $previousAnswers
     *
     * @return boolean
     */
    public function allowedToVote(
                        Poll $poll,
                        UserInterface $user = null,
                        array $previousAnswers = array() )
    {
        $alreadyVoted = false;
        if ( null === $user ) // Check if anonymous voting is allowed
        {
            if ( !$this->configuration['anonymous_polling'] ) return false;
        }
        if ( count( $previousAnswers ) > 0 ) // Check if user voted previously in this poll
        {
            if ( !$this->configuration['allow_changing_vote'] ) // Check if changing one's vote is allowed
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Handle a voting attempt
     *
     * @param Poll $poll
     * @param UserInterface $user
     * @param array $previousAnswers
     * @param array $newAnswers
     * @param string $ipAddress
     *
     * @return boolean
     */
    public function examineVoteAttempt(
                        Poll $poll,
                        UserInterface $user = null,
                        array $previousAnswers = array(),
                        array $newAnswers = array(),
                        $ipAddress = "" )
    {
        ///////////////////////////////
        // Handle registered polling //
        ///////////////////////////////
        if ( $user && $this->registeredPolling() )
        {
            $participationRepo = $this->objectManager->getRepository( 'UserfriendlyPollBundle:Participation' );
            $participations = $participationRepo->findBy( array( 'user' => $user, 'poll' => $poll ));
            foreach ( $participations as $participation )
            {
                $this->tally( $poll, $participation->getAnswer(), -1 );
                $this->objectManager->remove( $participation );
            }
            foreach ( $newAnswers as $answer )
            {
                $participation = new Participation();
                $participation->setUser( $user );
                $participation->setPoll( $poll );
                $participation->setAnswer( $answer );
                $this->objectManager->persist( $participation );
                $this->tally( $poll, $answer );
            }
            $this->objectManager->flush();
            return true;
        }
        //////////////////////////////
        // Handle anonymous polling //
        //////////////////////////////
        // http://blog.thelonepole.com/2013/03/preventing-spam-votes-in-online-polls
        $limiter = $this->trackAttempt( $poll, $ipAddress );
        // Exceeds current threshold for IP/poll?
        // NO: count vote
        // YES:
            // ...
        /////////////////////////////////
        /////////////////////////////////
        /////////////////////////////////
        /////////////////////////////////
        /////////////////////////////////
        // Timeout expired or NULL?
        // NO:
            // return false
        // YES:
            // Grace period expired or NULL?
            // NO:
                // Threshold exceeded?
                // NO:
                    // Count vote
                    // ...
                // YES:
                    // Reset & double timeout, set new grace period to same length
                    // ...
            // YES:
                // ...
    }

    /**
     * @param \Userfriendly\Bundle\PollBundle\Entity\AnonymousParticipationLimit $limiter
     *
     * @return boolean
     */
    protected function isThresholdExceeded( AnonymousParticipationLimit $limiter )
    {
        $attemptRepo = $this->objectManager->getRepository( 'UserfriendlyPollBundle:AnonymousParticipationAttempt' );
        //$limiter->getIpAddress()
        // TODO
        // sum vote attempts from IP address over current threshold timespan
        // compare with current threshold values
        return false;
    }

    /**
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\Poll $poll
     * @param \Userfriendly\Bundle\PollBundle\Entity\Answer $answer
     * @param integer $increase Either +1 or -1
     */
    protected function tally( Poll $poll, Answer $answer, $increase = 1 )
    {
        if ( $increase != 1 && $increase != -1 ) return;
        $tally = $this->objectManager
                      ->getRepository( 'UserfriendlyPollBundle:Tally' )
                      ->findOneBy( array( 'poll' => $poll, 'answer' => $answer ));
        if ( null === $tally )
        {
            if ( $increase == -1 ) return;
            $tally = new Tally();
            $tally->setPoll( $poll );
            $tally->setAnswer( $answer );
            $this->objectManager->persist( $tally );
        }
        $tally->setCount( $tally->getCount() + $increase );
        if ( $tally->getCount() < 1 ) $this->objectManager->remove( $tally );
    }

    /**
     * Track time of attempt to vote from this IP address for this poll
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\Poll $poll
     * @param string $ipAddress
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\AnonymousParticipationAttempt
     */
    protected function trackAttempt( Poll $poll, $ipAddress = "" )
    {
        $voteAttempt = new AnonymousParticipationAttempt();
        $voteAttempt->setPoll( $poll );
        $voteAttempt->setIpAddress( $ipAddress );
        $this->objectManager->persist( $voteAttempt );
        // Check if we have a limiter entry for this IP address & this poll
        $limiterRepo = $this->objectManager->getRepository( 'UserfriendlyPollBundle:AnonymousParticipationLimit' );
        $limiter = $limiterRepo->findOneBy( array( 'poll' => $poll, 'ipAddress' => $ipAddress ));
        if ( null === $limiter )
        {
            $limiter = new AnonymousParticipationLimit();
            $limiter->setPoll( $poll );
            $limiter->setIpAddress( $ipAddress );
            $limiter->setCurrentThresholdTimespan( $this->configuration['threshold']['timespan'] );
            $limiter->setCurrentAllowedVotesThreshold( $this->configuration['threshold']['votes_allowed'] );
            $limiter->setCurrentTimeout( $this->configuration['threshold']['timeout'] );
            $limiter->setCurrentGracePeriod( $this->configuration['threshold']['grace_period'] );
            $this->objectManager->persist( $limiter );
        }
        $limiter->setLastVoteAttemptAt( new \DateTime( "now" ));
        $this->objectManager->flush();
        return $limiter;
    }

    /**
     * Check if anonymous polling is enabled
     *
     * @return boolean
     */
    public function anonymousPolling()
    {
        return $this->configuration['anonymous_polling'];
    }

    /**
     * Check if registered polling is enabled
     *
     * @return boolean
     */
    public function registeredPolling()
    {
        return $this->configuration['registered_polling'];
    }
}
