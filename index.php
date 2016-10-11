<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/7/25
 * Time: 9:48
 */
?>
<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/7/5
 * Time: 11:19
 */
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);

// 获取 post 提交数据
function post($name)
{
    $data = isset($_POST[$name]) ? $_POST[$name] : null;
    if (!empty($data) && is_string($data)) $data = trim($data);
    return $data;
}

// 判断提交数据
if (! empty($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    // 接收参数
    $host     = post('host');     // 数据库地址
    $username = post('user');     // 数据库用户名
    $password = post('pass');     // 数据库密码
    $database = post('database'); // 数据库
    $prefix   = post('prefix');   // 表前缀
	
    $arrError = [
        'status' => 0,
        'msg'    => '提交参数存在问题, 请确认填写完成',
    ];

    // 验证数据的有性
    if ($host && $username && $password && $database)
    {
        $prefix = $prefix ? $prefix : 'yii_2';
        // 验证数据库名称只能小写字母加下划线
        $arrError['msg'] = '数据库名称只能小写字母加下划线';
        if (preg_match('/^[a-z]{1,}[a-z_0-9]{1,}$/', $database))
        {
            // 开始连接数据库
            $mysql = new mysqli($host, $username, $password);
            $arrError['msg'] = '数据库连接出现问题 Error:' . $mysql->connect_error;
            if ($mysql->connect_errno == 0)
            {
                // 设置字符串
                $mysql->query('SET NAMES UTF8');

                // 选择库,没有存在执行新建库
                $mysql->select_db($database);
                if ($mysql->errno)
                {
                    $mysql->query('CREATE DATABASE `'.$database.'`');
                    $mysql->select_db($database);
                }

                // 没有错误
                $arrError['msg'] = '数据库操作出现问题 Error:' . $mysql->error;
                if (empty($mysql->errno))
                {
                    // 检查表信息
                    $result   = $mysql->query('SHOW TABLES');
                    $arrTable = [$prefix.'admin', $prefix.'auth_item', $prefix.'auth_item_child', $prefix.'assignment', $prefix.'rule',  $prefix.'menu'];
                    $strError = '';
                    if ($result)
                    {
                        while ($row = $result->fetch_row()) {
                            if (in_array($row[0], $arrTable)) $strError .= ' 数据表('.$row[0].')已经存在; ';
                        }

                        $result->free();
                    }

                    // 没有错误
                    $arrError['msg'] = $strError;
                    if (empty($strError))
                    {
                        set_time_limit(0);
                        // 执行数据库操作
                        $mysql->multi_query(str_replace('yii2_', $prefix, file_get_contents('./yii2.sql')));
                        do {
                            $result = $mysql->store_result();
                            if ($result) $result->free();
                        } while ($mysql->next_result());
                        if ($mysql->error == 0)
                        {
                            // 修改配置文件
                            $strConfig = <<<HTML
<?php
return [
    'components' => [
        'db' => [
            'class'       => 'yii\db\Connection',
            'dsn'         => 'mysql:host=localhost;dbname={$database}',
            'username'    => '{$username}',
            'password'    => '{$password}',
            'charset'     => 'utf8',
            'tablePrefix' => '{$prefix}',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
HTML;
                            // 修改配置文件内容
                            file_put_contents('./common/config/main-local.php', $strConfig);

                            // 修改文件名
                            // rename('./index.php', './install.log');

                            // 信息返回
                            $arrError = [
                                'status' => 1,
                                'msg'    => '安装成功'
                            ];
                        }
                    }
                }
            }
        }
    }

    exit(json_encode($arrError));
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yii2 Admin 管理系统安装</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />

    <!--移动优先-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--引入公共CSS文件-->
    <link rel="stylesheet" href="./backend/web/public/assets/css/bootstrap.min.css" />

    <!--引入公共js文件-->
    <script type="text/javascript" src="./backend/web/public/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="./backend/web/public/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./backend/web/public/assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="./backend/web/public/assets/js/validate.message.js"></script>
    <script type="text/javascript" src="./backend/web/public/assets/js/layer/layer.js"></script>
    <style type="text/css">
        div.main {margin-top:70px;}.error {color:red}
    </style>
</head>
<body>
<div class="container theme-showcase main" role="main">
    <div class="row">
        <div class="col-md-12">
            <h1> Yii2 Admin 管理系统安装  　<button class="btn btn-info" onclick="$('#myModal').modal('show')">安装信息</button></h1>
            <form>
                <div class="form-group">
                    <label>数据库地址</label>
                    <input type="text" class="form-control" name="host" required="true" rangelength="[2, 20]" value="127.0.0.1" placeholder="database name">
                </div>

                <div class="form-group">
                    <label>数据库用户名</label>
                    <input type="text" class="form-control"  name="user" required="true" rangelength="[2, 20]" placeholder="database user"  value="root" />
                </div>
                <div class="form-group">
                    <label>数据库密码</label>
                    <input type="password" class="form-control"  name="pass" required="true" rangelength="[2, 40]" placeholder="database Password">
                </div>
                <div class="form-group">
                    <label>数据库名</label>
                    <input type="text" class="form-control" name="database" required="true" rangelength="[2, 20]" placeholder="database name">
                </div>
                <div class="form-group">
                    <label>数据表前缀</label>
                    <input type="text" class="form-control"  name="prefix" placeholder="database table prefix" value="my_"  >
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="check" required="true"> 我同意
                    </label>
                </div>
                <button type="submit" class="btn btn-success">提交</button>
                <button type="reset" class="btn btn-default">重置</button>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">温馨提醒</h4>
            </div>
            <div class="modal-body">
                <div>
                    <p> 后台项目位于./backend </p>
                    <p> 超级管理员账号：<strong class="text-success">super</strong> </p>
                    <p> 超级管理员密码：<strong class="text-danger">admin123</strong> </p>
                    <p> SQL文件位于：<span class="text-info">./yii2.sql</span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="close-modal">好的, 我知道了</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        // 关闭modal
        $('#close-modal').click(function(){$('#myModal').modal('hide')});
        $('form').submit(function(){
            if ($(this).validate({
                    errorPlacement:function(error, errorPlacement) {
                        error.appendTo(errorPlacement.parent().addClass('has-error'));
                    },
                    success:function(label){
                        label.parent().removeClass('has-error');
                    }
                }).form()){

                // 数据请求
                var l = layer.load();
                $.ajax({
                    url:  './index.php',
                    type: 'POST',
                    data: $('form').serialize(),
                    dataType:'json',
                }).done(function(json){
                    layer.msg(json.msg, {icon:json.status == 1 ? 6 : 5, end:function(){
                        if (json.status == 1) $('#myModal').modal('show');
                    }})
                }).fail(function(){
                    layer.msg('服务器繁忙, 请求稍候再试...');
                }).always(function(){
                    layer.close(l);
                })
            }
            return false;
        })
    })
</script>
</body>
</html>
