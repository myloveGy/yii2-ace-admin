<?php

use \yii\helpers\Url;

// 定义标题和面包屑信息
$this->title = '上传文件';
$url = '@web/public/assets';
$depends = ['depends' => 'backend\assets\AdminAsset'];

$this->registerCssFile($url . '/css/dropzone.css', $depends);
$this->registerJsFile($url . '/js/dropzone.min.js', $depends);
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var myDropzone = null;
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
                        "bSortable": false,
                        "createdCell": function (td, data) {
                            var html = '';
                            if (data) {
                                try {
                                    data = JSON.parse(data);
                                    for (var i in data) {
                                        html += "<img src='" + data[i] + "' width='40px' height='40px'> ";
                                    }
                                } catch (e) {
                                }
                            }
                            $(td).html(html);
                        }
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

        var $form = null;
        meTables.fn.extend({
            // 显示的前置和后置操作
            afterShow: function (data, child) {
                if (!$form) $form = $("#edit-form");
                myDropzone.removeAllFiles();
                $("#dropzone").find("div.dz-image-preview").remove();
                $form.find("input[name='url[]']").remove();
                if (this.action === "update" && data["url"]) {
                    try {
                        var imgs = JSON.parse(data["url"]);
                        for (var i in imgs) {
                            var mockFile = { name: "Filename" + i, size: 12345 };
                            myDropzone.emit("addedfile", mockFile);
                            myDropzone.emit("thumbnail", mockFile, imgs[i]);
                            myDropzone.emit("complete", mockFile);
                            addInput(mockFile.name, imgs[i]);
                        }
                    } catch (e) {
                        console.error(e)
                    }
                }
                return true;
            }
        });

        function addInput(name, url) {
            $form.append('<input type="hidden" data-name="' + name + '" name="url[]" value="' + url + '">');
        }

        $(function () {
            m.init();

            $form = $("#edit-form");

            Dropzone.autoDiscover = false;

            try {
                myDropzone = new Dropzone("#dropzone", {
                    url: "<?=Url::toRoute(['uploads/upload', 'sField' => 'url'])?>",
                    // The name that will be used to transfer the file
                    paramName: "UploadForm[url]",
                    params: {
                        "_csrf": $('meta[name=csrf-token]').attr('content')
                    },
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    dictDefaultMessage:
                        '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> Drop files</span> to upload \
                        <span class="smaller-80 grey">(or click)</span> <br /> \
                        <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>'
                    ,
                    dictResponseError: 'Error while uploading file!',
                    //change the previewTemplate to use Bootstrap progress bars
                    previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n<div class=\"dz-details\">\n<div class=\"dz-filename\"><span data-dz-name></span></div>\n<div class=\"dz-size\" data-dz-size></div>\n<img data-dz-thumbnail />\n</div>\n<div class=\"progress progress-small progress-striped active\"><div class=\"progress-bar progress-bar-success\" data-dz-uploadprogress></div></div>\n<div class=\"dz-success-mark\"><span></span></div>\n<div class=\"dz-error-mark\"><span></span></div>\n<div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>"
                    , init: function () {
                        this.on("success", function (file, response) {
                            if (response.errCode === 0) {
                                addInput(file.name, response.data.sFilePath);
                            } else {
                                this.removeFile(file);
                                layer.msg(response.errMsg, {icon: 5, time: 1000});
                            }
                        });

                        this.on("removedfile", function(file){
                            $form.find("input[data-name='" + file.name + "']").remove();
                        })
                    }
                });
            } catch (e) {
                console.error(e);
            }

        });
    </script>
<?php $this->endBlock(); ?>