<?php
namespace mikemix\Wiziq\API\Request;

use mikemix\Wiziq\Common\Api\RequestInterface;
use mikemix\Wiziq\Entity\Classroom;

class ViewSchedule implements RequestInterface
{
    /** @var Classroom */
    private $classroomId;

    public function __construct($classroomId)
    {
        $this->classroomId = $classroomId;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return 'view_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return [
            'class_master_id' => $this->classroomId
        ];
    }
}
