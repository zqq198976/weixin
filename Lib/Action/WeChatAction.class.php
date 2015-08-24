<?php 
/**
 * 定义微信链接TOKEN
 	 等微信公众平台和你的服务器打通之后，只要不进行重新配置其实这个TOKEN就不需要了，下面程序也只需要直接调用responseMsg()就可以了
 */
define("TOKEN", "woshileifeng");
class WeChatAction extends Action {
 		//因为在微信公众平台和我们的服务器进行配置，点击提交按钮之后，微信会发送一个名字为echostr的随机数，所以这里可以通过是否有随机数判断用户是进行配置还是进行消息发送
    public function index() {
    	//如果用户进行配置操作，就调用验证函数
				if(isset($_GET["echostr"])){
					//这里不知道为什么一定要打印出来，不然配置不成功
					echo $_GET["echostr"];
					$this->checkSignature();
			//如果用户是进行消息发送操作，就调用回复函数
				}else{
					$this->responseMsg();
					}
    }
 		public function responseMsg(){
        //接受特殊的post数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //extract post data
        if (!empty($postStr)){
 								write_logs('receive_messages.log',date("Y-m-d H:i:s").' Receive-message: '.$postStr);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";            
                if($keyword == '你好' || $keyword == 'hello')
                {
                    $msgType = "text";
                    $contentStr = "Hello,welcome to wechat world!";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    echo "Input something...";
                }
        }else {
            echo "";
            exit;
        }
    }
    private function checkSignature() {
    		//签名
        $signature = $_GET["signature"];
        //时间戳
        $timestamp = $_GET["timestamp"];
        //随机数
        $nonce = $_GET["nonce"];
 				//TOKEN
        $token = TOKEN;
        //将时间戳，随机数和TOKEN相结合成一个数组
        $tmpArr = array($token, $timestamp, $nonce);
        //对这个数组进行排序
        sort($tmpArr, SORT_STRING);
        //再讲排序得到的数组合并成一个字符串
        $tmpStr = implode($tmpArr);
        //将合并之后的字符串进行加密
        $tmpStr = sha1($tmpStr);
 				//将加密之后的字符串和接收到的签名串进行比较
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}
?>
