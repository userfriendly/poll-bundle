<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Userfriendly\Bundle\PollBundle\Entity\Poll;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__answer")
 */
class Answer
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO") */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Poll") @ORM\JoinColumn(name="poll_id", referencedColumnName="id") */
    protected $poll;

    /** @ORM\Column(type="string") */
    protected $answerText;

    /**
     * Constructor
     */
    public function __construct( $text = "" )
    {
        $this->answerText = $text;
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
     * Set answerText
     *
     * @param string $answerText
     * @return \Userfriendly\Bundle\PollBundle\Entity\Answer
     */
    public function setAnswerText( $answerText )
    {
        $this->answerText = $answerText;
        return $this;
    }

    /**
     * Get answerText
     *
     * @return string
     */
    public function getAnswerText()
    {
        return $this->answerText;
    }

    /**
     * Set poll
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\Poll $poll
     * @return \Userfriendly\Bundle\PollBundle\Entity\Answer
     */
    public function setPoll( Poll $poll = null )
    {
        $this->poll = $poll;
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
     * String representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->answerText;
    }
}