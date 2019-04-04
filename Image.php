<?php

/**
 * @Author   HSK
 * @Email    3024186605@qq.com
 * @DateTime 2019-04-04
 */

class Image
{

    /**
     * @Author   HSK
     * @Email    3024186605@qq.com
     * @DateTime 2019-04-04
     * @param    $url       线上图片地址
     * 保存线上图片到本地
     */
    public function save_image($url)
    {
        if(empty($url)){
            return false;
        }

        // 获取线上图片数据流
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data_flow = curl_exec($ch);
        curl_close($ch);
        // 判断文件夹是否存在
        if (!is_dir("./public/uploads/WePictures/".date("Ymd")."/")) {
            mkdir("./public/uploads/WePictures/".date("Ymd")."/", 0755, true);
        }
        // 存储图片
        $img_url = "./public/uploads/WePictures/".date("Ymd")."/".md5(microtime(true)).".png";
        if(file_put_contents($img_url,$data_flow)){
            // 去除路径前面的点
            $img_url = substr($img_url,1);
            return $img_url;
        }else{
            return false;
        }
    }

    /**
     * @Author   HSK
     * @Email    3024186605@qq.com
     * @DateTime 2019-04-04
     * @param    $bg_img_src        背景图路径
     * @param    $title             文字配置
     * @param    $img               图片配置
     * 处理图片
     */
    public function create_images($bg_img_src,$title = [],$img = [])
    {
        if(empty($bg_img_src)){
            return false;
        }
        /*背景图*/
        // 获取图像信息
        $bg_img_info = getimagesize($bg_img_src);
        // 载入图片
        $bg_img_source_mime = $bg_img_info['mime'];
        switch ($bg_img_source_mime)
        {
            case 'image/gif':
                $bg_img = imagecreatefromgif($bg_img_src);
                break;
            case 'image/jpeg':
                $bg_img = imagecreatefromjpeg($bg_img_src);
                break;
            case 'image/png':
                $bg_img = imagecreatefrompng($bg_img_src);
                break;
            default:
                return false;
                break;
        }
        
        /*添加文字*/
        if(!empty($title)){
            foreach ($title as $v) {
                // 字体颜色
                list($R,$G,$B) = explode(',', $v['color']);
                $white = imagecolorallocate($bg_img,$R,$G,$B);
                // 处理图片 [图片路径 字体大小 字体角度 X坐标 Y坐标 字体颜色 字体文件路径 文字内容]
                imagettftext($bg_img, $v['size'], $v['angle'], $v['x'], $v['y'], $white, $v['fonts'], $v['title']);
            }
        }
        

        /*添加图片*/
        if(!empty($img)){
            foreach ($img as $v) {
                // 图片路径
                $src = $v['src'];
                // 获取图像信息
                $src_info = getimagesize($src); 
                // 载入图片
                $src_img_source_mime = $src_info['mime'];
                switch ($src_img_source_mime)
                {
                    case 'image/gif':
                        $src_img = imagecreatefromgif($src);
                        break;
                    case 'image/jpeg':
                        $src_img = imagecreatefromjpeg($src);
                        break;
                    case 'image/png':
                        $src_img = imagecreatefrompng($src);
                        break;
                    default:
                        return false;
                        break;
                }

                // 创建一个新画布,用于存放新图片
                $src_img_thumb = imagecreatetruecolor($v['width'], $v['height']);
                // 缩放图片 [新图片 原图片 新X坐标 新Y坐标 原X坐标 原Y坐标 新图片宽 新图片高 原图片宽 原图片高]
                imagecopyresampled($src_img_thumb, $src_img, 0, 0, 0, 0, $v['width'], $v['height'], $src_info[0], $src_info[1]);
                // 处理图片 [背景图 水印图 背景图X坐标(水印图在背景图上的坐标) 背景图Y坐标(水印图在背景图上的坐标) 水印图X坐标(根据坐标取水印图的部分) 水印图Y坐标(根据坐标取水印图的部分) 水印图宽度 水印图高度 水印图透明度]
                imagecopymerge($bg_img,$src_img_thumb,$v['x'],$v['y'],0,0,$v['width'],$v['height'],$v['opacity']);
            }
        }

        /*生成新图片*/
        header('Content-type:image/png');
        // 判断文件夹是否存在
        if (!is_dir("./public/uploads/Picture/".date("Ymd")."/")) {
            mkdir("./public/uploads/Picture/".date("Ymd")."/", 0755, true);
        }
        // 图片保存路径
        $url = "./public/uploads/Picture/".date("Ymd")."/".md5(microtime(true)).".png";
        // 保存图片
        $res = ImagePng($bg_img,$url);
        // 销毁图像
        ImageDestroy($bg_img);
        if(!$res) return false;
        // 去除路径前面的点
        $url = substr($url,1);

        return $url;
    }
}