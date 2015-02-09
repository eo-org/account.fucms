<?php

namespace DoctrineMongoHydrator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class AccountDocumentWebsiteHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['server'])) {
            $reference = $data['server'];
            if (isset($this->class->fieldMappings['server']['simple']) && $this->class->fieldMappings['server']['simple']) {
                $className = $this->class->fieldMappings['server']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->dm->getClassNameFromDiscriminatorValue($this->class->fieldMappings['server'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['server']->setValue($document, $return);
            $hydratedData['server'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['globalSiteId'])) {
            $value = $data['globalSiteId'];
            $return = (int) $value;
            $this->class->reflFields['globalSiteId']->setValue($document, $return);
            $hydratedData['globalSiteId'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['uniqueSubdomain'])) {
            $value = $data['uniqueSubdomain'];
            $return = (string) $value;
            $this->class->reflFields['uniqueSubdomain']->setValue($document, $return);
            $hydratedData['uniqueSubdomain'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['label'])) {
            $value = $data['label'];
            $return = (string) $value;
            $this->class->reflFields['label']->setValue($document, $return);
            $hydratedData['label'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['pyInitial'])) {
            $value = $data['pyInitial'];
            $return = (string) $value;
            $this->class->reflFields['pyInitial']->setValue($document, $return);
            $hydratedData['pyInitial'] = $return;
        }

        /** @Many */
        $mongoData = isset($data['domains']) ? $data['domains'] : null;
        $return = new \Doctrine\ODM\MongoDB\PersistentCollection(new \Doctrine\Common\Collections\ArrayCollection(), $this->dm, $this->unitOfWork, '$');
        $return->setHints($hints);
        $return->setOwner($document, $this->class->fieldMappings['domains']);
        $return->setInitialized(false);
        if ($mongoData) {
            $return->setMongoData($mongoData);
        }
        $this->class->reflFields['domains']->setValue($document, $return);
        $hydratedData['domains'] = $return;

        /** @Field(type="date") */
        if (isset($data['created'])) {
            $value = $data['created'];
            if ($value instanceof \MongoDate) { $date = new \DateTime(); $date->setTimestamp($value->sec); $return = $date; } else { $return = new \DateTime($value); }
            $this->class->reflFields['created']->setValue($document, clone $return);
            $hydratedData['created'] = $return;
        }

        /** @Field(type="date") */
        if (isset($data['expireDate'])) {
            $value = $data['expireDate'];
            if ($value instanceof \MongoDate) { $date = new \DateTime(); $date->setTimestamp($value->sec); $return = $date; } else { $return = new \DateTime($value); }
            $this->class->reflFields['expireDate']->setValue($document, clone $return);
            $hydratedData['expireDate'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['storageCapacity'])) {
            $value = $data['storageCapacity'];
            $return = (int) $value;
            $this->class->reflFields['storageCapacity']->setValue($document, $return);
            $hydratedData['storageCapacity'] = $return;
        }

        /** @Field(type="boolean") */
        if (isset($data['trial'])) {
            $value = $data['trial'];
            $return = (bool) $value;
            $this->class->reflFields['trial']->setValue($document, $return);
            $hydratedData['trial'] = $return;
        }

        /** @Field(type="boolean") */
        if (isset($data['active'])) {
            $value = $data['active'];
            $return = (bool) $value;
            $this->class->reflFields['active']->setValue($document, $return);
            $hydratedData['active'] = $return;
        }

        /** @Field(type="boolean") */
        if (isset($data['removed'])) {
            $value = $data['removed'];
            $return = (bool) $value;
            $this->class->reflFields['removed']->setValue($document, $return);
            $hydratedData['removed'] = $return;
        }
        return $hydratedData;
    }
}