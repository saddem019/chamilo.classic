<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @license see /license.txt
 * @author autogenerated
 */
class Blog extends \CourseEntity
{
    /**
     * @return \Entity\Repository\BlogRepository
     */
     public static function repository(){
        return \Entity\Repository\BlogRepository::instance();
    }

    /**
     * @return \Entity\Blog
     */
     public static function create(){
        return new self();
    }

    /**
     * @var integer $c_id
     */
    protected $c_id;

    /**
     * @var integer $blog_id
     */
    protected $blog_id;

    /**
     * @var string $blog_name
     */
    protected $blog_name;

    /**
     * @var string $blog_subtitle
     */
    protected $blog_subtitle;

    /**
     * @var datetime $date_creation
     */
    protected $date_creation;

    /**
     * @var boolean $visibility
     */
    protected $visibility;

    /**
     * @var integer $session_id
     */
    protected $session_id;


    /**
     * Set c_id
     *
     * @param integer $value
     * @return Blog
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
     * Set blog_id
     *
     * @param integer $value
     * @return Blog
     */
    public function set_blog_id($value)
    {
        $this->blog_id = $value;
        return $this;
    }

    /**
     * Get blog_id
     *
     * @return integer 
     */
    public function get_blog_id()
    {
        return $this->blog_id;
    }

    /**
     * Set blog_name
     *
     * @param string $value
     * @return Blog
     */
    public function set_blog_name($value)
    {
        $this->blog_name = $value;
        return $this;
    }

    /**
     * Get blog_name
     *
     * @return string 
     */
    public function get_blog_name()
    {
        return $this->blog_name;
    }

    /**
     * Set blog_subtitle
     *
     * @param string $value
     * @return Blog
     */
    public function set_blog_subtitle($value)
    {
        $this->blog_subtitle = $value;
        return $this;
    }

    /**
     * Get blog_subtitle
     *
     * @return string 
     */
    public function get_blog_subtitle()
    {
        return $this->blog_subtitle;
    }

    /**
     * Set date_creation
     *
     * @param datetime $value
     * @return Blog
     */
    public function set_date_creation($value)
    {
        $this->date_creation = $value;
        return $this;
    }

    /**
     * Get date_creation
     *
     * @return datetime 
     */
    public function get_date_creation()
    {
        return $this->date_creation;
    }

    /**
     * Set visibility
     *
     * @param boolean $value
     * @return Blog
     */
    public function set_visibility($value)
    {
        $this->visibility = $value;
        return $this;
    }

    /**
     * Get visibility
     *
     * @return boolean 
     */
    public function get_visibility()
    {
        return $this->visibility;
    }

    /**
     * Set session_id
     *
     * @param integer $value
     * @return Blog
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
}