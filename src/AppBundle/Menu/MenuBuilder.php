<?php
/**
 * Created by PhpStorm.
 * User: apacz
 * Date: 06.07.2017
 * Time: 14:23
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Security;
use UserBundle\Service\UserInformationService;

class MenuBuilder
{

    /**
     * @var UserInformationService
     */
    private $userService;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     * @param UserInformationService $userService
     */
    public function __construct(FactoryInterface $factory, UserInformationService $userService)
    {
        $this->factory = $factory;
        $this->userService = $userService;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

//        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->addChild('Places list', array('route' => 'place_index'));
        $menu->addChild('Parties list', array('route' => 'party_index'));

        $isUserLogin = $this->userService->currentUserHasRole('ROLE_USER');

        if ($isUserLogin == true) {
            $displayName = $this->userService->displayCurrentUsername();

            if ($this->userService->currentUserHasRole('ROLE_ADMIN')){
                $menu->addChild('Admin Panel', array('route' => 'easyadmin'));
            }

            $account = $menu->addChild($displayName, array('uri'=>'#'))
                ->setExtra('safe_label', true )
                ->setChildrenAttribute('class','sub-menu');
            $account->addChild('Profile', array('route' => 'fos_user_profile_show'));
            $account->addChild('Preference', array('route' => 'user_preference'));
            $account->addChild('Edit', array('route' => 'fos_user_profile_edit'));
            $account->addChild('Logout',
                array('route' => 'fos_user_security_logout',
                    'label' => 'Logout'));

        } else {
            $account = $menu->addChild('Account', array('uri'=>'#'))
                ->setChildrenAttribute('class','sub-menu');
            $account->addChild('Login', array('route' => 'fos_user_security_login'));
            $account->addChild('Forget Password?', array('route' => 'fos_user_resetting_request'));
            $account->addChild('Register', array('route' => 'fos_user_registration_register'));
        }


        return $menu;
    }
}