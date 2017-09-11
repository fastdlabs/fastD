<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Logger\Formatter;

use FastD\Application;
use Monolog\Formatter\LogstashFormatter;

/**
 * Class StashFormatter.
 */
class StashFormatter extends LogstashFormatter
{
    public function __construct()
    {
        parent::__construct(app()->getName(), get_local_ip(), null, null);
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        if (empty($record['datetime'])) {
            $record['datetime'] = gmdate('c');
        }
        $message = array(
            '@timestamp' => $record['datetime'],
            '@source' => $this->systemName,
            '@fields' => array(),
        );
        if (isset($record['message'])) {
            $message['@message'] = $record['message'];
        }
        if (isset($record['channel'])) {
            $message['@tags'] = array($record['channel']);
            $message['@fields']['channel'] = $record['channel'];
        }
        if (isset($record['level'])) {
            $message['@fields']['level'] = $record['level'];
        }
        if ($this->applicationName) {
            $message['@type'] = $this->applicationName;
        }
        if (isset($record['extra']['server'])) {
            $message['@source_host'] = $record['extra']['server'];
        }
        if (isset($record['extra']['url'])) {
            $message['@source_path'] = $record['extra']['url'];
        }
        if (!empty($record['extra'])) {
            foreach ($record['extra'] as $key => $val) {
                $message['@fields'][$this->extraPrefix . $key] = $val;
            }
        }
        if (!empty($record['context'])) {
            foreach ($record['context'] as $key => $val) {
                $message['@fields'][$this->contextPrefix . $key] = $val;
            }
        }
        print_r($message);
        die;
        return $this->toJson($message);
    }
}
