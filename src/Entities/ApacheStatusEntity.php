<?php

namespace ZnTool\Deployer\Entities;

class ApacheStatusEntity
{

    private $status;
    private $ago;
    private $processId;
    private $mainPid;
    private $taksCount;
    private $memory;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getAgo()
    {
        return $this->ago;
    }

    /**
     * @param mixed $ago
     */
    public function setAgo($ago): void
    {
        $this->ago = $ago;
    }

    /**
     * @return mixed
     */
    public function getProcessId()
    {
        return $this->processId;
    }

    /**
     * @param mixed $processId
     */
    public function setProcessId($processId): void
    {
        $this->processId = $processId;
    }

    /**
     * @return mixed
     */
    public function getMainPid()
    {
        return $this->mainPid;
    }

    /**
     * @param mixed $mainPid
     */
    public function setMainPid($mainPid): void
    {
        $this->mainPid = $mainPid;
    }

    /**
     * @return mixed
     */
    public function getTaksCount()
    {
        return $this->taksCount;
    }

    /**
     * @param mixed $taksCount
     */
    public function setTaksCount($taksCount): void
    {
        $this->taksCount = $taksCount;
    }

    /**
     * @return mixed
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param mixed $memory
     */
    public function setMemory($memory): void
    {
        $this->memory = $memory;
    }

    public function isActive(): bool
    {
        return $this->status == 'active';
    }
}
