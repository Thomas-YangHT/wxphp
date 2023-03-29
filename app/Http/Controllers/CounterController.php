<?php
// +----------------------------------------------------------------------
// | 文件: index.php
// +----------------------------------------------------------------------
// | 功能: 提供count api接口
// +----------------------------------------------------------------------
// | 时间: 2021-12-12 10:20
// +----------------------------------------------------------------------
// | 作者: rangangwei<gangweiran@tencent.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Counters;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

$help="请输入要查询的命令：\n===================\n如: ls ---返回用法链接\n";
$help.="001-<a href='https://mubu.com/doc/3xyI7zD_Yo'>CmdCheetSheet</a>\n";
$help.="002-<a href='https://mubu.com/doc/3y4NwBXCxo'>简述shell流程控制</a>\n";
$help.="003-<a href='https://mubu.com/doc/2-KsPtqeKo'>国内安装源总结</a>\n";
$help.="004-<a href='https://mubu.com/doc/2d8bwNidNo'>简述网络排障</a>\n";	
$help.="005-<a href='https://mubu.com/doc/2wPYi23fso'>简述运维操作工具</a>\n";	
$help.="006-<a href='https://mubu.com/doc/3Q14_dh4Go'>markdown文档工具</a>\n";	
$help.="007-<a href='https://mubu.com/doc/3FM8gqgzro'>简述web应用性能优化</a>\n";	
$help.="008-<a href='https://mubu.com/doc/3DOZgQxGwo'>简述微信公众号开发</a>\n";	
$help.="009-<a href='https://mubu.com/doc/3uK_TGfrXo'>解决故障[更新中]</a>\n";
$help.="010-<a href='https://mubu.com/doc/4pNs1XBo_f9'>安全扫描</a>\n";
$help.="011-<a href='https://mubu.com/doc/3Bxi3I9DCo'>Raspberry pi4使用记录</a>\n";
$help.="012-<a href='https://mubu.com/doc/1XiLnBztCo'>K8S启动盘使用帮助</a>\n";
$help.="\n待完成waiting：\n";
$help.="013-<a href=''>试用pandas分析数据</a>\n";
$help.="014-<a href=''>性能优化</a>\n";
$help.="015-<a href=''>Python实例</a>\n";
$help.="016-<a href=''>日志收集系统</a>\n";
$help.="017-<a href=''>来自编个监控系统</a>\n";
$help.="018-<a href=''>从win10到centos7</a>\n";
$help.="\nCloudMan 每天5分钟系列：\n";
$help.="<a href='https://mp.weixin.qq.com/s/7o8QxGydMTUe4Q7Tz46Diw'>[Docker教程]</a>\n";
$help.="<a href='https://mp.weixin.qq.com/s/RK6DDc8AUBklsUS7rssW2w'>[Kubernetes教程]</a>\n";
$help.="<a href='https://mp.weixin.qq.com/s/QtdMkt9giEEnvFTQzO9u7g'>[OpenStack教程]</a>\n";
$help.="\n其它：\n";
$help.="001-<a href='https://mubu.com/doc/3u65WbvQsp'>SimpleComputerWords</a>\n";		
$help.="002-<a href='https://mubu.com/doc/3mtscGgyIo'>简述测试概念与工具</a>\n";			
$help.="003-<a href='https://mubu.com/doc/LYdGMKtto'>IT架构图</a>\n";
$help.="004-<a href='https://mp.weixin.qq.com/mp/homepage?__biz=Mzg4MjAyMDgzMQ==&hid=1&sn=ce7139573c267c56ae45f026c4242045'>LinuxMan往期目录</a>\n";

