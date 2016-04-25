<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UserBundle\Entity\TipoPuesto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\Type\ConsultaAprobacionHorasType;

/**
 * CombinarPuesto controller.
 *
 * @Security("is_granted('ROLE_USER')") 
 */
class SupervisorUsuariosController extends Controller
{
    /**
     * Este método tiene como intención mostrarle a un usuario los demás usuarios que están
     * bajo su control. Por ejemplo, si su puesto es tipo gerente puede ver a los usuarios tipo
     * asistente y encargado.
     * 
     *
     * @return Usuario Array de usuarios
     *
     * @Route("/mostrar/usuarios", name="mostrar_usuarios")
     */
    public function usuariosPermisosAction()
    {
        $em = $this->getDoctrine()->getManager();

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $claseActual = $discriminator->getClass();

          //Se necesita saber cual es el tipo de Usuario Actual para saber a donde dirigirlo.
          if ($claseActual == "UserBundle\Entity\UsuarioSocio") {
              $usuarios = $em->getRepository('UserBundle:UsuarioTrabajador')->findAll();

              return $this->render('UserBundle:Puesto:showUsuarioPermisos.html.twig',
                [
                    'usuarios' => $usuarios,
                ]
            );
          }

        $usuarioActual = $this->getUser();

        $arrayPuestos = $usuarioActual->getPuestos();
        //se utiliza solamente el último puesto porque es se toma como el más reciente
        $puestoActual = $arrayPuestos->last();
        //array
        $tipoPuestosJerarquia = $puestoActual->getTipoPuesto()->getPuestos();

        $returnArray = [];
        foreach ($tipoPuestosJerarquia as $tipoPuesto) {
            $puesto = $em->getRepository('UserBundle:Puesto')->findOneBy(['tipoPuesto' => $tipoPuesto]);

            $usuarios = $em->getRepository('UserBundle:UsuarioTrabajador')->findAll();
            foreach ($usuarios as $usuario) {
                $puestosUsuario = $usuario->getPuestos();
                $lastPuesto = $puestosUsuario->last();
                if ($lastPuesto == $puesto && $lastPuesto != null) {
                    $returnArray[] = $usuario;
                }
            }
        }

        return $this->render('UserBundle:Puesto:showUsuarioPermisos.html.twig',
            [
                'usuarios' => $returnArray,
            ]
        );
    }

    /**
     * @Security("is_granted('ROLE_GERENTE')") 
     * @Route("/revisar/horas/{usuario_id}", name="revisar_horas")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function revisarHorasUsuarioAction(Request $request, $usuario_id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('UserBundle:UsuarioTrabajador')->findOneById($usuario_id);
        $registros = $em->getRepository('AppBundle:RegistroHoras')
                  ->findBy(['ingresadoPor' => $usuario]);

        $form = $this->createForm(
            ConsultaAprobacionHorasType::class);

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'AppBundle:AprobacionHoras:revisarHoras.html.twig',
                [
                    'verificador' => true,
                    'registros' => $registros,
                    'usuario' => $usuario,
                    'form' => $form->createView(),
                ]

            );
        }
        $data = $form->getData();
        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];

        $registrosFiltrados = $em->getRepository('AppBundle:RegistroHoras')
                                ->findByFechaAndUsuario($fechaInicio, $fechaFinal, $usuario);

        return $this->render(
                'AppBundle:AprobacionHoras:revisarHoras.html.twig',
                [
                    'registros' => $registrosFiltrados,
                    'usuario' => $usuario,
                    'form' => $form->createView(),
                ]

            );
    }

    /**
     * @Route("/aprobar/{idRegistro}", name="aprobar_horas")
     */
    public function aprobarHoras(Request $request, $idRegistro)
    {
        $em = $this->getDoctrine()->getManager();
        $registro = $em->getRepository('AppBundle:RegistroHoras')->findOneById($idRegistro);
        if ($registro->getAprobado() === false) {
            $registro->setAprobado(true);
            $em->persist($registro);
            $em->flush($registro);

            return new JsonResponse('Se cambió de no aprobado a aprobado');
        } else {
            $registro->setAprobado(false);
            $em->persist($registro);
            $em->flush($registro);

            return new JsonResponse('Se cambió de aprobado a no aprobado');
        }
    }

    /**
     * @Route("/enviar/aviso/horas/{usuario_id}", name="avisar_horas_no_aprobadas")
     */
    public function enviarAvisoHorasNoAprobadas(Request $request, $usuario_id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('UserBundle:UsuarioTrabajador')->findOneById($usuario_id);
        $registros = $em
                ->getRepository('AppBundle:RegistroHoras')
                ->findBy(['ingresadoPor' => $usuario]);

        $cantidadRegistros = 0;
        $registrosFiltrados = [];
        foreach ($registros as $registro) {
            if ($registro->getAprobado() === false) {
                $registrosFiltrados[] = $registro;
                ++$cantidadRegistros;
            }
        }
        $this->sendEmail($usuario, $registrosFiltrados);

        return new JsonResponse('Se ha enviado correo por '.$cantidadRegistros.' registros');
    }
    /**
     * @Route("/mostrar/registros/noAprobados/{array_ids}", name="show_registros_no_aprobados")
     *
     * @param Request   $request
     * @param Array int $array_ids Array de los ids de los registros no aprobados
     */
    public function showRegistrosNoAprobados(Request $request, $array_ids)
    {
        $serializer = $this->get('serializer');
        $idRegistros = $serializer->deserialize($array_ids, '[]', 'json');
        $em = $this->getDoctrine()->getManager();
        $registros = $em->getRepository('AppBundle:RegistroHoras')->findById($idRegistros);

        return $this->render(
            'AppBundle:AprobacionHoras:showRegistrosNoAprobados',
            [
                'registros' => $registros,
            ]
        );
    }

    /**
     * Función para enviar un correo.
     *
     * @param Usuario $enviado_a Nombre de la persona a la que se le envía el correo
     * @param Array   $archivos  Array de registros a enviar
     */
    private function sendEmail($enviado_a, $registros)
    {

        //new instance
         $context = [

        ];
        $fromEmail = 'no-responder@newtonlabs.com.gt';

        $message = \Swift_Message::newInstance();

        $mensaje = 'Estimado usuario, las siguientes horas no han sido aprobadas';

        //espacio para agregar imágenes
        $img_src = $message->embed(\Swift_Image::fromPath('images/email_header.png'));//attach image 1
        $fb_image = $message->embed(\Swift_Image::fromPath('images/fb.gif'));//attach image 2
        $tw_image = $message->embed(\Swift_Image::fromPath('images/tw.gif'));//attach image 3
        $right_image = $message->embed(\Swift_Image::fromPath('images/right.gif'));//attach image 4
        $left_image = $message->embed(\Swift_Image::fromPath('images/left.gif'));//attach image 5

        $subject = 'Aprobación de Horas';

        $message
            ->setSubject($subject)
            ->setFrom([$fromEmail => 'Smart-Time'])
            ->setTo($enviado_a->getEmail())
            ->setBody($this->renderView('AppBundle:AprobacionHoras:emailHoras.html.twig', [
                'image_src' => $img_src,
                'fb_image' => $fb_image,
                'tw_image' => $tw_image,
                'right_image' => $right_image,
                'left_image' => $left_image,
                'enviado_a' => $enviado_a,
                'mensaje' => $mensaje,
                'registros' => $registros,
                ]), 'text/html')
            ->setContentType('text/html')

        ;

        $this->get('mailer')->send($message);
    }
}