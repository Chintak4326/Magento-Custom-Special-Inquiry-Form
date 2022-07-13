<?php

namespace ChintakExtensions\Special\Api\Data;

interface SpecialInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const FNAME = 'fname';
    const EMAIL = 'email';
    const MOBILE = 'mobile';
    const SUBJECT = 'subject';
    const MESSAGE = 'message';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function getEntityId();

    public function setEntityId($entityId);


    public function getFname();

    public function setFname($fname);


    public function getEmail();

    public function setEmail($email);


    public function getMobile();

    public function setMobile($mobile);


    public function getSubject();

    public function setSubject($subject);


    public function getMessage();

    public function setMessage($message);

    
    public function getCreatedAt();

    public function setCreatedAt($createdAt);


    public function getUpdatedAt();

    public function setUpdatedAt($updatedAt);

}