FAQ
===

以下整理了一些常见的问题，在提问之前建议找找有没有类似的问题。

## 一 `composer` 安装问题(安装慢、或者有错误)

1. 确认安装了[Composer Asset插件](https://github.com/fxpio/composer-asset-plugin)
2. `composer` 使用[中国镜像](https://pkg.phpcomposer.com/)
3. 不使用`composer`,直接下载打包文件
   * [百度网盘](https://pan.baidu.com/s/1frc7FxxL1Pkf2dd06m0tlA)
   * [有道云笔记](https://note.youdao.com/ynoteshare1/index.html?id=4e1e59dd2ec2541796105d4d7afdb3c9)
   * [CSDN](https://download.csdn.net/download/myliujx/11193963)

## 二 连表操作怎么办？

在控制器中重写 `getQuery`方法

```php
public function getQuery($where)
{
    return Admin::find()->select(['*'])
                ->innerJoin('admin_operate_logs', 'admin.id = admin_operate_logs.admin_id')
                ->where($where)
                ->asArray()
}
```

能使用关联查询的请使用关联

```php
public function getQuery($where)
{
    return Customer::find()->with('orders', 'country')->where($where)->asArray()
}
```

## 三 怎么将请求参数传递到`search`方法的查询条件?

1. 控制器`actionIndex`方法接收请求参数,传递到视图(或者直接在视图里面接收)

    ```php
    public function actionIndex()
    {
        $id = Yii::$app->request->get('id');
        return $this->render('index', compact('id'))
    }
    ```

2. 视图文件中，配置`meTables`的附加参数

    ```php
    var m = meTables({
        params: {
            id: <?php echo isset($id) ? $id : 'null' ?>,
        }
    })
    ```
    
## 四 前端`meTables`配置相关问题

### 4.1 视图怎么控制创建和修改表单不一致?

通过 `meTables` 的`beforeShow` 方法实现，控制某个`input`的显示和隐藏

```js
var m = meTables({
    table: {
        columns: [
            {
                title: "用户名称",
                data: "username",
                edit: {
                    id: "username"
                }
            }
        ]
    }
});

// 用户名字段在创建的时候显示出来，修改的时候隐藏起来
$.extend(m, {
    beforeShow: function() {
        if (this.action === "update") {
            $("#username").attr('disable', true).hide();
        } else  {
            $("#username").attr('disable', false).show();
        }
    }
});
```

### 4.2 操作项指定按钮在特定情况下显示

配置指定按钮的`show`方法

`show`函数接受一个参数`rows`(这一行的数据)，需要返回`true`来确定显示

```js
var m = meTables({
    operations: {
        buttons: {
            // 配置show，根据情况显示删除 rows 表示这一行的数据
            delete: {
                show: function (rows) {
                    return rows.status == 1;
                }
            }
        }
    }
})
```

[更多`meTabls`说明>>](./metables.md)
