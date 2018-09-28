<?php
require_once(dirname(__FILE__) . '/Timer.php');
class TimeLogger
{
    protected static $instanceList = array();
    protected $logPath;
    protected $timerName;
    protected $name;
    protected $staticInfo = '';
    protected $logFolderPath = '/var/log';
    protected function __construct($name = 'default')
    {
        $this->logPath = $this->logFolderPath . '/' . self::genFileName($name);
        $this->timerName = "timer_$name";
        $timer = Timer::getInstance($this->timerName);
    }
    protected static function genFileName($logName)
    {
        return $logName. '-' . date('Y-m-d') . ".log";
    }
    public function setStaticInfo($staticInfo)
    {
        $this->staticInfo = $staticInfo;
    }
    public function setLogFolderPath($logFolderPath)
    {
        if(!file_exists($logFolderPath)){
            throw new Exception("Path $logFolderPath not exists");
        };
        $this->logFolderPath = $logFolderPath;
        $this->logPath = $this->logFolderPath . '/' . basename($this->logPath);
    }
    public static function getInstance($logName = 'default')
    {
        if(!array_key_exists($logName, self::$instanceList)){
            self::$instanceList[$logName] = new self($logName);
        };
        return self::$instanceList[$logName];
    }
    public function startLog()
    {
        $timer = Timer::getInstance($this->timerName);
        $timer->setTime();
        $logMessage = 'INFO [' . date('Y-m-d H:i:s') . ' 0] ';
        if($this->staticInfo != ''){
            $logMessage .= "($this->staticInfo)";
        };
        $logMessage .= ": Start time log\n";
        file_put_contents($this->logPath, $logMessage, FILE_APPEND);
    }
    public function log($message, $isShowTime = false, $level = 'info')
    {
        $timer = Timer::getInstance($this->timerName);
        $timeData = $timer->getTime();
        $logMessage = strtoupper($level) . ' [';
        if($isShowTime){
            $logMessage .= date('Y-m-d H:i:s') . ', ';
        };
        $logMessage .= "{$timeData['full']}, {$timeData['diff']}] ";
        if($this->staticInfo != ''){
            $logMessage .= "($this->staticInfo)";
        };
        $logMessage .= ": $message\n";
        file_put_contents($this->logPath, $logMessage, FILE_APPEND);
    }
    public function endLog()
    {
        $timer = Timer::getInstance($this->timerName);
        $timeData = $timer->getTime();
        $logMessage = 'INFO[' . date('Y-m-d H:i:s') . " {$timeData['full']}, {$timeData['diff']}]";
        if($this->staticInfo != ''){
            $logMessage .= "($this->staticInfo)";
        };
        $logMessage .= ": End time log\n";
        file_put_contents($this->logPath, $logMessage, FILE_APPEND);
    }
}
