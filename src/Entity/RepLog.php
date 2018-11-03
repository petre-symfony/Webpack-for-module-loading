<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepLogRepository")
 */
class RepLog {
	const ITEM_LABEL_PREFIX = 'liftable_thing.';
	const WEIGHT_FAT_CAT = 18;
	private static $thingsYouCanLift = array(
		'cat' => '9',
		'fat_cat' => self::WEIGHT_FAT_CAT,
		'laptop' => '4.5',
		'coffee_cup' => '.5',
	);
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   * @Groups("Default")
   */
  private $id;

  /**
   * @ORM\Column(type="integer")
   * @Assert\NotBlank(message="How many times did you lift this?")
   * @Assert\GreaterThan(value=0, message="You can certainly life more than just 0!")
   * @Groups("Default")
   */
  private $reps;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank(message="What did you lift?")
   * @Groups("Default")
   */
  private $item;

  /**
   * @ORM\Column(type="float", nullable=true)
   * @Groups("Default")
   */
  private $totalWeightLifted;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\User")
   * @ORM\JoinColumn(nullable=false)
   */
  private $user;

  public function getId(): ?int{
    return $this->id;
  }

  public function getReps(): ?int{
    return $this->reps;
  }

  public function setReps(int $reps): self{
    $this->reps = $reps;
	  $this->calculateTotalLifted();

    return $this;
  }

  public function getItem(): ?string{
    return $this->item;
  }

  public function setItem(string $item): self{
	  if (!isset(self::$thingsYouCanLift[$item])) {
		  throw new \InvalidArgumentException(sprintf('You can\'t lift a "%s"!', $item));
	  }
	  $this->item = $item;
	  $this->calculateTotalLifted();

    return $this;
  }

  public function getTotalWeightLifted(): ?float{
    return $this->totalWeightLifted;
  }

	/**
	 * Calculates the total weight lifted and sets it on the property
	 */
	private function calculateTotalLifted(){
		if (!$this->getItem()) {
			return;
		}
		$weight = self::$thingsYouCanLift[$this->getItem()];
		$totalWeight = $weight * $this->getReps();
		$this->totalWeightLifted = $totalWeight;
	}
	/**
	 * Returns an array that an be used in a form drop down
	 *
	 * @return array
	 */
	public static function getThingsYouCanLiftChoices(){
		$things = array_keys(self::$thingsYouCanLift);
		$choices = array();
		foreach ($things as $thingKey) {
			$choices[self::ITEM_LABEL_PREFIX.$thingKey] = $thingKey;
		}
		return $choices;
	}

	public function getItemLabel(){
		return self::ITEM_LABEL_PREFIX.$this->getItem();
	}

  public function getUser(): ?User{
    return $this->user;
  }

  public function setUser(?User $user): self{
    $this->user = $user;

    return $this;
  }
}
