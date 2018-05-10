<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/3/2018
 * Time: 9:10 PM
 */

namespace AppBundle\Domain\Model\Trading;


use AppBundle\Domain\Model\Util\Formatter;

class Interest
{
    const TYPE_FIXED = 'fixed';
    const TYPE_VARIABLE = 'variable';
    
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var double
     */
    protected $percent;
    /**
     * @var \DateInterval
     */
    protected $interval;
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @param double $percent
     * @return Interest
     */
    public function setPercent($percent)
    {
        $this->percent = Formatter::toDouble($percent);
        return $this;
    }
    
    /**
     * @param \DateInterval $interval
     * @return Interest
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }
    
    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }
    
    /**
     * @return \DateInterval
     */
    public function getInterval()
    {
        return $this->interval;
    }
    
    /**
     * @param string $type
     * @return Interest
     */
    public function setType($type)
    {
        if (in_array($type, [self::TYPE_FIXED, self::TYPE_VARIABLE])) {
            $this->type = $type;
        }
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}