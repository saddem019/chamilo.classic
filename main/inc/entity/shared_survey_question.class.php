<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @license see /license.txt
 * @author autogenerated
 */
class SharedSurveyQuestion extends \Entity
{
    /**
     * @return \Entity\Repository\SharedSurveyQuestionRepository
     */
     public static function repository(){
        return \Entity\Repository\SharedSurveyQuestionRepository::instance();
    }

    /**
     * @return \Entity\SharedSurveyQuestion
     */
     public static function create(){
        return new self();
    }

    /**
     * @var integer $question_id
     */
    protected $question_id;

    /**
     * @var integer $survey_id
     */
    protected $survey_id;

    /**
     * @var text $survey_question
     */
    protected $survey_question;

    /**
     * @var text $survey_question_comment
     */
    protected $survey_question_comment;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $display
     */
    protected $display;

    /**
     * @var integer $sort
     */
    protected $sort;

    /**
     * @var string $code
     */
    protected $code;

    /**
     * @var integer $max_value
     */
    protected $max_value;


    /**
     * Get question_id
     *
     * @return integer 
     */
    public function get_question_id()
    {
        return $this->question_id;
    }

    /**
     * Set survey_id
     *
     * @param integer $value
     * @return SharedSurveyQuestion
     */
    public function set_survey_id($value)
    {
        $this->survey_id = $value;
        return $this;
    }

    /**
     * Get survey_id
     *
     * @return integer 
     */
    public function get_survey_id()
    {
        return $this->survey_id;
    }

    /**
     * Set survey_question
     *
     * @param text $value
     * @return SharedSurveyQuestion
     */
    public function set_survey_question($value)
    {
        $this->survey_question = $value;
        return $this;
    }

    /**
     * Get survey_question
     *
     * @return text 
     */
    public function get_survey_question()
    {
        return $this->survey_question;
    }

    /**
     * Set survey_question_comment
     *
     * @param text $value
     * @return SharedSurveyQuestion
     */
    public function set_survey_question_comment($value)
    {
        $this->survey_question_comment = $value;
        return $this;
    }

    /**
     * Get survey_question_comment
     *
     * @return text 
     */
    public function get_survey_question_comment()
    {
        return $this->survey_question_comment;
    }

    /**
     * Set type
     *
     * @param string $value
     * @return SharedSurveyQuestion
     */
    public function set_type($value)
    {
        $this->type = $value;
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set display
     *
     * @param string $value
     * @return SharedSurveyQuestion
     */
    public function set_display($value)
    {
        $this->display = $value;
        return $this;
    }

    /**
     * Get display
     *
     * @return string 
     */
    public function get_display()
    {
        return $this->display;
    }

    /**
     * Set sort
     *
     * @param integer $value
     * @return SharedSurveyQuestion
     */
    public function set_sort($value)
    {
        $this->sort = $value;
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function get_sort()
    {
        return $this->sort;
    }

    /**
     * Set code
     *
     * @param string $value
     * @return SharedSurveyQuestion
     */
    public function set_code($value)
    {
        $this->code = $value;
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function get_code()
    {
        return $this->code;
    }

    /**
     * Set max_value
     *
     * @param integer $value
     * @return SharedSurveyQuestion
     */
    public function set_max_value($value)
    {
        $this->max_value = $value;
        return $this;
    }

    /**
     * Get max_value
     *
     * @return integer 
     */
    public function get_max_value()
    {
        return $this->max_value;
    }
}