class CounterController extends Controller
{
    /*
    回得消息
    */
    public function returnMsg(){

        try {
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            if (!empty($postStr)){
                // $this->logger("R \r\n".$postStr);
                Log::info("R \r\n".$postStr);

                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $RX_TYPE = trim($postObj->MsgType);
    
                // if (($postObj->MsgType == "event") && ($postObj->Event == "subscribe" || $postObj->Event == "unsubscribe")){
                //     //过滤关注和取消关注事件
                // }
    
                //消息类型分离
                switch ($RX_TYPE)
                {
                    // case "event":
                    //     $result = $this->receiveEvent($postObj);
                    //     break;
                    case "text":
                        $result = $this->receiveText($postObj);
                        break;
                    // case "image":
                    //     $result = $this->receiveImage($postObj);
                    //     break;
                    // case "location":
                    //     $result = $this->receiveLocation($postObj);
                    //     break;
                    // case "voice":
                    //     $result = $this->receiveVoice($postObj);
                    //     break;
                    // case "video":
                    // case "shortvideo":
                    //     $result = $this->receiveVideo($postObj);
                    //     break;
                    // case "link":
                    //     $result = $this->receiveLink($postObj);
                    //     break;
                    default:
                        $result = "unknown msg type: ".$RX_TYPE;
                        break;
                }
                // $this->logger("T \r\n".$result);
                Log::info("T \r\n".$result);
                echo $result;
            }else {
                echo "";
                exit;
            }
    }

       //接收事件消息
    //    private function receiveEvent($object)
    //    {
    //        $content = "";
    //        switch ($object->Event)
    //        {
    //            case "subscribe":
    //                $content = "欢迎关注LinuxMan \n";
    //                global $help;
    //                $content .= $help;
    //                if (!empty($object->EventKey)){
    //                    $content .= "\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey);
    //                }
    //                break;
    //            case "unsubscribe":
    //                $content = "取消关注";
    //                break;
    //            case "CLICK":
    //                switch ($object->EventKey)
    //                {
    //                    case "COMPANY":
    //                        $content = array();
    //                        $content[] = array("Title"=>"方倍工作室", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
    //                        break;
    //                    default:
    //                        $content = "点击菜单：".$object->EventKey;
    //                        break;
    //                }
    //                break;
    //            case "VIEW":
    //                $content = "跳转链接 ".$object->EventKey;
    //                break;
    //            case "SCAN":
    //                $content = "扫描场景 ".$object->EventKey;
    //                break;
    //            case "LOCATION":
    //                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
    //                break;
    //            case "scancode_waitmsg":
    //                if ($object->ScanCodeInfo->ScanType == "qrcode"){
    //                    $content = "扫码带提示：类型 二维码 结果：".$object->ScanCodeInfo->ScanResult;
    //                }else if ($object->ScanCodeInfo->ScanType == "barcode"){
    //                    $codeinfo = explode(",",strval($object->ScanCodeInfo->ScanResult));
    //                    $codeValue = $codeinfo[1];
    //                    $content = "扫码带提示：类型 条形码 结果：".$codeValue;
    //                }else{
    //                    $content = "扫码带提示：类型 ".$object->ScanCodeInfo->ScanType." 结果：".$object->ScanCodeInfo->ScanResult;
    //                }
    //                break;
    //            case "scancode_push":
    //                $content = "扫码推事件";
    //                break;
    //            case "pic_sysphoto":
    //                $content = "系统拍照";
    //                break;
    //            case "pic_weixin":
    //                $content = "相册发图：数量 ".$object->SendPicsInfo->Count;
    //                break;
    //            case "pic_photo_or_album":
    //                $content = "拍照或者相册：数量 ".$object->SendPicsInfo->Count;
    //                break;
    //            case "location_select":
    //                $content = "发送位置：标签 ".$object->SendLocationInfo->Label;
    //                break;
    //            case "ShakearoundUserShake":
    //                $content = "摇一摇\nUuid：".$object->ChosenBeacon->Uuid.
    //                "\nMajor：".$object->ChosenBeacon->Major.
    //                "\nMinor：".$object->ChosenBeacon->Minor.
    //                "\nDistance：".$object->ChosenBeacon->Distance.
    //                "\nRssi：".$object->ChosenBeacon->Rssi.
    //                "\nMeasurePower：".$object->ChosenBeacon->MeasurePower.
    //                "\nChosenPageId：".$object->ChosenBeacon->ChosenPageId
    //                ;
    //                break;
    //            default:
    //                $content = "receive a new event: ".$object->Event;
    //                break;
    //        }
   
    //        if(is_array($content)){
    //            $result = $this->transmitNews($object, $content);
    //        }else{
    //            $result = $this->transmitText($object, $content);
    //        }
    //        return $result;
    //    }
  
        //回复文本消息
        private function transmitText($object, $content)
        {
            if (!isset($content) || empty($content)){
                return "";
            }
    
            $xmlTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
            $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
    
            return $result;
       } 
       //接收文本消息
       private function receiveText($object)
       {
           $keyword = trim($object->Content);
           //多客服人工回复模式
        //    if (strstr($keyword, "请问在吗") || strstr($keyword, "在线客服")){
        //        $result = $this->transmitService($object);
        //        return $result;
        //    }
   
           //自动回复模式
   
                   
           if (strstr($keyword, "help") || strstr($keyword,"?")){
               global $help;
               $content = $help;
        //    }else if (strstr($keyword, "文本")){
        //        $content = "这是个文本消息";
        //    }else if (strstr($keyword, "表情")){
        //        $content = "微笑：/::)\n乒乓：/:oo\n中国：".$this->bytes_to_emoji(0x1F1E8).$this->bytes_to_emoji(0x1F1F3)."\n仙人掌：".$this->bytes_to_emoji(0x1F335);
        //    }else if (strstr($keyword, "链接")){
        //        $content = "电话号码：0755-83765566\n\n电子邮件：40012345@qq.com\n\n访问链接：<a href='http://www.qq.com/'>点此访问腾讯网</a>";
        //    }else if (strstr($keyword, "图文")){
        //        $content = array();
        //        $content[] = array("Title"=>"图文标题",  "Description"=>"图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
        //    }else if (strstr($keyword, "多图文")){
        //        $content = "微信官方已经禁用了多图文的这一消息格式。--2018年10月12日起，被动回复消息与客服消息接口的图文消息类型中图文数目只能为一条，请知悉。";
        //    }else if (strstr($keyword, "音乐")){
        //        $content = array();
        //        $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://mascot-music.stor.sinaapp.com/zxmzf.mp3", "HQMusicUrl"=>"http://mascot-music.stor.sinaapp.com/zxmzf.mp3");
              }else{
               $content = "Link1：<a href='https://jaywcjlove.gitee.io/linux-command/c/".$keyword.".html'>".$keyword."</a>\n";
               $content .= "Link2：<a href='https://www.linuxcool.com/".$keyword."'>".$keyword."</a>\n";
               $content .= "Link3：<a href='https://man.linuxde.net/".$keyword."'>".$keyword."</a>";
           }
   
           if(is_array($content)){
            //    if (isset($content[0])){
            //        $result = $this->transmitNews($object, $content);
            //    }else if (isset($content['MusicUrl'])){
            //        $result = $this->transmitMusic($object, $content);
            //    }
           }else{
               $result = $this->transmitText($object, $content);
           }
           return $result;
       }

    /**
     * 获取todo list
     * @return Json
     */
    public function getCount()
    {
        try {
            $data = (new Counters)->find(1);
            if ($data == null) {
                $count = 0;
            }else {
                $count = $data["count"];
            }
            $res = [
                "code" => 0,
                "data" =>  $count
            ];
            Log::info('getCount rsp: '.json_encode($res));
            return response()->json($res);
        } catch (Error $e) {
            $res = [
                "code" => -1,
                "data" => [],
                "errorMsg" => ("查询计数异常" . $e->getMessage())
            ];
            Log::info('getCount rsp: '.json_encode($res));
            return response()->json($res);
        }
    }


    /**
     * 根据id查询todo数据
     * @param $action `string` 类型，枚举值，等于 `"inc"` 时，表示计数加一；等于 `"reset"` 时，表示计数重置（清零）
     * @return Json
     */
    public function updateCount()
    {
        try {
            $action = request()->input('action');
            if ($action == "inc") {
                $data = (new Counters)->find(1);
                if ($data == null) {
                    $count = 1;
                }else {
                    $count = $data["count"] + 1;
                }
    
                $counters = new Counters;
                $counters->updateOrCreate(['id' => 1], ["count" => $count]);
            }else if ($action == "clear") {
                Counters::destroy(1);
                $count = 0;
            }else {
                throw '参数action错误';
            }

            $res = [
                "code" => 0,
                "data" =>  $count
            ];
            Log::info('updateCount rsp: '.json_encode($res));
            return response()->json($res);
        } catch (Exception $e) {
            $res = [
                "code" => -1,
                "data" => [],
                "errorMsg" => ("更新计数异常" . $e->getMessage())
            ];
            Log::info('updateCount rsp: '.json_encode($res));
            return response()->json($res);
        }
    }
}
