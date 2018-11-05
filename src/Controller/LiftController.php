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
use Symfony\Component\Serializer\SerializerInterface;

class LiftController extends BaseController{
	/**
   * @Route("/lift", name="lift")
   */
  public function index(Request $request){
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

		$repLogsModel = $this->findAllUsersRepLogModels();
	  $repLogsJson = $this->serializer->
	    serialize($repLogsModel, 'json');

	  return $this->render('lift/index.html.twig', array(
		  'form' => $form->createView(),
		  'leaderboard' => $this->getLeaders(),
		  'repLogsJson' => $repLogsJson
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
