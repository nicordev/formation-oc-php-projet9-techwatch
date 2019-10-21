<?php

namespace App\DataFixtures;

use App\Entity\Source;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;

class AppFixtures extends Fixture
{
    /**
     * @var PasswordEncoder
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadSource($manager);
        $manager->flush();
    }

    private function loadUser(ObjectManager $manager)
    {
        $user = (new User())->setEmail("sarah.croche@gmail.com");
        $user->setPassword($this->encoder->encodePassword($user, "pwdSucks!0"));
        $manager->persist($user);
    }

    private function loadSource(ObjectManager $manager)
    {
        $urls = [
            'https://afup.org/pages/site/rss.php',
            'https://www.alsacreations.com/rss/actualites.xml',
            'https://putaindecode.io/feed.xml'
        ];

        foreach ($urls as $url) {
            $manager->persist((new Source())->setUrl($url));
        }
    }
}
