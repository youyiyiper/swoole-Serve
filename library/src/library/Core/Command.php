<?php
namespace Serve\Core;

use Serve\Resque\Serve;
use Serve\Resque\Server;

class Command
{
    private $cmd = '';

    public function __construct($cmd)
    {
        //register_argc_argv
        if (ini_get('register_argc_argv')) {
            $this->cmd = $cmd[1] ?? '';
        } else {
            Logger::notice("Turn on register_argc_argv in the php.ini file.");
        }
    }

    public function execute()
    {
        Env::load();
        switch (strtolower($this->cmd)) {
            case 'start':
                if (!Server::isRunning()) {
                    Serve::run();
                } else {
                    $masterPid = Server::getMasterPid();
                    Logger::notice("Resque is running, Master pid is: {$masterPid}.");
                }
                break;
            case 'stop':
                if (Server::isRunning())
                {
                    $stop = ProcessHelper::killBySig(Server::getMasterPid(), SIGTERM);
                    if ($stop) {
                        Logger::notice("Resque has stopped.");
                    }
                    // return false  -> 没有运行
                    if (!$stop) {
                        Logger::notice("Resque is not running.");
                    }
                } else {
                    Logger::notice("Resque is not running.");
                }
                break;
            case 'reload:all':
                // 1. reload worker and task process.
                $reloadOk = Server::reloadAll();
                if ($reloadOk) {
                    Logger::notice("Task process and worker process reload succeed.");
                }
                if (!$reloadOk) {
                    Logger::notice("Resque is not running.");
                }
                break;
            case 'reload':
                if (Server::reloadTaskWorker()) {
                    Logger::notice("Task process reload succeed.");
                } else {
                    Logger::notice("Resque is not running.");
                }
                break;
            default:
                exit("Usage: php serve start|stop|reload|reload:all" . PHP_EOL);
                break;
        }
    }
}