<?php
namespace mikemix\Wiziq\API;

use mikemix\Wiziq\API\Request;
use mikemix\Wiziq\Common\Api\ClassroomApiInterface;
use mikemix\Wiziq\Entity\Attendees;
use mikemix\Wiziq\Entity\Classroom;
use mikemix\Wiziq\Entity\PermaClassroom;

class ClassroomApi implements ClassroomApiInterface
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function __construct(Gateway $requester)
    {
        $this->gateway = $requester;
    }

    /**
     * Create a class.
     *
     * @param Classroom $classroom
     * @return array
     */
    public function create(Classroom $classroom)
    {
        $response = $this->gateway->sendRequest(new Request\Create($classroom))
            ->create[0]->class_details[0];

        return [
            'class_id'        => (int)$response->class_id,
            'class_master_id' => (int)$response->class_master_id,
            'recording_url'   => (string)$response->recording_url,
            'presenter_email' => (string)$response->presenter_list[0]->presenter[0]->presenter_email,
            'presenter_url'   => (string)$response->presenter_list[0]->presenter[0]->presenter_url,
        ];
    }

    /**
     * Edit an existing classroom.
     *
     * @param $classroom
     * @return bool
     */
    public function modify($classroom)
    {
        $response = $this->gateway->sendRequest(new Request\Modify($classroom))->modify[0];
        $status = (string)$response->attributes()['status'];

        return filter_var($status, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Cancel a class.
     *
     * @param int $classroomId
     */
    public function cancel($classroomId)
    {
        $this->gateway->sendRequest(new Request\Cancel($classroomId));
    }

    /**
     * Get class data.
     *
     * @param $classroomId
     * @param $parameters
     * @return array
     */
    public function getData($classId, $parameters)
    {
        $response = $this->gateway->sendRequest(new Request\GetData($classId, $parameters))->get_data;

        $record = $response->record_list->record;
        $children = $record->children();

        $record_array = [];
        foreach ($children as $elem) {
            $record_array[$elem->getName()] = ((string)$record->{$elem->getName()});
        }

        return $record_array;
    }

    /**
     * Count the classes by their statuses.
     *
     * @return array
     */
    public function countClassesByStatus()
    {
        $response = $this->gateway->sendRawRequest('get_data', ['columns' => 'status'])->get_data;
        $records = $response->record_list[0];

        $total = [];

        foreach ($records as $record)
        {
            $status = trim((string)$record->status);
            $total[$status] = (isset($total[$status]) ? $total[$status]+1 : 1);
        }

        return $total;
    }

    /**
     * Get attendance report by class_id.
     *
     * @param $classId
     * @return array
     */
    public function getAttendanceReport($classId)
    {
        $report = $this->gateway->sendRequest(new Request\GetAttendanceReport($classId))->get_attendance_report;

        $attendees = $report->attendee_list->attendee;

        $attendees_array = [];
        foreach ($attendees as $attendee)
        {
            $attendees_array[] = [
                'is_presenter'  => strlen(trim((string) $attendee->attributes()['presenter'])) > 0,
                'attendee_id'   => (int) $attendee->attendee_id,
                'screen_name'   => (string) $attendee->screen_name,
                'entry_time'    => (string) $attendee->entry_time,
                'exit_time'     => (string) $attendee->exit_time,
                'attended_minutes' => (int) $attendee->attended_minutes
            ];
        }

        return [
            'class_id'          => (string) $report->class_id,
            'class_duration'    => (int) $report->class_duration,
            'attendees'         => $attendees_array
        ];
    }

    /**
     * Get the information about scheduled class.
     *
     * @param $classMasterId
     * @return array
     */
    public function viewSchedule($classMasterId)
    {
        $response = $this->gateway->sendRequest(new Request\ViewSchedule($classMasterId))->view_schedule->recurring_list->class_details;
        $presenter = $response->presenter_list->presenter;

        return [
            'class_id'          => (int)    $response->class_id,
            'class_master_id'   => (int)    $classMasterId,
            'title'             => (string) $response->class_title,
            //'is_permanent'      => (string) $response->attributes()['perma_class'],
            'start_time'        => (string) $response->start_time,
            'duration'          => (int)    $response->duration,
            'status'            => (string) $response->class_status,
            'recording_url'     => (string) $response->recording_url,
            'presenter_id'      => (string) $presenter->presenter_id,
            'presenter_url'     => (string) $presenter->presenter_url,
            'presenter_email'   => (string) $presenter->presenter_email
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function createPermaClas(PermaClassroom $classroom)
    {
        $response = $this->gateway->sendRequest(new Request\CreatePermaClass($classroom));
        $details  = $response->create_perma_class[0]->perma_class_details[0];

        return [
            'class_id'        => (int)$details->class_master_id,
            'attendee_url'    => (string)$details->common_perma_attendee_url,
            'presenter_email' => (string)$details->presenter[0]->presenter_email,
            'presenter_url'   => (string)$details->presenter[0]->presenter_url,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function addAttendeesToClass($classroomId, Attendees $attendees)
    {
        $response = $this->gateway->sendRequest(new Request\AddAttendees($classroomId, $attendees));
        $result   = [];

        foreach ($response->add_attendees[0]->attendee_list[0] as $attendee) {
            $result[] = ['id' => (int)$attendee->attendee_id, 'url' => (string)$attendee->attendee_url];
        }

        return $result;
    }

    /**
     * @param \SimpleXMLElement $response
     * @return array
     */
    protected function getPresentersFromResponse(\SimpleXMLElement $response)
    {
        $presenters = [];
        foreach ($response->presenter_list[0] as $presenter) {
            $presenters[] = [
                'email' => (string)$presenter->presenter_email,
                'url'   => (string)$presenter->presenter_url,
            ];
        }

        return $presenters;
    }
}
