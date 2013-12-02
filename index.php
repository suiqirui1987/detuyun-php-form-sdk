<?php
/// (回调中的所有信息均为 UTF_8 编码，签名验证的时候需要注意编码是否一致)
$bucket = 'abcdd'; /// 空间名
$form_api_key = "faith196";///
$form_api_secret = 'fhx442gh1n1qmeuqyvmtf5nt2uk482'; /// 表单 API 功能的密匙（请访问得图云管理后台首页的AccessKey管理页面获取）

$options = array();
$options['bucket'] = $bucket; /// 空间名
$options["access_key"]="faith196";
$options['expiration'] = time()+600; /// 授权过期时间
$options['save_name'] = '/{year}/{mon}/{random}{.suffix}'; /// 文件名生成格式，请参阅 API 文档
$options['content_length_range'] = '0,1024000'; /// 限制文件大小，可选
$options['image_width_range'] = '100,1024000'; /// 限制图片宽度，可选
$options['image_height_range'] = '100,1024000'; /// 限制图片高度，可选
$options['return_url'] = 'http://api.detuyun.com/sdk/php-form-sdk/return.php'; /// 页面跳转型回调地址
$options['notify_url'] = 'http://api.detuyun.com/sdk/php-form-sdk/notify.php'; /// 服务端异步回调地址, 请注意该地址必须公网可以正常访问

$policy = base64_encode(json_encode($options));
$sign = md5($policy.'&'.$form_api_secret); /// 表单 API 功能的密匙（请访问得图云管理后台首页的AccessKey管理页面获取）

?>
<form action="http://api.detuyun.com/" method="post" enctype="Multipart/form-data">
	<input type="hidden" name="postdata" value="<?php echo $policy?>">
	<input type="hidden" name="signature" value="<?php echo $sign?>">
	<input type="file" name="file">
	<input type="submit">
</form>