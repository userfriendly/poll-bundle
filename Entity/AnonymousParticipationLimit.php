<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Userfriendly\Bundle\PollBundle\Entity\Poll;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__anonymousparticipationlimit")
 */
class AnonymousParticipationLimit
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Poll") @ORM\JoinColumn(name="poll_id", referencedColumnName="id") */
    protected $poll;

    /** @ORM\Column(type="string", length=15) */
    protected $ipAddress;

    /** @ORM\Column(name="last_vote_at", type="datetime", nullable=true) */
    protected $lastVoteAttemptAt;

    /** @ORM\Column(name="threshold_exceeded_at", type="datetime", nullable=true) */
    protected $thresholdLastExceededAt;

    /** @ORM\Column(name="threshold_timespan", type="integer") */
    protected $currentThresholdTimespan; // in minutes

    /** @ORM\Column(name="threshold_votes", type="integer") */
    protected $currentAllowedVotesThreshold;

    /** @ORM\Column(name="timeout", type="integer") */
    protected $currentTimeout; // in minutes

    /** @ORM\Column(name="graceperiod", type="integer") */
    protected $currentGracePeriod; // in minutes

    /** @ORM\Column(name="created_at", type="datetime") @Gedmo\Timestampable(on="create") */
    protected $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currentThresholdTimespan = 1;
        $this->currentAllowedVotesThreshold = 1;
        $this->currentTimeout = 1;
        $this->currentGracePeriod = 1;
    }

    /*'''''''''''''''''''''''*\
    |    Getters & setters    |
    \*_______________________*/

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Userfriendly\Bundle\PollBundle\Entity\Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param \Userfriendly\Bundle\PollBundle\Entity\Poll $poll
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setPoll( Poll $poll )
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setIpAddress( $ipAddress = "" )
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCurrentThresholdTimespan()
    {
        return $this->currentThresholdTimespan;
    }

    /**
     * @param integer $currentThresholdTimespan
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setCurrentThresholdTimespan( $currentThresholdTimespan )
    {
        $this->currentThresholdTimespan = $currentThresholdTimespan;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCurrentAllowedVotesThreshold()
    {
        return $this->currentAllowedVotesThreshold;
    }

    /**
     * @param integer $currentAllowedVotesThreshold
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setCurrentAllowedVotesThreshold(
            $currentAllowedVotesThreshold )
    {
        $this->currentAllowedVotesThreshold = $currentAllowedVotesThreshold;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getThresholdLastExceededAt()
    {
        return $this->thresholdLastExceededAt;
    }

    /**
     * @param \DateTime $thresholdLastExceededAt
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setThresholdLastExceededAt( $thresholdLastExceededAt )
    {
        $this->thresholdLastExceededAt = $thresholdLastExceededAt;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCurrentTimeout()
    {
        return $this->currentTimeout;
    }

    /**
     * @param integer $currentTimeout
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setCurrentTimeout( $currentTimeout = 1 )
    {
        $this->currentTimeout = $currentTimeout;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCurrentGracePeriod()
    {
        return $this->currentGracePeriod;
    }

    /**
     * @param integer $currentGracePeriod
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setCurrentGracePeriod( $currentGracePeriod = 1 )
    {
        $this->currentGracePeriod = $currentGracePeriod;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastVoteAttemptAt()
    {
        return $this->lastVoteAttemptAt;
    }

    /**
     * @param \DateTime $lastVoteAttemptAt
     *
     * @return \Userfriendly\Bundle\PollBundle\AnonymousParticipationLimit
     */
    public function setLastVoteAttemptAt( \DateTime $lastVoteAttemptAt )
    {
        $this->lastVoteAttemptAt = $lastVoteAttemptAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /*'''''''''''''''''''*\
    |    Other methods    |
    \*___________________*/

    /**
     *
     */
    public function foo( $number )
    {
        // TODO
        return false;
    }
}
