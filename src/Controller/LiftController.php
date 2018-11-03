<?php

namespace App\Controller;

use App\Entity\RepLog;
use App\Form\RepLogType;
use App\Repository\RepLogRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class LiftController extends AbstractController{
	/**
	 * @var RepLogRepository
	 */
	private $repLogRepository;
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	public function __construct(
		RepLogRepository $repLogRepository,
		UserRepository $userRepository
	){
		$this->repLogRepository = $repLogRepository;
		$this->userRepository = $userRepository;
	}

	/**
   * @Route("/lift", name="lift")
   */
  public function index(Request $request, RepLogRepository $repLogRepository){
	  $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
	  $form = $this->createForm(RepLogType::class);
	  $form->handleRequest($request);
	  if ($form->isSubmitted() && $form->isValid()){
		  $em = $this->getDoctrine()->getManager();
		  $repLog = $form->getData();
		  $repLog->setUser($this->getUser());
		  $em->persist($repLog);
		  $em->flush();

		  $this->addFlash('notice', 'Reps crunched!');
		  return $this->redirectToRoute('lift');
	  }


	  return $this->render('lift/index.html.twig', array(
		  'form' => $form->createView(),
		  'leaderboard' => $this->getLeaders()
	  ));
  }

	/**
	 * Returns an array of leader information
	 *
	 * @return array
	 */
	private function getLeaders(){
		$leaderboardDetails = $this->repLogRepository->getLeaderboardDetails();
		$leaderboard = [];
		foreach($leaderboardDetails as $details){
			if(!$user = $this->userRepository->find($details['user_id'])){
				// interesting, this user is missing...
				continue;
			}
			$leaderboard[] = [
				'username' => $user->getUsername(),
				'weight' => $details['weightSum'],
				'in_cats' => number_format($details['weightSum']/RepLog::WEIGHT_FAT_CAT),
			];
		}
		return $leaderboard;
	}
}
