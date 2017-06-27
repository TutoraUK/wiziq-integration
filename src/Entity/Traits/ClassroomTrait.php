<?php
namespace mikemix\Wiziq\Entity\Traits;

trait ClassroomTrait
{
    private $title;
    private $presenterEmail;
    private $presenterId;
    private $presenterName;
    private $languageCultureName;
    private $attendeeLimit;
    private $presenterDefaultControls;
    private $attendeeDefaultControls;
    private $createRecording;
    private $returnUrl;
    private $statusPingUrl;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param $id
     * @return ClassroomTrait
     */
    public function withClassId($id)
    {
        $self = clone $this;
        $self->class_id = (string)$id;
        return $self;
    }

    /**
     * @param $id
     * @return ClassroomTrait
     */
    public function withClassMasterId($id)
    {
        $self = clone $this;
        $self->class_master_id = (string)$id;
        return $self;
    }

    /**
     * @param $start_time
     * @return ClassroomTrait
     */
    public function withStarttime($start_time)
    {
        $self = clone $this;
        $self->start_time = $start_time;
        return $self;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withPresenterEmail($value)
    {
        $self = clone $this;
        $self->presenterEmail = (string)$value;
        return $self;
    }

    /**
     * @param int    $id
     * @param string $name
     * @return self
     */
    public function withPresenter($id, $name)
    {
        $self = clone $this;
        $self->presenterId   = (int)$id;
        $self->presenterName = (string)$name;
        return $self;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withLanguageCultureName($value)
    {
        $self = clone $this;
        $self->languageCultureName = (string)$value;
        return $self;
    }

    /**
     * @param int $value
     * @return self
     */
    public function withAttendeeLimit($value)
    {
        $self = clone $this;
        $self->attendeeLimit = (int)$value;
        return $self;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withPresenterDefaultControls($value)
    {
        $self = clone $this;
        $self->presenterDefaultControls = (string)$value;
        return $self;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withAttendeeDefaultControls($value)
    {
        $self = clone $this;
        $self->attendeeDefaultControls = (string)$value;
        return $self;
    }

    /**
     * @param bool $value
     * @return self
     */
    public function withCreateRecording($value)
    {
        $self = clone $this;
        $self->createRecording = $value ? 'true' : 'false';
        return $self;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withReturnUrl($value)
    {
        $self = clone $this;
        $self->returnUrl = (string)$value;
        return $self;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withStatusPingUrl($value)
    {
        $self = clone $this;
        $self->statusPingUrl = (string)$value;
        return $self;
    }
}
