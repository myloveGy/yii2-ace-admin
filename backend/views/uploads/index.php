<?php
// 定义标题和面包屑信息
$this->title = '上传文件';
$url = '@web/public/assets';
$depends = ['depends' => 'backend\assets\AdminAsset'];

$this->registerCssFile($url.'/css/dropzone.css', $depends);
$this->registerJsFile($url.'/js/dropzone.min.js', $depends);
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">

        meTables.extend({
            /**
             * 定义编辑表单(函数后缀名Create)
             * 使用配置 edit: {"type": "email", "id": "user-email"}
             * edit 里面配置的信息都通过 params 传递给函数
             */
            "dropzoneCreate": function (params) {
                return '<div id="dropzone" class="dropzone"></div>';
            }
        });
            var m = meTables({
            title: "上传文件",
////            fileSelector: ["#file-url"],
//                form: {
//                    "class": "dropzone"
//                },
            table: {
                "aoColumns": [
                    {
                        "title": "Id",
                        "data": "id",
                        "sName": "id",
                        "defaultOrder": "desc",
                        "edit": {"type": "hidden"}
                    },
                    {
                        "title": "标题",
                        "data": "title",
                        "sName": "title",
                        "edit": {"type": "text", "required": true, "rangelength": "[2, 250]"},
                        "bSortable": false
                    },
                    {
                        "title": "文件访问地址",
                        "data": "url",
                        "sName": "url",
                        "edit": {"type": "dropzone"},
                        "bSortable": false
                    },
                    {
                        "title": "创建时间",
                        "data": "created_at",
                        "sName": "created_at",
                        "createdCell": meTables.dateTimeString
                    },
                    {
                        "title": "修改时间",
                        "data": "updated_at",
                        "sName": "updated_at",
                        "createdCell": meTables.dateTimeString
                    }
                ]
            }
        });

        /**
         meTables.fn.extend({
        // 显示的前置和后置操作
        beforeShow: function(data, child) {
            return true;
        },
        afterShow: function(data, child) {
            return true;
        },
        
        // 编辑的前置和后置操作
        beforeSave: function(data, child) {
            return true;
        },
        afterSave: function(data, child) {
            return true;
        }
    });
         */

        $(function () {
            m.init();

                Dropzone.autoDiscover = false;
                try {
                    var myDropzone = new Dropzone("#dropzone" , {
                        url: "<?=\yii\helpers\Url::toRoute(['uploads/upload', 'sField' => 'url'])?>",
                        // The name that will be used to transfer the file
                        paramName: "UploadForm[url]",
                        params:  {
                            "_csrf": $('meta[name=csrf-token]').attr('content')
                        },
                        maxFilesize: 0.5, // MB
                        addRemoveLinks : true,
                        dictDefaultMessage :
                            '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> Drop files</span> to upload \
                            <span class="smaller-80 grey">(or click)</span> <br /> \
                            <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>'
                        ,
                        dictResponseError: 'Error while uploading file!',
                        //change the previewTemplate to use Bootstrap progress bars
                        previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n  <div class=\"dz-success-mark\"><span></span></div>\n  <div class=\"dz-error-mark\"><span></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"
                        ,init: function() {
                            this.on("success", function (file, response) {
                                if (response.errCode === 0) {
                                    $("#edit-form").append('<input type="hidden" name="url[]" value="' + response.data.sFilePath + '">');
                                } else {
                                    this.removeFile(file);
                                }
                            });
                        }
                    });
                } catch(e) {
                    console.error(e);
                }

        });
    </script>
<?php $this->endBlock(); ?>