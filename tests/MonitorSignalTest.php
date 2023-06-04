<?php

namespace Igwen6w\MonitorSignal\Tests;

use Igwen6w\MonitorSignal\MonitorSignal;

class MonitorSignalTest
{
    public function test()
    {
        $monitor = new MonitorSignal();
        $monitor->addMonitor(SIGUSR1);
        $monitor->addMonitor(SIGINT);

        echo 'PID: '.getmypid().PHP_EOL;

        while (true) {
            if ($monitor->pullReceivedSignal(SIGUSR1)) {
                echo '接收到了SIGUSR1'.PHP_EOL;
                echo '开始休眠'.PHP_EOL;
                sleep(5);
                echo '休眠结束'.PHP_EOL;
            }
            if ($monitor->pullReceivedSignal(SIGINT)) {
                echo '你按了ctrl-c'.PHP_EOL;
                echo '进程将在10秒后结束'.PHP_EOL;
                sleep(10);
                echo '进程结束'.PHP_EOL;
                posix_kill(getmypid(),SIGHUP);
            }
            echo '业务代码'.PHP_EOL;
            sleep(1);
        }
    }
}
