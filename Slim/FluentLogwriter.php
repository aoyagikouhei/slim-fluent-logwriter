<?php
namespace Slim;

class FluentLogwriter
{
    /**
     * @var loggerAry
     */
    protected $loggerAry;

    /**
     * @var paramsAry
     *    host : host name (localhost)
     *    port : port (24224)
     *    tag : tag (systemlog)
     *    tag_with_date : postfix tag date format
     *    error_handler : error handler
     *    level : level
     *    option : add fluent log params
     */
    protected $paramsAry;

    /**
     * Constructor
     * @param  params $params
     */
    public function __construct($params=null)
    {
        $this->loggerAry = array();
        $this->paramsAry = array();
        if (!is_null($params))
        {
            $this->addFluent($params);
        }
    }
    
    /**
     * add Fluent params
     * @params params
     */
    public function addFluent($params=array())
    {
        $params = array_merge(
            array(
                'host' => 'localhost',
                'port' => '24224',
                'tag' => 'systemlog',
                'level' => \Slim\Log::DEBUG,
                'option' => array()
            ), 
            $params
        );
        
        if (isset($params['tag_with_date']))
        {
            $ts = new \DateTime();
            $params['tag'] = 
                $params['tag'] . $ts->format($params['tag_with_date']);
        }
        
        $logger = new \Fluent\Logger\FluentLogger(
            $params['host'], 
            $params['port']
        );
        if (isset($params['error_handler'])) {
            $logger->registerErrorHandler($params['error_handler']);
        }
        $this->loggerAry[] = $logger;
        $this->paramsAry[] = $params;
    }
    
    /**
     * Check Writable
     * @param message message
     * @param level level
     * @param params params
     * @return true/false
     */
    protected function isWrite($message, $level, $params)
    {
        $in = $level;
        $check = $params['level'];
        return
            (is_null($in) && ($check === \Slim\Log::DEBUG)) || 
            (!is_null($in) && ($in <= $check));
    }

    /**
     * Write message
     * @param  mixed     $message
     * @param  int       $level
     * @return true
     */
    public function write($message, $level = null)
    {
        $count = count($this->loggerAry);
        for ($i = 0; $i < $count; $i++)
        {
            if (!$this->isWrite($message, $level, $this->paramsAry[$i]))
            {
                continue;
            }
            $this->loggerAry[$i]->post(
                $this->paramsAry[$i]['tag'],
                array_merge(
                    $this->paramsAry[$i]['option'],
                    array(
                        'l' => $level,
                        'm' => $message
                    )
                )
            );
        }
        return true;
    }
}
