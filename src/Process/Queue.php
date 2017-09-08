<?php
    /**
     * @author    jan huang <bboyjanhuang@gmail.com>
     * @copyright 2017
     *
     * @see      https://www.github.com/janhuang
     * @see      http://www.fast-d.cn/
     */
    
    namespace FastD\Process;
    
    
    use FastD\Server;
    use FastD\Swoole\Queue as Q;
    use swoole_process;
    
    /**
     * Class Queue
     * @package FastD\Process
     */
    class Queue extends Q
    {
        public function handle(swoole_process $process)
        {
            try {
                $server = server();
            } catch (\Exception $e) {
                //            $server = new Server();
            }
            
            while (true) {
                $recv = $process->pop();
                $process->write($recv);
            }
        }
    }