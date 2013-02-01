<?php
namespace Slim;

class FluentLogwriter
{
    /**
     * @var logger
     */
    protected $logger;

    /**
     * @var params
     */
    protected $params;

    /**
     * Constructor
     * @param  params(host, port, tag, tag_with_date) $params
     */
    public function __construct($params)
    {
        $this->params = array_merge(
            array(
                'host' => 'localhost',
                'port' => '24224',
                'tag' => 'systemlog',
            ), 
            $params
        );
        // postfix date (ex. 'Ym', 'Ymd', ...)
        if (isset($this->params['tag_with_date']))
        {
            $ts = new DateTime();
            $this->params['tag'] = 
                $this->params['tag'] . $ts->format($this->params['tag_with_date']);
        }
        $this->logger = new Fluent\Logger\FluentLogger(
            $this->params['host'], 
            $this->params['port']
        );
    }

    /**
     * Write message
     * @param  mixed     $message
     * @param  int       $level
     * @return true|false
     */
    public function write($message, $level = null)
    {
        return $this->logger->post(
            $this->params['tag'],
            array(
                'l' => $level,
                'm' => $message
            )
        );
    }
}
