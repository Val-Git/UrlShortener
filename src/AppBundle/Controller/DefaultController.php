<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\ShortUrl;
use AppBundle\Form\ShortUrlType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $short_url = new ShortUrl();

        $form = $this->createForm(ShortUrlType::class, $short_url);

        $form->handleRequest($request);

        // Проверяем что форма отправлена и данные в ней валидны
        if ( $form->isSubmitted() && $form->isValid() ) {

            return $this->shorten( $form->getData() );

        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function shorten($url_obj)
    {
        // Получаем данные
        $short_url = $url_obj->getShortUrl();

        // Doctrine Entity Manager
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(ShortUrl::class);

        if ( $repository->isShortUrlExists($short_url) ) {
            // записываем в log
            $this->get('logger')->error('Short url is already used');

            return $this->redirectToRoute('homepage', [
                'error' => "Url title '{$short_url}' is already used. Please enter another one."
            ]);
        }

        // Сохраняем URL в БД
        $url_obj->setCreatedAt();
        $em->persist($url_obj);
        $em->flush();

        // Генерируем короткую строку на основе ID
        $code = $short_url ?? $repository->idToStr($url_obj->getId());

        // Обновляем запись в БД
        $url_obj->setShortUrl($code);
        $em->persist($url_obj);
        $em->flush();

        // записываем в log
        $this->get('logger')->notice("New short url item ({$url_obj->getId()}: {$code}) created");

        return $this->redirectToRoute('homepage', array(
            'short_url' => $code
        ));
    }

    /**
     * @Route("/clean", name="clean")
     *
     * Оформил в виде экшена. Но лучше з апускать по Cron'у
     */
    public function cleanAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(ShortUrl::class);

        $repository->cleanOldItems();

        // записываем в log
        $this->get('logger')->notice('Items of urls pair for last 15 days were cleaned');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/{url}", name="expand", requirements={"url"=".+"})
     */
    public function expandAction($url)
    {
        $url_obj = $this->getDoctrine()
                        ->getRepository(ShortUrl::class)
                        ->findOneBy(['short_url' => $url]);

        if (empty($url_obj)) {
            throw new NotFoundHttpException("There is no URL linked to {$url}.");
        }

        return $this->redirect( $url_obj->getOriginalUrl() );
    }
}
