<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Userfriendly\Bundle\PollBundle\Entity\Poll;
use Userfriendly\Bundle\PollBundle\Entity\Answer;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__tally")
 */
class Tally
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO") */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Poll") @ORM\JoinColumn(name="poll_id", referencedColumnName="id") */
    protected $poll;

    /** @ORM\OneToOne(targetEntity="Answer") @ORM\JoinColumn(name="answer_id", referencedColumnName="id") */
    protected $answer;

    /** @ORM\Column(type="integer") */
    protected $count;

    /** @ORM\Column(name="updated_at", type="datetime") @Gedmo\Timestampable(on="update") */
    protected $updatedAt;

    /** @ORM\Column(name="created_at", type="datetime") @Gedmo\Timestampable(on="create") */
    protected $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->count = 0;
    }

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
     * @return \Userfriendly\Bundle\PollBundle\Entity\Tally
     */
    public function setPoll( Poll $poll = null )
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * @return \Userfriendly\Bundle\PollBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param \Userfriendly\Bundle\PollBundle\Entity\Answer $answer
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Tally
     */
    public function setAnswer( $answer )
    {
        $this->answer = $answer;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param integer $count
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Tally
     */
    public function setCount( $count )
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}
