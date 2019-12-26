<?php

namespace AppBundle\Controller;

use PartyBundle\Service\ChatService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\DataCollectorTranslator;
/**
 * @Template()
 */
class DefaultController extends Controller
{

    /**
     * @Route("/set-language/{lang}", name="set_language")
     */
    public function setLanguageAction(Request $request, $lang)
    {
        $request->setDefaultLocale($lang);
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        if($this->getUser()){
            return $this->redirectToRoute('party_index');
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * @Route("/regulamin", name="regulamin")
     */
    public function regulaminAction(Request $request)
    {

        return [];
    }


    /**
     * @Route("/private-politics", name="privatePolitics")
     */
    public function privatePoliticsAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/cookies", name="cookies")
     */
    public function cookiesAction(Request $request)
    {
        return [];
    }
}
