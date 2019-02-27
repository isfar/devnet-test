<?php

namespace Mo\Model\Entity;
/**
 * @Entity @Table(name="mo")
 **/
class Mo 
{
    /** @Id @Column(type="integer") @GeneratedValue 
     * @var int
     */
    protected $id;
    /** @Column(type="string") 
     * @var string
     */
    protected $msisdn;
    /**
     * @Column(name="operatorid", type="integer")
     * @var int
     */
    protected $operatorId; 
    /**
     * @Column(name="shortcodeid", type="integer")
     * @var int
     */
    protected $shortcodeId; 
    /**
     * @Column(name="text", type="string")
     * @var string
     */
    protected $text;
    /**
     * @Column(name="auth_token", type="string")
     * @var string
     */
    protected $authToken;
    /**
     * @Column(name="created_at", type="datetime")
     * @var \DateTime
     */
    protected $createdAt; 

    public function setId(int $id): Mo {
        $this->id = $id;
        return $this;
    }
    public function getId(): int {
        return $this->id;
    }
    

    public function setMsisdn(string $msisdn): Mo {
        $this->msisdn = $msisdn;
        return $this;
    }

    public function getMsisdn(): string {
        return $this->msisdn;
    }

    public function setOperatorId(int $id): Mo {
        $this->operatorId = $id;
        return $this;
    } 

    public function getOperatorId(): int {
        return $this->operatorId;
    }

    public function setShortcodeId(int $id): Mo {
        $this->shortcodeId = $id;
        return $this;
    }

    public function getShortcodeId(): int {
        return $this->shortcodeId;
    }

    public function setText(string $text): Mo {
        $this->text = $text;
        return $this;
    }
    public function getText(): string {
        return $this->text;
    }

    public function setAuthToken(string $token): Mo {
        $this->authToken = $token;
        return $this;
    }

    public function getAuthToken(): string {
        return $this->authToken;
    }

    public function setCreatedAt(\DateTime $datetime): Mo {
        $this->createdAt = $datetime;
        return $this;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }
}
