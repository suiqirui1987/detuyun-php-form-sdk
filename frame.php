<?php
/// (回调中的所有信息均为 UTF-8 编码，签名验证的时候需要注意编码是否一致)
$bucket = 'pubimgs'; /// 空间名
$form_api_secret = 'xxxxxx'; /// 表单 API 功能的密匙（请访问得图云管理后台首页的AccessKey管理页面获取）

$options = array();
$options['bucket'] = $bucket; /// 空间名
$options['expiration'] = time()+600; /// 授权过期时间
$options['save-key'] = '/{year}/{mon}/{random}{.suffix}'; /// 文件名生成格式，请参阅 API 文档
$options['allow-file-type'] = 'jpg,jpeg,gif,png'; /// 控制文件上传的类型，可选
$options['content-length-range'] = '0,1024000'; /// 限制文件大小，可选
$options['image-width-range'] = '100,1024000'; /// 限制图片宽度，可选
$options['image-height-range'] = '100,1024000'; /// 限制图片高度，可选
$options['return-url'] = 'http://localhost/form-test/agent.html'; /// 页面跳转型回调地址 !!! iframe 回调地址，注意客户网站上要部署 agent.html 进行跨域代理
//$options['notify-url'] = 'http://a.com/form-test/notify.php'; /// 服务端异步回调地址, 请注意该地址必须公网可以正常访问

$policy = base64_encode(json_encode($options));
$sign = md5($policy.'&'.$form_api_secret); /// 表单 API 功能的密匙（请访问得图云管理后台首页的AccessKey管理页面获取）

?>
<iframe src="#" name="upload-frame" style="width:0;height:0;border:0;"></iframe>
<script>
function dump(c,d){var a="";d||(d=0);for(var e="",b=0;b<d+1;b++)e+="    ";if("object"==typeof c)for(var f in c)b=c[f],"object"==typeof b?(a+=e+"'"+f+"' ...\n",a+=dump(b,d+1)):a+=e+"'"+f+"' => \""+b+'"<br/>';else a="===>"+c+"<===("+typeof c+")";return a};

////// !!! 必要函数 !!! //////
function upload_callback(r){ /// 表单上传完成后将回调此方法。
	/// 注意：如果在上传过程中遇到无法捕捉的错误，将无法触发到改方法（建议加一个 setTimeout 的方法来捕捉超时）
	/// 如需验证回调的信息合法性，可把 agent.html 改为使用 PHP 类程序页面
	console.log(r);
	document.getElementById('upload-form').innerHTML = dump(r);
}</script>

<form action="http://api.detuyun.com/" method="post" enctype="Multipart/form-data" target="upload-frame" id="upload-form">
	<input type="hidden" name="policy" value="<?php echo $policy?>">
	<input type="hidden" name="signature" value="<?php echo $sign?>">
	<input type="file" name="file">
	<input type="submit">
</form>