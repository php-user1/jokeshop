<?php

namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Authentication\Form\RegisterForm;
use Application\Entity\User;
use Authentication\Service\ValidationServiceInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\FormInterface;

class RegisterController extends AbstractActionController
{
    private $entityManager;
    private $registerForm;
    private $validationService;
    private $ormAuthService;

    public function __construct(
        EntityManagerInterface $entityManager,
        RegisterForm $registerForm,
        ValidationServiceInterface $validationService,
        $ormAuthService
    ) {
        $this->entityManager      = $entityManager;
        $this->registerForm       = $registerForm;
        $this->validationService  = $validationService;
        $this->ormAuthService     = $ormAuthService;
    }

    private function prepareData($user)
    {
        $user->setPasswordSalt(sha1(time() . 'userPasswordSalt'));
        $user->setPassword(sha1('passwordStaticSalt' . $user->getPassword() . $user->getPasswordSalt()));
    }

    public function indexAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute('home');
            die;
        }

        $this->layout('layout/authLayout');

        $user = new User();
        $form = $this->registerForm;

        $form->setHydrator(new DoctrineObject($this->entityManager));
        $form->bind($user);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            $repository = $this->entityManager->getRepository(User::class);
            if ($this->validationService->isObjectExists($repository, $form->get('name')->getValue(), ['name'])) {
                $nameExists = 'User with name "' . $form->get('name')->getValue() . '" exists already';
                $form->get('name')->setMessages(['nameExists' => $nameExists]);
            }

            if ($form->isValid() && empty($form->getMessages())) {
                $cloneUser = clone $user; // to have not hashed password
                $this->prepareData($user);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->entityManager->getRepository(User::class)->login($cloneUser, $this->ormAuthService);

                return $this->redirect()->toRoute('home');
            }
        }

        return new ViewModel(['form' => $form]);
    }
}
