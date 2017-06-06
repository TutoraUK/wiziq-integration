<?php
namespace mikemix\Wiziq\API\Request;

use mikemix\Wiziq\Common\Api\RequestInterface;
use mikemix\Wiziq\Entity\Classroom;

class GetData implements RequestInterface
{
    /**
     * @var int
     */
    private $classroomId;

    /**
     * @var string
     */
    protected $parameters;

    public function __construct($classroomId, $parameters)
    {
        $this->classroomId = $classroomId;
        $this->parameters = $parameters;
    }

    /**
     * Returns the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'get_data';
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
            'columns' => $this->parameters
        ];
    }
}
