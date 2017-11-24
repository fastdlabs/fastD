<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Logger\Formatter;

use Monolog\Formatter\LogstashFormatter;

/**
 * Class StashFormatter.
 */
class StashFormatter extends LogstashFormatter
{
    public function __construct()
    {
        parent::__construct(app()->getName(), get_local_ip(), null, null, self::V1);
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function formatV1(array $record)
    {
        if (empty($record['datetime'])) {
            $record['datetime'] = gmdate('c');
        }
        $message = array(
            '@timestamp' => $record['datetime'],
            '@version' => version(),
            'host' => $this->systemName,
        );
        if (isset($record['message'])) {
            $message['message'] = $record['message'];
        }
        if (isset($record['channel'])) {
            $message['channel'] = $record['channel'];
        }
        if (isset($record['level_name'])) {
            $message['level'] = $record['level_name'];
        }
        if (!empty($record['extra'])) {
            foreach ($record['extra'] as $key => $val) {
                $message[$this->extraPrefix.$key] = $val;
            }
        }
        if (!empty($record['context'])) {
            foreach ($record['context'] as $key => $val) {
                $message[$this->contextPrefix.$key] = $val;
            }
        }

        if (isset($message['trace']) && is_array($message['trace'])) {
            $message['trace'] = implode(PHP_EOL, $message['trace']);
        }

        return $message;
    }
}
