<?php

namespace App\Model;

class Operation
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var User
     */
    protected $fromUser;

    /**
     * @var User
     */
    protected $toUser;

    /**
     * @var integer Amount in euro cents.
     */
    protected $amount;

    /**
     * @var integer
     */
    protected $type;

    /**
     * @var string
     */
    protected $createdAt;

    /**
     * @param int|string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param User $fromUser
     * @return self
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;
        return $this;
    }

    /**
     * @return User
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * @param User $toUser
     * @return self
     */
    public function setToUser($toUser)
    {
        $this->toUser = $toUser;
        return $this;
    }

    /**
     * @return User
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * @param integer $amount
     * @return self
     * @throws \Exception
     */
    public function setAmount($amount)
    {
        $this->amount = (int)$amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $type
     * @return self
     * @throws \Exception
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $createdAt
     * @return self
     * @throws \Exception
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
