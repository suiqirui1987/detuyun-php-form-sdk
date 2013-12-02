


此 SDK 适用于 PHP 5.1.0 及其以上版本。基于得图云存储HTTP REST API接口 构建。使用此 SDK 构建您的网络应用程序，能让您以非常便捷地方式将数据安全地存储到得图云存储上。无论您的网络应用是一个网站程序，还是包括从云端（服务端程序）到终端（手持设备应用）的架构的服务或应用，通过得图云存储及其 SDK，都能让您应用程序的终端用户高速上传和下载，同时也让您的服务端更加轻盈。




- [应用接入](#install)
	- [获取Access Key 和 Secret Key](#acc-appkey)
- [使用说明](#detuyun-api)
	- [1 初始化DetuYun](#detuyun-init)
	- [2 上传文件](#detuyun-upload)
	- [3 Signature 签名](#detuyun-down)
	- [4 同步回调](#detuyun-createdir)
	- [5 异步回调](#detuyun-deletedir)

- [异常处理](#detuyun-exception)


<a name="install"></a>
## 应用接入

<a name="acc-appkey"></a>

### 1. 获取Access Key 和 Secret Key

要接入得图云存储，您需要拥有一对有效的 Access Key 和 Secret Key 用来进行签名认证。可以通过如下步骤获得：

1. <a href="http://www.detuyun.com/user/accesskey" target="_blank">登录得图云开发者自助平台，查看 Access Key 和 Secret Key 。</a>

<a name=detuyun-api></a>
## 使用说明

<a name="detuyun-init"></a>
### 1.设置初始参数


	$bucket = 'abcdd'; 
	$form_api_key = "faith196";
	$form_api_secret = 'fhx442gh1n1qmeuqyvmtf5nt2uk482'; 


参数`bucket`为空间名称，`form_api_key`为Access Key，`form_api_key`为Access Secret，即表单API功能的密钥，您可以在得图云管理后台首页的AccessKey管理页面获取。


<a name="detuyun-upload"></a>
### 2. 上传文件
表单文件上传到得图云存储时，需要告知该文件需要怎么处理，以及最终的保存路径等。所以表单在上传文件的同时，可以挑选我们提供的可选参数，自由搭配出符合自身业务逻辑的参数集，做成 policy 并传递给得图云存储。
policy 内容可以通过以下三个步骤获得：

* 自由挑选所需的参数，做成参数集

* 将 key-value 的参数集转换成 json 格式的字符串

* 将 json 格式的字符串(无换行)进行 base64 处理
 
如

	$options = array();
	$options['bucket'] = $bucket; 
	$options["access_key"]="faith196";
	$options['expiration'] = time()+600; 
	$options['save_name'] = '/{year}/{mon}/{random}{.suffix}'; 
       ...
	$policy = base64_encode(json_encode($options));

参数`expiration`表示授权过期时间，`save_name`表示文件名生成格式，具体请参阅表单API文档的<a href="http://www.detuyun.com/docs/form2.html" target="_blank"> Policy详解 </a>。

<a name=detuyun-down></a>
### 3. Signature 签名
回调中的所有信息均为 UTF_8 编码，签名验证的时候需要注意编码是否一致。

	$sign = md5($policy.'&'.$form_api_secret); 

参数`form_api_secret`为表单 API 验证密匙，可访问得图云管理后台的AccessKey管理页面获取。客户端在文件上传时，需要使用“表单 API 验证密匙”，计算出一个唯一的签名值，并将该值传递到得图云存储。得图云服务器在接收到请求的第一时间里，在服务器端以同样的计算方式计算出签名值，用来验证当前的请求是否有效。若传递过来的签名值与得图云服务端计算的签名值不匹配，则视为错误请求并返回“签名错误”的消息。

<a name=detuyun-createdir></a>
### 4. 同步回调
如果没有设置 `return-url` 同步回调参数，那么得图云存储处理完上传操作后，将把结果信息返回输出到 body 中；如果设置了 `return-url` 同步回调参数，那么得图云存储处理完上传操作后，将会使用 http 302 的方式自动跳转到用户指定的 URL。

	$options['return_url'] = 'http://api.detuyun.com/sdk/php-form-sdk/return.php';

页面跳转型回调地址。URL 中包括：`code`、`message`、`url`、`time`、`sign`(或 `non-sign`) 、`image-width`、`image-height`、`image-frames` 和` image-type` 参数。

<a name=detuyun-deletedir></a>
### 5. 异步回调
如果设置了 `notify-url` 异步回调参数，那么得图云存储处理完上传操作后，服务端将通过 POST 的方式把上传结果回调到用户所指定的URL，


	$options['notify_url'] = 'http://api.detuyun.com/sdk/php-form-sdk/notify.php';

服务端异步回调地址, 请注意该地址必须公网可以正常访问。回调地址中包括：`code`、`message`、`url`、`time` 、 `sign`(或 `non-sign`) 、`image-width`、`image-height`、`image-frames` 和 `image-typ`。

<a name=detuyun-exception></a>
## 异常处理
当API请求发生错误时，SDK将抛出异常，具体错误代码请参考 <a target="_blank"  href="http://www.detuyun.com/docs/form6.html">表单API错误代码表</a>

根据返回HTTP CODE的不同，SDK将抛出以下异常：

* **DetuYunAuthorizationException** 401，授权错误
* **DetuYunForbiddenException** 403，权限错误
* **DetuYunNotFoundException** 404，文件或目录不存在
* **DetuYunNotAcceptableException** 406， 目录错误
* **DetuYunServiceUnavailable** 503，系统错误

未包含在以上异常中的错误，将统一抛出 `DetuYunException` 异常。
