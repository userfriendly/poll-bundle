<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Userfriendly\Bundle\PollBundle\Model\UserInterface;
use Userfriendly\Bundle\PollBundle\Entity\Poll;
use Userfriendly\Bundle\PollBundle\Entity\Answer;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__participation")
 */
class Participation
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO") */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Userfriendly\Bundle\PollBundle\Model\UserInterface") @ORM\JoinColumn(name="user_id", referencedColumnName="id") */
    protected $user;

    /** @ORM\ManyToOne(targetEntity="Poll") @ORM\JoinColumn(name="poll_id", referencedColumnName="id") */
    protected $poll;

    /** @ORM\ManyToOne(targetEntity="Answer") @ORM\JoinColumn(name="answer_id", referencedColumnName="id") */
    protected $answer;

    /** @ORM\Column(name="created_at", type="datetime") @Gedmo\Timestampable(on="create") */
    protected $createdAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user
     *
     * @return \Userfriendly\Bundle\PollBundle\Model\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \Userfriendly\Bundle\PollBundle\Model\UserInterface $user
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Participation
     */
    public function setUser( User $user = null )
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get poll
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * Set poll
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\Poll $poll
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Participation
     */
    public function setPoll( Poll $poll = null )
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * Get answer
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set answer
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\Answer $answer
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Participation
     */
    public function setAnswer( Answer $answer = null )
    {
        $this->answer = $answer;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}