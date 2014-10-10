<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Userfriendly\Bundle\PollBundle\Entity\Question;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Userfriendly\Bundle\PollBundle\Entity\Repository\PollRepository")
 * @ORM\Table(name="uf_poll__poll")
 */
class Poll
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO") */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Question") @ORM\JoinColumn(name="question_id", referencedColumnName="id") */
    protected $question;

    /** @ORM\OneToMany(targetEntity="Answer", mappedBy="poll", cascade={"persist", "remove", "merge"}, orphanRemoval=true) */
    protected $answers;

    /** @ORM\Column(name="closed_at", type="datetime") */
    protected $closedAt;

    /** @ORM\Column(name="created_at", type="datetime") @Gedmo\Timestampable(on="create") */
    protected $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /*'''''''''''''''''''''''*\
    |    Getters & setters    |
    \*_______________________*/

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
     * Set question
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\Question $question
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Poll
     */
    public function setQuestion( Question $question = null )
    {
        $this->question = $question;
        return $this;
    }

    /**
     * Get question
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param \Userfriendly\Bundle\PollBundle\Entity\Answer
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Poll
     */
    public function addAnswer( Answer $answer )
    {
        $answer->setPoll( $this );
        $this->answers[] = $answer;
        return $this;
    }

    /**
     * Get Answer objects
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @return \DateTime
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param \DateTime $closedAt
     *
     * @return \Userfriendly\Bundle\PollBundle\Entity\Poll
     */
    public function setClosedAt( \DateTime $closedAt )
    {
        $this->closedAt = $closedAt;
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

    /*'''''''''''''''''''*\
    |    Other methods    |
    \*___________________*/

    /**
     * Check if poll is already closed
     *
     * @return boolean
     */
    public function isClosed()
    {
        $now = new \DateTime( "now" );
        return $now > $this->closedAt;
    }

    /**
     * Check if the answers provided belong to this poll and return the respective entity objects
     *
     * @param array $answerIdsProvided
     *
     * @return array
     */
    public function verifyAnswers( array $answerIdsProvided = array() )
    {
        $verifiedAnswers = array();
        foreach ( $this->answers as $answer )
        {
            if ( in_array( $answer->getId(), $idProvided ) )
                $verifiedAnswers[] = $answer;
        }
        return $verifiedAnswers;
    }
}
