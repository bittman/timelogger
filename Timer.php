<?php
class Timer
{
    protected static $instanceList = array();
    protected $time0;
    protected $currentTime;
    protected function __construct($timerName = 'default')
    {
        $this->time0 = microtime(true);
    }
    public static function getInstance($timerName = 'default')
    {
        if(!array_key_exists($timerName, self::$instanceList)){
            self::$instanceList[$timerName] = new self($timerName);
        };
        return self::$instanceList[$timerName];
    }
    public function setTime()
    {
        $currentTime = microtime(true);
        $this->time0 = $currentTime;
        $this->currentTime = $currentTime;
    }
    public function getTime()
    {
        $currentTime = microtime(true);
        $result = array('full' => ($currentTime - $this->time0), 'diff' => ($currentTime - $this->currentTime));
        $this->currentTime = $currentTime;
        return $result;
    }
    public function getTime0()
    {
        return $this->time0;
    }
}
