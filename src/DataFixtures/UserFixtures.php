<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
        {
            $faker = Faker\Factory::create('fr_FR');
            for ($i = 0; $i < 10; $i++) {
                $user = new User();
                $user->setEmail($faker->email);
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    '123456'
                ));
                $user->setRoles(['ROLE_SUBSCRIBER']);
                $manager->persist($user);
                $this->addReference('user_'.$i, $user);
            }

            // Création d’un utilisateur de type “administrateur”
            $admin = new User();
            $admin->setEmail('admin@admin.com');
            $admin->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin'
            ));
            $admin->setRoles(['ROLE_ADMIN']);
            $manager->persist($admin);

            // Sauvegarde des 2 nouveaux utilisateurs :
            $manager->flush();
        }
}
