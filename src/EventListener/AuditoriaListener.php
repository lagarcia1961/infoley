<?php

namespace App\EventListener;

use App\Constants\Auditoria as ConstantsAuditoria;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use App\Entity\Auditoria;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use ReflectionClass;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuditoriaListener
{
    private $em;
    private $tokenStorage;
    private $auditorias = [];
    private $entidadPersistida;


    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!($entity instanceof Auditoria)) {
            $this->entidadPersistida[] = $entity;
        }
        $this->registrarAuditoria($entity, ConstantsAuditoria::INSERTO, null, $args);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $this->entidadPersistida[] = $entity;
        $this->registrarAuditoria($entity, ConstantsAuditoria::MODIFICO, $args);
    }

    public function preRemove(PreRemoveEventArgs $args)
    {
        $entity = $args->getObject();
        $this->entidadPersistida[] = $entity;
        $this->registrarAuditoria($entity, ConstantsAuditoria::ELIMINO, null, $args);
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getObjectManager();
        if (!empty($this->auditorias)) {
            $i = 0;
            foreach ($this->auditorias as $auditoria) {
                if (@$this->entidadPersistida[$i]) {
                    if(@$this->entidadPersistida[$i]->getId()){
                        $auditoria->setEntidadId($this->entidadPersistida[$i]->getId());
                    }
                }
                $em->persist($auditoria);
                $i++;
            }
            $this->auditorias = [];
            $em->flush();
        }
    }

    private function registrarAuditoria($entity, int $accion, ?PreUpdateEventArgs $updateArgs = null, ?LifecycleEventArgs $args = null)
    {
        if (!($entity instanceof Auditoria)) {
            $auditoria = new Auditoria();

            $entidadNombre = (new ReflectionClass($entity))->getShortName();
            $auditoria->setEntidad($entidadNombre);
            $auditoria->setEntidadId((int)$entity->getId());

            $tipoAuditoria = $this->em->getRepository('App\Entity\TipoAuditoria')->find($accion);
            $auditoria->setTipoAuditoria($tipoAuditoria);

            $token = $this->tokenStorage->getToken();
            $usuario = $token ? $token->getUser() : null;
            $auditoria->setUser($usuario);

            // Capturar los valores antiguos y nuevos como arrays
            if ($accion === ConstantsAuditoria::MODIFICO && $updateArgs) {
                $auditoria->setRegistroAnterior($this->getOriginalData($updateArgs));
                $auditoria->setRegistroNuevo($this->getUpdatedData($updateArgs));
            } elseif ($accion === ConstantsAuditoria::ELIMINO && $args) {
                $auditoria->setRegistroAnterior($this->getEntityData($entity));
                $auditoria->setRegistroNuevo(null);
            } elseif ($accion === ConstantsAuditoria::INSERTO) {
                $auditoria->setRegistroAnterior(null);
                $auditoria->setRegistroNuevo($this->getEntityData($entity));
            }

            $this->auditorias[] = $auditoria;
        }
    }

    private function getOriginalData(PreUpdateEventArgs $args)
    {
        $data = [];
        foreach ($args->getEntityChangeSet() as $field => $changes) {
            if ($field === 'password') {
                $data[$field] = 'Dato sensible no disponible';
            } else {
                $data[$field] = $changes[0];
            }
        }
        return $data;
    }

    private function getUpdatedData(PreUpdateEventArgs $args)
    {
        $data = [];
        foreach ($args->getEntityChangeSet() as $field => $changes) {
            if ($field === 'password') {
                $data[$field] = 'Dato sensible no disponible';
            } else {
                $data[$field] = $changes[1];
            }
        }
        return $data;
    }

    private function getEntityData($entity)
    {
        $data = [];
        $reflectionClass = new ReflectionClass($entity);
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if (is_object($property->getValue($entity)) && !$property->getValue($entity) instanceof DateTime && !($property->getValue($entity) instanceof Collection)) {
                $data[$property->getName() . '_id'] = $property->getValue($entity)->getId();
            } else {
                if ($property->getName() === 'password') {
                    $data[$property->getName()] = 'Dato sensible no disponible';
                } else {
                    $data[$property->getName()] = $property->getValue($entity);
                }
            }
        }
        return $data;
    }
}
