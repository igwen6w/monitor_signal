<?php

namespace Igwen6w\MonitorSignal;

class MonitorSignal
{
    // 已经监听到的信号
    public array $received_signals = [];

    public function __construct()
    {
        // 开启异步监听
        pcntl_async_signals(true);
    }

    //

    /**
     * 添加监听
     *
     * @param int $signal
     * @return bool
     */
    public function addMonitor(int $signal): bool
    {
        return pcntl_signal($signal, function () use ($signal) {
            $this->addReceivedSignals($signal);
        });
    }

    /**
     * 获取已监听列表
     *
     * @return array
     */
    public function getReceivedSignals(): array
    {
        return $this->received_signals;
    }

    //

    /**
     * 拉取一次,返回 true 表示已监听到此信号，然后将此信号移除已监听列表
     *
     * @param int $signal
     * @return bool
     */
    public function pullReceivedSignal(int $signal): bool
    {
        if ($this->hasReceivedSignal($signal)) {
            $this->removeReceivedSignal($signal);
            return true;
        }
        return false;
    }


    /**
     * 是否已监听到某个信号
     *
     * @param int $signal
     * @return bool
     */
    public function hasReceivedSignal(int $signal): bool
    {
        return in_array($signal, $this->getReceivedSignals());
    }

    //

    /**
     * 从已监听列表中移除某信号
     *
     * @param int $signal
     * @return bool
     */
    public function removeReceivedSignal(int $signal): bool
    {
        foreach ($this->received_signals as $k => $v) {
            if ($v === $signal) {
                unset($this->received_signals[$k]);
                return true;
            }
        }
        return false;
    }


    /**
     * 追加信号到已监听列表
     *
     * @param int $signal
     * @return void
     */
    protected function addReceivedSignals(int $signal): void
    {
        $this->received_signals[] = $signal;
    }

    /**
     * @param array $received_signals
     */
    protected function setReceivedSignals(array $received_signals): void
    {
        $this->received_signals = $received_signals;
    }


    /**
     * 重置已监听列表
     *
     * @return void
     */
    public function clearReceivedSignals(): void
    {
        $this->setReceivedSignals([]);
    }
}