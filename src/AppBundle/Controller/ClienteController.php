<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Cliente;
use AppBundle\Form\Type\ClienteType;
use AppBundle\Form\Type\ClienteEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\AsignacionCliente;

/**
 * Cliente controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/cliente")
 */
class ClienteController extends Controller
{
    /**
     * Lists all Cliente entities.
     *
     * @Route("/", name="cliente")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_CLIENTES')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $claseActual = $discriminator->getClass();
        $usuarioActual = $this->getUser();
        //mostrar todo en caso de ser socio
        //o ser El código 69, Ciro Salay.
        if ($claseActual == "UserBundle\Entity\UsuarioSocio" ||
          $usuarioActual->getCodigo()->getCodigo() === 69
          ) {
            $entities = $em->getRepository('AppBundle:Cliente')->findAll();

            return $this->render('AppBundle:Cliente:indexCliente.html.twig', array(
              'entities' => $entities,
          ));
        }
        $clientes = $this->filtrarClientes($usuarioActual);

        return $this->render('AppBundle:Cliente:indexCliente.html.twig', array(
              'entities' => $clientes,
          ));
    }

    private function filtrarClientes($usuario)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select('cliente')
        ->from('AppBundle:Cliente', 'cliente')
        ->innerJoin('AppBundle:AsignacionCliente', 'asignacion', 'with', 'cliente.id = asignacion.cliente')
        ->where('asignacion.usuario = :usuario')
        ->OrWhere('asignacion.usuario = 1')
        ->setParameter('usuario', $usuario);

        return $qb->getQuery()->getResult();
    }

    /**
     * Creates a new Cliente entity.
     *
     * @Route("/", name="cliente_create")
     * @Method("POST")
     * @Security("is_granted('ROLE_CREAR_CLIENTES')")
     * @Template("AppBundle:Cliente:newCliente.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Cliente();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //modificar asignacion
            $usuarios = $form->getData()->getUsuarioAsignados();
            $copyUsuarios = clone $usuarios;
            $form->getData()->clearUsuarios();

            foreach ($copyUsuarios as $usuario) {
                $asignacion = new AsignacionCliente($usuario, $entity);

                $em->persist($asignacion);
                $form->getData()->addUsuarioAsignado($asignacion);
            }

            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', 'El cliente ha sido creado exitosamente');
            if ($form->get('submitAndSave')->isClicked()) {
                return $this->redirectToRoute('cliente_new');
            }

            return $this->redirect($this->generateUrl('cliente_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Cliente entity.
     *
     * @param Cliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Cliente $entity)
    {
        $form = $this->createForm(new ClienteType(), $entity, array(
            'action' => $this->generateUrl('cliente_create'),
            'method' => 'POST',
        ));

        $form->add('submitAndSave', 'submit', [
                    'label' => 'Guardar e ingresar otro',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ]);
        $form->add('submit', 'submit', [
                    'label' => 'Guardar y ver detalle',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ]);

        return $form;
    }

    /**
     * Displays a form to create a new Cliente entity.
     *
     * @Route("/new", name="cliente_new")
     * @Method("GET")
     * @Security("is_granted('ROLE_CREAR_CLIENTES')")
     * @Template("AppBundle:Cliente:newCliente.html.twig")
     */
    public function newAction()
    {
        $entity = new Cliente();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Cliente entity.
     *
     * @Route("/{id}", name="cliente_show")
     * @Method("GET")
     * @Security("is_granted('ROLE_VER_CLIENTES')")
     * @Template("AppBundle:Cliente:showCliente.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Cliente entity.
     *
     * @Route("/{id}/edit", name="cliente_edit")
     * @Method("GET")
     * @Template("AppBundle:Cliente:editCliente.html.twig")
     * @Security("is_granted('ROLE_EDITAR_CLIENTES')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Cliente entity.
     *
     * @param Cliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Cliente $entity)
    {
        $form = $this->createForm(new ClienteType(), $entity, array(
            'action' => $this->generateUrl('cliente_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Cliente entity.
     *
     * @Route("/{id}", name="cliente_update")
     * @Method("PUT")
     * @Template("AppBundle:Cliente:editCliente.html.twig")
     * @Security("is_granted('ROLE_EDITAR_CLIENTES')")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //modificar asignacion
            $usuarios = $editForm->getData()->getUsuarioAsignados();
            $copyUsuarios = clone $usuarios;
            $editForm->getData()->clearUsuarios();

            foreach ($copyUsuarios as $usuario) {
                $asignacion = new AsignacionCliente($usuario, $entity);

                $em->persist($asignacion);
                $editForm->getData()->addUsuarioAsignado($asignacion);
            }

            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', 'El cliente ha sido actualizado exitosamente');
            return $this->redirect($this->generateUrl('cliente_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Cliente entity.
     *
     * @Route("/{id}", name="cliente_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_ELIMINAR_CLIENTES')")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Cliente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Cliente entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cliente'));
    }

    /**
     * Creates a form to delete a Cliente entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cliente_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'hide-submit')))
            ->getForm()
        ;
    }
}
