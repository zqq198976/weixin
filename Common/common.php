<?php
//д����־
function write_logs($path,$contents){
	$logsRootPaths = C('SELF_LOG_PATH');
	$destination = $logsRootPaths.ltrim($path,'/');
	$logPath = dirname($destination);
	!is_dir($logPath) && mkdir($logPath, 0777, true);
	//�����־�ļ���С���������ô�С�򱸷���־�ļ���������
	if(is_file($destination) && floor(C('LOG_FILE_SIZE')) <= filesize($destination)){
		rename($destination,dirname($destination).'/'.date('Ymd').'-'.basename($destination));
		}
		error_log($contents."\n",3,$destination);
	}