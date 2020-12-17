<?php

namespace App\DataFixtures;

use App\Entity\Gutschein;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GutscheinFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'dennis@ilkss-gutschein.test']);

        $gutSchein = new Gutschein();
        $gutSchein->setGsBetrag(22000);
        $gutSchein->setUser($user);
        $gutSchein->setGsDate(new \DateTime());
        $gutSchein->setIsPayed(false);
        $gutSchein->setGsName('Dennis Esken');
        $gutSchein->setHash('fdjow34j230rdf92jlkdsf9j320f');
        $manager->persist($gutSchein);
        $manager->flush();
    }


    public function getDependencies(): array
    {
        return array(
            UserFixtures::class,
        );
    }
}
