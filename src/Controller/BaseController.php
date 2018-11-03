<?php

namespace App\Controller;

use App\Api\RepLogApiModel;
use App\Entity\RepLog;
use App\Repository\RepLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class BaseController extends AbstractController{
	/**
	 * @var Serializer
	 */
	private $serializer;
	/**
	 * @var TranslatorInterface
	 */
	private $translator;
	/**
	 * @var RepLogRepository
	 */
	private $repLogRepository;

	public function __construct(
		SerializerInterface $serializer,
		TranslatorInterface $translator,
		RepLogRepository $repLogRepository
	){
		$this->serializer = $serializer;
		$this->translator = $translator;
		$this->repLogRepository = $repLogRepository;
	}

	protected function createApiResponse($data, $statusCode = 200){
		$json = $this->serializer->serialize($data, 'json');

		return new JsonResponse($json, $statusCode, [], true);
  }

	/**
	 * Returns an associative array of validation errors
	 *
	 * {
	 *     'firstName': 'This value is required',
	 *     'subForm': {
	 *         'someField': 'Invalid value'
	 *     }
	 * }
	 *
	 * @param FormInterface $form
	 * @return array|string
	 */
	protected function getErrorsFromForm(FormInterface $form){
		foreach ($form->getErrors() as $error) {
			// only supporting 1 error per field
			// and not supporting a "field" with errors, that has more
			// fields with errors below it
			return $error->getMessage();
		}

		$errors = array();
		foreach ($form->all() as $childForm) {
			if ($childForm instanceof FormInterface) {
				if ($childError = $this->getErrorsFromForm($childForm)) {
					$errors[$childForm->getName()] = $childError;
				}
			}
		}

		return $errors;
	}

	/**
	 * Turns a RepLog into a RepLogApiModel for the API.
	 *
	 * This could be moved into a service if it needed to be
	 * re-used elsewhere.
	 *
	 * @param RepLog $repLog
	 * @return RepLogApiModel
	 */
	protected function createRepLogApiModel(RepLog $repLog){
		$model = new RepLogApiModel();
		$model->id = $repLog->getId();
		$model->reps = $repLog->getReps();
		$model->itemLabel = $this->translator
			->trans($repLog->getItemLabel());
		$model->totalWeightLifted = $repLog->getTotalWeightLifted();

		$selfUrl = $this->generateUrl(
			'rep_log_get',
			['id' => $repLog->getId()]
		);
		$model->addLink('_self', $selfUrl);

		return $model;
	}

	/**
	 * @return RepLogApiModel[]
	 */
	protected function findAllUsersRepLogModels(){
		$repLogs = $this->repLogRepository
			->findBy(array('user' => $this->getUser()))
		;

		$models = [];
		foreach ($repLogs as $repLog) {
			$models[] = $this->createRepLogApiModel($repLog);
		}

		return $models;
	}
}
