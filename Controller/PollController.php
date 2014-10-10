<?php

namespace Userfriendly\Bundle\PollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Userfriendly\Bundle\PollBundle\Entity\Poll;
use Userfriendly\Bundle\PollBundle\Entity\Answer;
use Userfriendly\Bundle\PollBundle\Entity\Question;
use Userfriendly\Bundle\PollBundle\Model\UserInterface;

class PollController extends Controller
{
    /**
     * Process poll form and redirect
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Exception
     */
    public function pollParticipationAction( Request $request )
    {
        $voting = $this->get( 'uf.poll.voting' );
        //////////////////////
        // Obtain user data //
        //////////////////////
        $ipAddress = $request->getClientIp();
        $cookieName = 'uf_poll_' . $pollIdProvided;
        $cookie = $request->cookies->has( $cookieName ) ? $request->cookies->get( $cookieName ) : array();
        $user = $this->getUser() instanceof UserInterface ? $this->getUser() : null;
        if ( $user || $voting->anonymousPolling() )
        {
            /////////////////////////////////////
            // Check the voting data submitted //
            /////////////////////////////////////
            $pollIdProvided = $request->get( 'poll_id' );
            $answerIdsProvided = $request->get( 'answer' );
            if ( !is_array( $answerIdsProvided ) || !$pollIdProvided ) throw new \Exception( 'invalid data' );
            ////////////////////////////////
            // Retrieve relevant entities //
            ////////////////////////////////
            $em = $this->getDoctrine()->getManager();
            $poll = $em->getRepository( 'UserfriendlyPollBundle:Poll' )->find( $pollIdProvided );
            if ( !$poll ) throw new \Exception( 'invalid poll' );
            if ( $poll->isClosed() ) throw new \Exception( 'this poll is closed' );
            $answers = $poll->verifyAnswers( $answerIdsProvided );
            $previousAnswers = $this->getPreviousAnswers( $request, $poll, $user );
            ///////////////////////////////
            // Handle the voting attempt //
            ///////////////////////////////
            if ( $voting->allowedToVote( $poll, $user, $previousAnswers ))
            {
                if ( $voting->examineVoteAttempt( $poll, $user, $previousAnswers, $answers, $ipAddress ))
                {
                    if ( !( $user && $voting->registeredPolling() ))
                    {
                        $verifiedAnswerIds = array();
                        foreach ( $answers as $answer ) $verifiedAnswerIds[] = $answer->getId();
                        $this->setPollCookie( $poll, $verifiedAnswerIds );
                    }
                }
            }
        }
        return $this->redirect( $request->headers->get( 'referer' ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sidebarAction( Request $request )
    {
        $repo = $this->getDoctrine()->getRepository( 'UserfriendlyPollBundle:Poll' );
        $polls = $repo->getLatestPolls( 2 );
        $user = $this->getUser() instanceof UserInterface ? $this->getUser() : null;
        $previousVoting = array();
        foreach ( $polls as $poll )
        {
            $answerIds = array();
            $answers = $this->getPreviousAnswers( $request, $poll, $user );
            foreach ( $answers as $answer ) $answerIds[] = $answer->getId();
            $previousVoting[$poll->getId()] = $answerIds;
        }
        return $this->render( 'UserfriendlyPollBundle:Poll:sidebar.html.twig', array(
            'polls' => $polls,
            'previousVoting' => $previousVoting,
        ));
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction( Request $request )
    {
        if ( $request->isMethod( 'post' ))
        {
            $answersArray = array();
            $tmp = $request->get( 'answer' );
            if ( is_array( $tmp ))
            {
                foreach ( $tmp as $answerString )
                {
                    if ( $answerString ) $answersArray[] = $answerString;
                }
            }
            $questionString = $request->get( 'question' );
            if ( $questionString && count( $answersArray ) > 0 )
            {
                $em = $this->getDoctrine()->getManager();
                $question = new Question( $questionString );
                if ( $request->get( 'multiple' )) $question->setMultiple( true );
                $em->persist( $question );
                $poll = new Poll();
                $poll->setQuestion( $question );
                foreach ( $answersArray as $answerString )
                {
                    $answer = new Answer( $answerString );
                    $poll->addAnswer( $answer );
                }
                $em->persist( $poll );
                $em->flush();
            }
            return $this->redirect( $this->generateUrl( 'create_poll_form' ));
        }
        return $this->render( 'UserfriendlyPollBundle:Poll:create.html.twig', array() );
    }

    /**
     * Read cookie for this poll if available
     *
     * @param Request $request
     * @param integer $pollId
     *
     * @return array
     */
    protected function getPollCookie( Request $request, Poll $poll )
    {
        $cookieName = 'uf_poll_' . $poll->getId();
        return $request->cookies->has( $cookieName ) ? $request->cookies->get( $cookieName ) : array();
    }

    /**
     * Set cookie for this poll
     *
     * @param Poll $poll
     * @param array $verifiedAnswerIds
     */
    protected function setPollCookie( Poll $poll, $verifiedAnswerIds )
    {
        $cookieName = 'uf_poll_' . $poll->getId();
        $cookieExpires = mktime( 1, 1, 1, 12, 30, 2037 );
        $cookie = new Cookie( $cookieName, $verifiedAnswerIds, $cookieExpires, '/' );
        $response = new Response();
        $response->headers->setCookie( $cookie );
        $response->send();
    }

    /**
     * Get answers *assumed* to have been given previously by this user
     *
     * @param Request $request
     * @param Poll $poll
     * @param UserInterface $user
     *
     * @return array
     */
    protected function getPreviousAnswers( Request $request, Poll $poll, UserInterface $user = null )
    {
        $answers = array();
        if ( $user && $this->get( 'uf.poll.voting' )->registeredPolling() ) // Look in database
        {
            $participations = $this->getDoctrine()
                                   ->getRepository( 'UserfriendlyPollBundle:Participation' )
                                   ->findBy( array( 'poll' => $poll, 'user' => $user ));
            foreach ( $participations as $participation )
            {
                $answers[] = $participation->getAnswer();
            }
        }
        else // Look in cookie
        {
            $cookieValue = $this->getPollCookie( $request, $poll ); // TODO: test if this actually returns an array
            $answers = $poll->verifyAnswers( $cookieValue );
        }
        return $answers;
    }
}
