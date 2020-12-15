<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Routing\RouterInterface;

class RegistrationFormType extends AbstractType
{
    private $routing;

    public function __construct(RouterInterface $routing){
        $this->routing = $routing;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $urlTerminos = $this->routing->generate('app_terminos');
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label_html' => true,
                'label' => "<a href='" . $urlTerminos. "'>Términos de uso</a>",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Las contraseñas no coinciden',
                'first_options' => [
                    'label' => 'Contraseña',

                ],
                'second_options' => [
                    'label' => 'Repita contraseña',
                    
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Introduzca password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'El password debe tener al menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
