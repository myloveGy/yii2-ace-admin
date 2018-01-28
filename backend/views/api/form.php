<?php

// 注入需要的JS
$this->registerJsFile('@web/public/assets/js/jquery.validate.min.js');
$this->registerJsFile('@web/public/assets/js/validate.message.js');
$this->registerJsFile('@web/assets/apiform.js');

?>
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" id="api-form" role="form" method="post" action="/api/create">
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right"><h4>基本信息</h4></label>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right" for="form-field-1-1">接口名称</label>

                <div class="col-sm-10">
                    <input type="text" required="true" name="summary" placeholder="接口名称" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right" for="form-field-1-1">请求地址</label>

                <div class="col-sm-10">
                    <input type="text" name="url" required="true" placeholder="请求url" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right" for="form-field-1-1">接口版本</label>

                <div class="col-sm-10">
                    <input type="text" name="version" required="true"  placeholder="version" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right">所属模块</label>
                <div class="col-sm-4">
                    <select class="form-control" required="true" name="tags">
                        <option value=""></option>
                        <option value="用户">用户</option>
                        <option value="文件">文件</option>
                    </select>
                </div>
                <label class="col-sm-1 control-label no-padding-right">是否弃用</label>
                <div class="col-sm-4">
                    <select class="form-control" name="status">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right">请求方式</label>
                <div class="col-sm-4">
                    <select class="form-control" name="method">
                        <?php foreach ($methods as $value): ?>
                        <option value="<?=$value?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <label class="col-sm-1 control-label no-padding-right">请求协议</label>
                <div class="col-sm-4">
                    <select class="form-control" name="schemes">
                        <?php foreach ($schemelist as $value): ?>
                            <option value="<?=$value?>"><?=$value?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right">请求格式</label>
                <div class="col-sm-4">
                    <select class="form-control" name="consumes">
                        <option value="application/json">application/json</option>
                        <option value="application/x-www-form-urlencoded">application/x-www-form-urlencoded</option>
                        <option value="multipart/form-data">multipart/form-data</option>
                    </select>
                </div>
                <label class="col-sm-1 control-label no-padding-right">响应格式</label>
                <div class="col-sm-4">
                    <select class="form-control" name="produces">
                        <option value="application/json">application/json</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right">开发状态</label>
                <div class="col-sm-4">
                    <select class="form-control" name="dev_status">
                        <option value="0">不显示</option>
                        <option value="1">开发中</option>
                        <option value="2">开发完成</option>
                    </select>
                </div>
                <label class="col-sm-1 control-label no-padding-right">责任人</label>
                <div class="col-sm-4">
                    <input type="text" name="worker"  placeholder="负责人" class="form-control" />
                </div>
            </div>

            <div class="space-4"></div>
            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right">描述信息</label>
                <div class="col-sm-10">
                    <textarea class="form-control" required="true" name="description"  placeholder="描述信息"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right"><h4>请求参数</h4></label>
            </div>
            <div>
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <table class="table">
                            <thead>
                            <th>操作</th>
                            <th>字段</th>
                            <th>参数位置</th>
                            <th>类型</th>
                            <th>是否必需</th>
                            <th>描述</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" name="parameters" id="parameters_json">
            <div id="parameters_data">
                <div class="form-group">
                    <label class="col-sm-1 control-label no-padding-right"></label>
                    <div class="col-sm-1">
                        <i style="border: none;margin-left: -22px;" class="ace-icon fa fa-trash-o bigger-130 trash_request form-control"></i>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" class="form-control ziduan" />
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control weizhi">
                            <option value="formData">formData</option>
                            <option value="path">path</option>
                            <option value="query">query</option>
                            <option value="body">body</option>
                            <option value="header">header</option>
                            <option value="cookie">cookie</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control leixin">
                            <option value="integer">integer</option>
                            <option value="long">long</option>
                            <option value="float">float</option>
                            <option value="double">double</option>
                            <option value="string">string</option>
                            <option value="byte">byte</option>
                            <option value="binary">binary</option>
                            <option value="boolean">boolean</option>
                            <option value="date">date</option>
                            <option value="dateTime">dateTime</option>
                            <option value="password">password</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select class="form-control required" >
                            <option value="true">是</option>
                            <option value="false">否</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control miaoshu" />
                    </div>
                </div>
            </div>
            <div class="form-group parameters">
                <label class="col-sm-1 control-label no-padding-right"></label>
                <div class="col-sm-10">
                    <button style="border: none;margin-left: 0px;" type="button" class="btn btn-success">
                        <i class="fa fa-plus"></i> 新增
                    </button>

                    <button style="border: none;" type="button" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i> 清空
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right"><h4>请求响应</h4></label>
            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label no-padding-right">默认</label>
                <div class="col-sm-10">
                    <input type="text" name="responses" readonly class="form-control" value='{"default": {"description": "操作成功"}}' />
                </div>
            </div>

            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-info" type="button">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        提交
                    </button>
                    <button class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        重置
                    </button>
                </div>
            </div>
            <div class="hr hr-24"></div>
        </form>
    </div>
</div>
<?php $this->beginBlock('javascript') ?>
<script>
/*    var $form  = $('#api-form');
    $form.submit(function(){
        if ($(this).validate(validatorError).form()) {
            alert();
        }
        else{
            return;
        }
        alert();
    });*/
</script>
<script type="text/template" id="html_template">
    <div class="form-group">
        <label class="col-sm-1 control-label no-padding-right"></label>
        <div class="col-sm-1">
            <i style="border: none;margin-left: -22px;" class="ace-icon fa fa-trash-o bigger-130 trash_request form-control"></i>
        </div>
        <div class="col-sm-1">
            <input type="text" class="form-control ziduan" />
        </div>
        <div class="col-sm-2">
            <select class="form-control weizhi">
                <option value="formData">formData</option>
                <option value="path">path</option>
                <option value="query">query</option>
                <option value="body">body</option>
                <option value="header">header</option>
                <option value="cookie">cookie</option>
            </select>
        </div>
        <div class="col-sm-2">
            <select class="form-control leixin">
                <option value="integer">integer</option>
                <option value="long">long</option>
                <option value="float">float</option>
                <option value="double">double</option>
                <option value="string">string</option>
                <option value="byte">byte</option>
                <option value="binary">binary</option>
                <option value="boolean">boolean</option>
                <option value="date">date</option>
                <option value="dateTime">dateTime</option>
                <option value="password">password</option>
            </select>
        </div>
        <div class="col-sm-1">
            <select class="form-control required" >
                <option value="true">是</option>
                <option value="false">否</option>
            </select>
        </div>
        <div class="col-sm-3">
            <input type="text" class="form-control miaoshu" />
        </div>
    </div>
</script>
<?php $this->endBlock(); ?>