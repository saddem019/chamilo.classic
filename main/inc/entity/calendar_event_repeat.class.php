<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @license see /license.txt
 * @author autogenerated
 */
class CalendarEventRepeat extends \CourseEntity
{
    /**
     * @return \Entity\Repository\CalendarEventRepeatRepository
     */
     public static function repository(){
        return \Entity\Repository\CalendarEventRepeatRepository::instance();
    }

    /**
     * @return \Entity\CalendarEventRepeat
     */
     public static function create(){
        return new self();
    }

    /**
     * @var integer $c_id
     */
    protected $c_id;

    /**
     * @var integer $cal_id
     */
    protected $cal_id;

    /**
     * @var string $cal_type
     */
    protected $cal_type;

    /**
     * @var integer $cal_end
     */
    protected $cal_end;

    /**
     * @var integer $cal_frequency
     */
    protected $cal_frequency;

    /**
     * @var string $cal_days
     */
    protected $cal_days;


    /**
     * Set c_id
     *
     * @param integer $value
     * @return CalendarEventRepeat
     */
    public function set_c_id($value)
    {
        $this->c_id = $value;
        return $this;
    }

    /**
     * Get c_id
     *
     * @return integer 
     */
    public function get_c_id()
    {
        return $this->c_id;
    }

    /**
     * Set cal_id
     *
     * @param integer $value
     * @return CalendarEventRepeat
     */
    public function set_cal_id($value)
    {
        $this->cal_id = $value;
        return $this;
    }

    /**
     * Get cal_id
     *
     * @return integer 
     */
    public function get_cal_id()
    {
        return $this->cal_id;
    }

    /**
     * Set cal_type
     *
     * @param string $value
     * @return CalendarEventRepeat
     */
    public function set_cal_type($value)
    {
        $this->cal_type = $value;
        return $this;
    }

    /**
     * Get cal_type
     *
     * @return string 
     */
    public function get_cal_type()
    {
        return $this->cal_type;
    }

    /**
     * Set cal_end
     *
     * @param integer $value
     * @return CalendarEventRepeat
     */
    public function set_cal_end($value)
    {
        $this->cal_end = $value;
        return $this;
    }

    /**
     * Get cal_end
     *
     * @return integer 
     */
    public function get_cal_end()
    {
        return $this->cal_end;
    }

    /**
     * Set cal_frequency
     *
     * @param integer $value
     * @return CalendarEventRepeat
     */
    public function set_cal_frequency($value)
    {
        $this->cal_frequency = $value;
        return $this;
    }

    /**
     * Get cal_frequency
     *
     * @return integer 
     */
    public function get_cal_frequency()
    {
        return $this->cal_frequency;
    }

    /**
     * Set cal_days
     *
     * @param string $value
     * @return CalendarEventRepeat
     */
    public function set_cal_days($value)
    {
        $this->cal_days = $value;
        return $this;
    }

    /**
     * Get cal_days
     *
     * @return string 
     */
    public function get_cal_days()
    {
        return $this->cal_days;
    }
}