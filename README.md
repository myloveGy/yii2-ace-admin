Yii2 ace Admin 后台模板
=======================

### 简介
系统基于yii2高级版本开发，后台模板使用的ace admin。对于一般的后台开发，比较方便; 对于数据表的CURL操作都有封装，且所有操作都有权限控制。
#### 特点
* 使用RBAC权限管理，所有操作基于权限控制
* 视图使用JS控制，数据显示使用的jquery.DataTables
* 基于数据表的增、删、改、查都有封装，添加新的数据表操作方便
### 安装要求
* PHP >= 5.4
* MySQL
### 项目demo
* 地址： [http://yii2.sh-jinger.com](http://yii2.sh-jinger.com)
* 账号： admin
* 密码： admin888
### 安装
* 提示：请先确定安装了[Composer Asset插件:](https://github.com/fxpio/composer-asset-plugin)
```
php composer.phar global require "fxp/composer-asset-plugin:^1.2.0"
```

1. 执行 composer 安装项目
        
    ```
    php composer create-project liujx/yii2-app-advanced
    ```

2. 执行该目录下的 init 初始化配置（生成本地配置文件）

3. 配置好数据库配置后,导入数据表结构

需要顺序执行
* 导入rbac migration 权限控制数据表
    ```
    php yii migrate --migrationPath=@yii/rbac/migrations
    ``` 
* 导入admin migration 后台基础数据
    ```
    php yii migrate 
    ```

或者执行安装文件

1. 浏览器进入该目录的下执行index.php （项目根目录下的index.php）进行数据库数据的导入

2. 配置虚拟机,设置路径为 bacekend/web/ 下，配置好路由重写 

* 后台默认超级管理员账号：super 密码：admin123
* 管理员账号：admin 密码：admin888
### 使用说明

基本操作的权限(以管理员操作为例)：

* admin/index       (显示管理员页面 + 左侧导航显示)
* admin/search      (管理员数据显示表格数据显示)
* admin/create      (添加管理员信息)
* admin/update      (修改管理员信息)
* admin/delete      (删除管理员信息)
* admin/delete-all  (批量删除管理员数据)
* admin/upload      (上传管理员头像)
* admin/export      (管理员数据信息导出)

每一个请求对应一个权限，请求路径就是权限名称，权限验证在Controller beforeAction 方法中验证

1. 后台控制器配置
    ```php
    namespace backend\controllers;
    
    /**
     * Class ChinaController
     * @package backend\controllers
     */
    class ChinaController extends Controller 
    {
        /**
         * @var string 定义使用的model
         */
        protected $modelClass = '\common\models\China';
            
        /**
         * 处理查询信息(主要查询、数据导出时候使用)
         * @param array $params 查询时候请求的参数信息（一个数组）
         * @return array 需要返回一个数组
         */
        public function where($params)
        {
            /**
             * 数组配置说明
             * where 配置默认查询条件,没有可以不用填写
             * 键对应查询字段
             * 值对应查询配置处理
             * 字符串 'pid' => '=' 处理为 model 查询数组 ['=', 'pid', '查询数值']
             * 数组 'id' => [
             *           'and' => '=',       // 查询类型(默认=)， 其他（>=, 'like', '<=', ...）
             *           'func' => 'intval'  // 对查询数值的处理函数，一般如果是时间查询转时间戳比较好用
             *           // 'field' => 'cid', // 改变查询的字段
             *      ]
             * 匿名函数 'name' => function($value) {
             *   return ['like', 'name', trim($value)];
             * }
             * @param string $value 查询的值
             * @return array 需要返回一个数组
             */
            return [
                'where' => [['=', 'status', 1]], // 默认查询条件(查询数组),没有不用填写
                'id' => ['and' => '=', 'func' => 'intval'],
                'name' => function($value) {
                    return ['like', 'name', trim($value)];
                },
                'pid'  => '='
            ];
            
            // 该段配置最终会处理为model 查询的where 条件数组(只有在查询值有效，不为空的情况下，对应字段的查询才会加上)
            // $model->find()->where(['and', ['=', 'id', '查询值'], ['like', 'name', '查询值'], ['=', 'pid', '查询值']])
        }
    }
    ```
2. 后台model
    使用gii生成model，命名空间 backend\models

3. 视图文件JS配置
    ```js
        var arrParent = {"0": "中国", "1": "湖南"};
        /**
         * 简单配置说明
         * title 配置表格名称
         * table DataTables 的配置 
         * --- aoColumns 中的 value, search, edit, defaultOrder, isHide 是 meTables 的配置
         * ------ value 为编辑表单radio、select, checkbox， 搜索的表单的select 提供数据源,格式为一个对象 {"值": "显示信息"}
         * ------ search 搜索表单配置(不配置不会生成查询表单), type 类型支持 text, select 其他可以自行扩展
         * ------ edit 编辑表单配置（不配置不会生成编辑表单）, 
         * --------- type 类型支持hidden, text, password, file, radio, select, checkbox, textarea 等等 
         * --------- meTable.inputCreate 等后缀函数为其生成表单元素，可以自行扩展
         * --------- 除了表单元素自带属性，比如 required: true, number: true 等为 jquery.validate.js 的验证配置
         * --------- 最终生成表单元素 <input name="name" required="true" number="true" />
         * ------ defaultOrder 设置默认排序的方式(有"ace", "desc")
         * ------ isHide 该列是否需要隐藏 true 隐藏
         * 其他配置查看 meTables 配置
         */
        
        // 自定义表单处理方式
        meTables.extend({
            /**
             * 定义编辑表单(函数后缀名Create)
             * 使用配置 edit: {"type": "email", "id": "user-email"}
             * edit 里面配置的信息都通过 params 传递给函数
             */
            "emailCreate": function(params) {
                return '<input type="email" name="' + params.name + '"/>';
            },
            
            /**
             * 定义搜索表达(函数后缀名SearchCreate)
             * 使用配置 search: {"type": "email", "id": "search-email"}
             * search 里面配置的信息都通过 params 传递给函数
             */
            "emailSearchCreate": function(params) {
                return '<input type="text" name="' + params.name +'">';
            }
        });
        
        var m = meTables({
            title: "地址信息",
            table: {
                "aoColumns":[
                    {"title": "id", "data": "id", "sName": "id",  "defaultOrder": "desc",
                        "edit": {"type": "text", "required":true,"number":true}
                    },
                    {"title": "地址名称", "data": "name", "sName": "name",
                        "edit": {"type": "text", "required": true, "rangelength":"[2, 40]"},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {"title": "父类ID", "data": "pid", "sName": "pid", "value": arrParent,
                        "edit": {"type": "text", "required": true, "number": true},
                        "search": {"type":"select"}
                    }
                ]
            }
        });
    
    
        $(function(){
            m.init();
        })
    ```
[meTables配置说明](./backend/web/public/assets/js/common/README.md)

### 预览
1. 登录页
![登录页](./backend/web/public/assets/images/desc1.png)
2. 数据显示
![数据显示](./backend/web/public/assets/images/desc2.png)
3. 权限分配
![权限分配](./backend/web/public/assets/images/desc3.png)
4. 模块生成
![模块生成](./backend/web/public/assets/images/desc4.png)

目录结构
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```
