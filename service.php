<?php
        $file=__DIR__."/pid.txt";
        if(file_exists($file)){
                $pid=file_get_contents($file);
                unlink($file);
        }
        $forceStop=1;
        if(!empty($pid)){
                shell_exec("kill {$pid}");
                for($i=0;$i<20;$i++){
                        usleep(10000);
                        $out=array();
                        exec("ps -p {$pid}",$out);
                        if(count($out)==1){
                                $forceStop=0;
                                break;
                        }
                }
                sleep(1);
        }
        if(!empty($forceStop)){
                $app=__DIR__.'/a.php';
                shell_exec("ps -ef | grep \"$app\" | awk '{print $2}' | xargs kill -9");
        }
        $pid=shell_exec('nohup /app/bin/php '.__DIR__.'/a.php >> /dev/null 2>&1  & echo $!');
        file_put_contents($file,$pid);

