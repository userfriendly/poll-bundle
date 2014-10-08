<?php

namespace Userfriendly\Bundle\PollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Userfriendly\Bundle\PollBundle\Entity\Poll;
use Userfriendly\Bundle\PollBundle\Entity\PollAnswer;
use Userfriendly\Bundle\PollBundle\Entity\PollQuestion;
use Userfriendly\Bundle\PollBundle\Entity\PollParticipation;

class PollController extends Controller
{
    /**
     * Process poll form
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function pollParticipationAction( Request $request )
    {
        $token = $this->get( 'security.context' )->getToken();
        if ( !$token ) throw new \Exception( 'invalid token' );
        $pollId = $request->get( 'poll_id' );
        $answerIdArray = $request->get( 'answer' );
        if ( !is_array( $answerIdArray ) || !$pollId ) throw new \Exception( 'invalid data' );
        $em = $this->getDoctrine()->getManager();
        $poll = $em->getRepository( 'UserfriendlyPollBundle:Poll' )->find( $pollId );
        if ( !$poll ) throw new \Exception( 'invalid poll' );
        $answerRepo = $em->getRepository( 'UserfriendlyPollBundle:PollAnswer' );
        foreach ( $answerIdArray as $answerId )
        {
            $answer = $answerRepo->find( $answerId );
            if ( !$answer ) throw new \Exception( 'invalid poll option' );
            $participation = new PollParticipation();
            $participation->setUser( $token->getUser() );
            $participation->setPoll( $poll );
            $participation->setAnswer( $answer );
            $em->persist( $participation );
        }
        $em->flush();
        return $this->redirect( $request->headers->get( 'referer' ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sidebarAction( Request $request )
    {
        $repo = $this->getDoctrine()->getRepository( 'UserfriendlyPollBundle:Poll' );
        $polls = $repo->getLatestPolls( 2 );
        return $this->render( 'UserfriendlyPollBundle:Poll:sidebar.html.twig', array(
            'polls' => $polls,
        ));
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
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
                $pollQuestion = new PollQuestion( $questionString );
                if ( $request->get( 'multiple' )) $pollQuestion->setMultiple( true );
                foreach ( $answersArray as $answerString )
                {
                    $answer = new PollAnswer( $answerString );
                    $pollQuestion->addAnswer( $answer );
                }
                $poll = new Poll();
                $poll->setPollQuestion( $pollQuestion );
                $em = $this->getDoctrine()->getManager();
                $em->persist( $poll );
                $em->flush();
            }
            return $this->redirect( $this->generateUrl( 'create_poll_form' ));
        }
        return $this->render( 'UserfriendlyPollBundle:Poll:create.html.twig', array(
        ));
    }
}
