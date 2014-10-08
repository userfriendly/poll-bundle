<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Userfriendly\Bundle\PollBundle\Entity\PollAnswer;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__question")
 */
class PollQuestion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $questionText;

    /** @ORM\Column(type="boolean") */
    protected $multiple;

    /** @ORM\OneToMany(targetEntity="PollAnswer", mappedBy="pollQuestion", cascade={"persist", "remove", "merge"}, orphanRemoval=true) */
    protected $answers;

    /**
     * Constructor
     */
    public function __construct( $text = "" )
    {
        $this->questionText = $text;
        $this->multiple = false;
        $this->answers = new ArrayCollection();
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
     * Set questionText
     *
     * @param string $questionText
     * @return PollQuestion
     */
    public function setQuestionText( $questionText )
    {
        $this->questionText = $questionText;
        return $this;
    }

    /**
     * Get questionText
     *
     * @return string
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }

    /**
     * Set multiple
     *
     * @param boolean $multiple
     * @return PollQuestion
     */
    public function setMultiple( $multiple )
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * Get multiple
     *
     * @return boolean
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param PollAnswer
     * @return PollQuestion
     */
    public function addAnswer( PollAnswer $answer )
    {
        $answer->setPollQuestion( $this );
        $this->answers[] = $answer;
        return $this;
    }

    /**
     * Get pollAnswer objects
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}