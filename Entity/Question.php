<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Userfriendly\Bundle\PollBundle\Entity\Answer;

/**
 * @ORM\Entity
 * @ORM\Table(name="uf_poll__question")
 */
class Question
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

    /**
     * Constructor
     */
    public function __construct( $text = "" )
    {
        $this->questionText = $text;
        $this->multiple = false;
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
     * @return \Userfriendly\Bundle\PollBundle\Entity\Question
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
     * @return \Userfriendly\Bundle\PollBundle\Entity\Question
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
}