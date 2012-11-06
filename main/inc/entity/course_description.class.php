<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @license see /license.txt
 * @author autogenerated
 */
class CourseDescription extends \CourseEntity
{
    /**
     * @return \Entity\Repository\CourseDescriptionRepository
     */
     public static function repository(){
        return \Entity\Repository\CourseDescriptionRepository::instance();
    }

    /**
     * @return \Entity\CourseDescription
     */
     public static function create(){
        return new self();
    }

    /**
     * @var integer $c_id
     */
    protected $c_id;

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var text $content
     */
    protected $content;

    /**
     * @var integer $session_id
     */
    protected $session_id;

    /**
     * @var boolean $description_type
     */
    protected $description_type;

    /**
     * @var integer $progress
     */
    protected $progress;


    /**
     * Set c_id
     *
     * @param integer $value
     * @return CourseDescription
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
     * Set id
     *
     * @param integer $value
     * @return CourseDescription
     */
    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $value
     * @return CourseDescription
     */
    public function set_title($value)
    {
        $this->title = $value;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function get_title()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param text $value
     * @return CourseDescription
     */
    public function set_content($value)
    {
        $this->content = $value;
        return $this;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function get_content()
    {
        return $this->content;
    }

    /**
     * Set session_id
     *
     * @param integer $value
     * @return CourseDescription
     */
    public function set_session_id($value)
    {
        $this->session_id = $value;
        return $this;
    }

    /**
     * Get session_id
     *
     * @return integer 
     */
    public function get_session_id()
    {
        return $this->session_id;
    }

    /**
     * Set description_type
     *
     * @param boolean $value
     * @return CourseDescription
     */
    public function set_description_type($value)
    {
        $this->description_type = $value;
        return $this;
    }

    /**
     * Get description_type
     *
     * @return boolean 
     */
    public function get_description_type()
    {
        return $this->description_type;
    }

    /**
     * Set progress
     *
     * @param integer $value
     * @return CourseDescription
     */
    public function set_progress($value)
    {
        $this->progress = $value;
        return $this;
    }

    /**
     * Get progress
     *
     * @return integer 
     */
    public function get_progress()
    {
        return $this->progress;
    }
}