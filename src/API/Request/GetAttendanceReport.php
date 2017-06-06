<?php
namespace mikemix\Wiziq\API\Request;

use mikemix\Wiziq\Common\Api\RequestInterface;

class GetAttendanceReport implements RequestInterface
{
    /**
     * @var int
     */
    private $classroomId;

    public function __construct($classroomId)
    {
        $this->classroomId = $classroomId;
    }

    /**
     * Returns the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'get_attendance_report';
    }

    /**
     * Returns the method params.
     *
     * @return array
     */
    public function getParams()
    {
        return [
            'class_id' => $this->classroomId,
        ];
    }
}
