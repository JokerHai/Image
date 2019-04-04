# Image
PHP图片处理类，可以保存线上图片到本地、图片添加水印、制作海报。

使用方法(TP5.0框架为例)：

1.引入类库

Vendor('hsk99.image.Image');

2.实例化类

$this->image = new \Image();

3.保存线上图片到本地

$this->image->save_image($url);

注：$url 为线上图片的地址

4.处理图片

$this->image->create_images($bg_img_src,$title = [],$img = []);

注：$bg_img_src 背景图路径（不能为空）,$title 为文字配置（二维数组  可以为空）,$img 为图片配置（二维数组  可以为空）

$bg_img_src 背景图 例：$bg_img_src =  "./bg.png";

$title  文字配置 [文字内容 X坐标 Y坐标 字体大小 字体角度 字体颜色 字体文件路径]

例：$title[] = ['title'=>"你好",'x'=>$name_x,'y'=>600,'size'=>"60",'angle'=>"0",'color'=>"200,200,200",'fonts'=>"./hyxkj.ttf"];

$img  图片配置 [图片路径 X坐标 Y坐标 图片宽度 图片高度 透明度(0-100)]

例：$img[] = ['src'=>"./img.png",'x'=>350,'y'=>240,'width'=>100,'height'=>100,'opacity'=>100];
