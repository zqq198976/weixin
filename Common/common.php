<?php
//写入日志
function write_logs($path,$contents){
	$logsRootPaths = C('SELF_LOG_PATH');
	$destination = $logsRootPaths.ltrim($path,'/');
	$logPath = dirname($destination);
	!is_dir($logPath) && mkdir($logPath, 0777, true);
	//检测日志文件大小，超过配置大小则备份日志文件重新生成
	if(is_file($destination) && floor(C('LOG_FILE_SIZE')) <= filesize($destination)){
		rename($destination,dirname($destination).'/'.date('Ymd').'-'.basename($destination));
		}
		error_log($contents."\n",3,$destination);
	}