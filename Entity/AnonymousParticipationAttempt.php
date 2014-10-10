<?php

namespace Userfriendly\Bundle\PollBundle\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Userfriendly\Bundle\PollBundle\Entity\Poll;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__anonymousparticipationattempt")
 */
class AnonymousParticipationAttempt
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Poll") @ORM\JoinColumn(name="poll_id", referencedColumnName="id") */
    protected $poll;

    /** @ORM\Column(type="string", length=15, nullable=true) */
    protected $ipAddress;

    /** @ORM\Column(name="created_at", type="datetime") @Gedmo\Timestampable(on="create") */
    protected $createdAt;

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
     * @return \Userfriendly\Bundle\PollBundle\Entity\AnonymousParticipationAttempt
     */
    public function setPoll( Poll $poll = null )
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
     * @return \Userfriendly\Bundle\PollBundle\Entity\AnonymousParticipationAttempt
     */
    public function setIpAddress( $ipAddress = "" )
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
