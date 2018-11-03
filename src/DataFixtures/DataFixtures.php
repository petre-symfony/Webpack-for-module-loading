<?php

namespace App\DataFixtures;

use App\Entity\RepLog;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DataFixtures extends Fixture {
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder){
		$this->passwordEncoder = $passwordEncoder;
	}

  public function load(ObjectManager $manager) {
	  $items = array_flip(RepLog::getThingsYouCanLiftChoices());
	  $names = array(
		  array('Brad', 'Kitt'),
		  array('Cat', 'Middleton'),
		  array('Cindy', 'Clawford'),
		  array('Diane', 'Kitten'),
		  array('Fuzz', 'Aldrin'),
		  array('Hunter S.', 'Thomcat'),
		  array('J.R.R', 'Tollkitten'),
		  array('Madelion', 'Albright'),
		  array('Meowly', 'Cyrus'),
		  array('Ron', 'Furgandy'),
	  );
	  foreach ($names as $name) {
		  $firstName = $name[0];
		  $lastName = $name[1];
		  $user = new User();
		  $username = sprintf('%s_%s', $firstName, $lastName);
		  $username = strtolower($username);
		  $username = str_replace(' ', '', $username);
		  $username = str_replace('.', '', $username);
		  $user->setUsername($username);
		  $user->setFirstName($firstName);
		  $user->setLastName($lastName);
		  $user->setPassword($this->
		  passwordEncoder->encodePassword(
			  $user, 'pumpup'
		  ));
		  $user->setRoles(['ROLE_USER']);
		  $manager->persist($user);
		  for ($j = 0; $j < rand(1, 5); $j++) {
			  $repLog = new RepLog();
			  $repLog->setUser($user);
			  $repLog->setReps(rand(1, 30));
			  $repLog->setItem(array_rand($items));
			  $manager->persist($repLog);
		  }
	  }

    $manager->flush();
  }
}
