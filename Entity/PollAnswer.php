<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Userfriendly\Bundle\PollBundle\Entity\PollQuestion;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__answer")
 */
class PollAnswer
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO") */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="PollQuestion") @ORM\JoinColumn(name="pollquestion_id", referencedColumnName="id") */
    protected $pollQuestion;

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
     * @return \Userfriendly\Bundle\PollBundle\Entity\PollAnswer
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
     * Set pollQuestion
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\PollQuestion $pollQuestion
     * @return \Userfriendly\Bundle\PollBundle\Entity\PollAnswer
     */
    public function setPollQuestion( PollQuestion $pollQuestion = null )
    {
        $this->pollQuestion = $pollQuestion;
        return $this;
    }

    /**
     * Get pollQuestion
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\PollQuestion
     */
    public function getPollQuestion()
    {
        return $this->pollQuestion;
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