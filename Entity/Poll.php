<?php

namespace Userfriendly\Bundle\PollBundle\Entity;

use Userfriendly\Bundle\PollBundle\Entity\PollQuestion;
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

    /** @ORM\OneToOne(targetEntity="PollQuestion",cascade={"persist"}) @ORM\JoinColumn(name="pollquestion_id", referencedColumnName="id") */
    protected $pollQuestion;

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
     * Set pollQuestion
     *
     * @param \Userfriendly\Bundle\PollBundle\Entity\PollQuestion $pollQuestion
     * @return \Userfriendly\Bundle\PollBundle\Entity\Poll
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return \Userfriendly\Bundle\PollBundle\Entity\Poll
     */
    public function setCreatedAt( $createdAt )
    {
        $this->createdAt = $createdAt;
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