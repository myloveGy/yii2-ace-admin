<?php

use yii\helpers\Url;

// 定义标题和面包屑信息
$this->title = '我的日程管理';

$url = '@web/public/assets';
$depends = ['depends' => 'backend\assets\AdminAsset'];

$this->registerCssFile($url.'/css/fullcalendar.css', $depends);
$this->registerCssFile($url.'/css/bootstrap-datetimepicker.css', $depends);
$this->registerJsFile($url.'/js/jquery-ui.custom.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.ui.touch-punch.min.js', $depends);
$this->registerJsFile($url.'/js/date-time/moment.min.js', $depends);
$this->registerJsFile($url.'/js/date-time/bootstrap-datetimepicker.min.js', $depends);
$this->registerJsFile($url.'/js/fuelux/fuelux.spinner.min.js', $depends);
$this->registerJsFile($url.'/js/fullcalendar.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.validate.min.js', $depends);
$this->registerJsFile($url.'/js/validate.message.js', $depends);
?>
<div class="row">
    <div class="col-sm-9">
        <div class="space"></div>
        <!-- #section:plugins/data-time.calendar -->
        <div id="calendar"></div>
        <!-- /section:plugins/data-time.calendar -->
    </div>
    <div class="col-sm-3">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h4> 等待处理事件 </h4>
            </div>
            <div class="widget-body">
                <div class="widget-main no-padding">
                    <div id="external-events">
                        <?php if ($arrange) : ?>
                        <?php foreach ($arrange as $value) : ?>
                        <div class="external-event <?=$timeColors[$value->time_status]?>"
                             sTitle="<?=$value->title?>"
                             iVal="<?=$value->id?>"
                             iEnd="<?=$value->end_at?>"
                             sDesc="<?=$value->desc?>"
                             iTimeStatus="<?=$value->time_status?>"
                             data-class="<?=$timeColors[$value->time_status]?>">
                            <i class="ace-icon fa fa-arrows"></i>
                            <?=$value->title?>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <label>
                            <input type="checkbox" class="ace ace-checkbox" id="drop-remove" />
                            <span class="lbl"> 删除日程事件 </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--隐藏的编辑表单-->
<div class="modal fade" id="calendarModal"  tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> 编辑日程事件信息 </h4>
            </div>
            <div class="modal-body">
                <form method="post" id="editForm" class="form-horizontal" name="editForm" action="update">
                    <input type="hidden" name="actionType" value="insert" />
                    <input type="hidden" name="id"         value="" />
                    <input type="hidden" name="admin_id"   value="<?=\Yii::$app->user->id?>" />
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="input-title"> 事件标题 </label>
                            <div class="col-sm-9">
                                <input type="text" id="input-title" required="true" rangelength="[2, 100]" name="title" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="desc"> 事件描述 </label>
                            <div class="col-sm-9">
                                <textarea required="true" rangelength="[2, 255]" id="desc" name="desc" class="form-control form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="start_at"> 开始时间 </label>
                            <div class="col-sm-9">
                                <div class="input-group bootstrap-datetimepicker">
                                    <input type="text" class="form-control datetime-picker me-datetime" id="start_at" required="true" name="start_at">
                                    <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="end_at"> 结束时间 </label>
                            <div class="col-sm-9">
                                <div class="input-group bootstrap-datetimepicker">
                                    <input type="text" class="form-control datetime-picker me-datetime" id="end_at" required="true" name="end_at">
                                    <span class="input-group-addon">
                                        <i class="fa fa-clock-o bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"> 日程状态 </label>
                            <div class="col-sm-9">
                                <?php if ($status) : ?>
                                <?php foreach ($status as $key => $value) : ?>
                                <label class="line-height-1 blue">
                                    <input type="radio" required="true" number="true" name="status" class="ace valid"  value="<?=$key?>">
                                    <span class="lbl"> <?=$value?> </span>
                                </label>　
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"> 时间状态 </label>
                            <div class="col-sm-9">
                                <?php if ($timeStatus) : ?>
                                    <?php foreach ($timeStatus as $key => $value) : ?>
                                        <label class="line-height-1 blue">
                                            <input type="radio" required="true" number="true" name="time_status" class="ace valid"  value="<?=$key?>">
                                            <span class="lbl"> <?=$value?> </span>
                                        </label>　
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" id="delete-calendar" data-action="delete">
                    <i class="ace-icon fa fa-trash-o"></i> 删除这个日程事件
                </button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-sm btn-primary btn-image" id="update-calendar">确定</button>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    /**
     * formObject() 给表单对象赋值
     * @param form   表单对象
     * @param array 用来赋值对象
     */
    function formObject(form, array)
    {
        form.reset();
        if (array) {
            for (var i in array) {
                if (form[i]) form[i].value = array[i];
            }
        }
    }

    var oLoading = null;

    function alwaysClose()
    {
        layer.close(oLoading);
    }

    function ajaxFail()
    {
        layer.msg("服务器繁忙,请稍后再试");
    }

    var aColors     = <?=json_encode($statusColors)?>;
    jQuery(function($) {
        // 时间选项
        $('.me-datetime').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});

        var modal = $('#calendarModal'),
            oDrop = null,
            calenderCalEvent = {},
            form = document.editForm;

        /**
         * formUpdateObject() 给修改的表单对象赋值，并确定是否提交数据
         * @param form      表单对象
         * @param calEvent  事件对象
         * @param isSubmit  是否提交表单
         */
        function formUpdateObject(form, calEvent, isSubmit)
        {
            calenderCalEvent = calEvent;
            formObject(form, {
                id:          calEvent.id,                                   // ID
                title:       $.trim(calEvent.title),                        // 标题
                desc:        $.trim(calEvent.desc),                         // 说明描述
                start_at:    calEvent.start.format('YYYY-MM-DD HH:mm:ss'),  // 时间开始
                end_at:      calEvent.end.format('YYYY-MM-DD HH:mm:ss'),    // 时间结束
                time_status: calEvent.time_status,                          // 时间状态
                status:      calEvent.status,                               // 状态
                actionType:  'update'
            });

            if (isSubmit == true) $('#update-calendar').trigger('click');
        }

        /* initialize the external events
         -----------------------------------------------------------------*/
        $('#external-events div.external-event').each(function() {
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });

        /* initialize the calendar
         -----------------------------------------------------------------*/
        var calendar = $('#calendar').fullCalendar({
            //isRTL: true,
            buttonHtml: {
                prev: '<i class="ace-icon fa fa-chevron-left"></i>',
                next: '<i class="ace-icon fa fa-chevron-right"></i>'
            },

            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },

            buttonText: {
                today: "今天",
                month: "月",
                week: "周",
                day: "日"
            },  
            /**
             * 字段内容 {title: '长期代办事务', start: new Date(y, m, d - 5),  end: new Date(y, m, d - 2), className: 'label-success'}
             * 字段内容可以追加其他字段信息
             * 可以设置为访问地址 返回格式一致
             */
            events: '<?=Url::toRoute(['arrange'])?>',
            editable: true,
            /**
             * 事件被拖拽
             * calEvent      已经移动后的事件对象
             * dayDelta      保存日程向前或者向后移动了多少的数据
             * minuteDelta   这个值只有在agenda视图有效，移动的时间
             * allDay        如果是月视图,或者是agenda视图的全天日程，此值为true,否则为false
             */
            eventDrop: function(calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
                // 表单重新赋值
                formUpdateObject(form, calEvent, true);
            },
            /**
             * 事件改变大小
             * calEvent      已经移动后的事件对象
             * dayDelta      保存日程向前或者向后移动了多少的数据
             * minuteDelta   这个值只有在agenda视图有效，移动的时间
             * allDay        如果是月视图,或者是agenda视图的全天日程，此值为true,否则为false
             */
            eventResize: function(calEvent, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
                // 表单重新赋值
                formUpdateObject(form, calEvent, true);
            },
            droppable: true, // this allows things to be dropped onto the calendar !!!
            // 拖拽事件
            drop: function(date, allDay) {
                var isDel = $('#drop-remove').is(':checked');
                if (isDel) oDrop = $(this);
                formObject(form, {
                    'id':           $.trim($(this).attr('iVal')),
                    'title':        $.trim($(this).attr('sTitle')),
                    'desc':         $.trim($(this).attr('sDesc')),
                    'start_at':     date.format('YYYY-MM-DD HH:mm:ss'),
                    'end_at':       (new Date(date.format('YYYY-MM-DD HH:mm:ss'))).getTime() / 1000 + 86400,
                    'status':       1,
                    'time_status': $.trim($(this).attr('iTimeStatus')),
                    'actionType':  isDel ? 'update' : 'create'
                });
                $('#update-calendar').trigger('click');
            },
            selectable: true,
            selectHelper: true,
            // 点击日期事件
            select: function(start, end, allDay) {
                $('#delete-calendar').hide();
                // 默认赋值
                formObject(form, {
                    'start_at':     start.format('YYYY-MM-DD HH:mm:ss'),     // 时间开始
                    'end_at':       end.format('YYYY-MM-DD HH:mm:ss'),       // 时间结束
                    'time_status':  1,                                       // 时间状态
                    'status'     :  1,                                       // 状态
                    'actionType':  'create'                                  // 操作类型
                });
                // 添加一个新的日程事件
                modal.modal('show').find('h4').html('添加一个新的事件');
            },
            // 事件被点击
            eventClick: function(calEvent, jsEvent, view) {
                $('#delete-calendar').show();
                // 开始赋值显示编辑
                formUpdateObject(form, calEvent);
                modal.modal('show').find('h4').html('编辑日程事件信息');
            }
        });

        // 编辑日程事件
        $('#update-calendar').click(function(){
            if ($('#editForm').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                        $(e).remove();
                    }
                }).form()) {
                oLoading  = layer.load();
                // 提交数据
                $.ajax({
                    url:        $('#editForm').find('input[name=actionType]').val(),
                    type:       'POST',
                    dataType:   'json',
                    data:       $('#editForm').serializeArray()
                }).always(alwaysClose).done(function(json) {
                    layer.msg(json.errMsg, {icon:json.errCode == 0 ? 6 : 5});
                    if (json.errCode == 0) {
                        // 开始修改数据
                        calenderCalEvent.id          = json.data.id;
                        calenderCalEvent.desc        = json.data.desc;
                        calenderCalEvent.title       = json.data.title;
                        calenderCalEvent.start       = new Date(json.data.start_at * 1000);
                        calenderCalEvent.end         = new Date(json.data.end_at * 1000);
                        calenderCalEvent.status      = json.data.status;
                        calenderCalEvent.time_status = json.data.time_status;
                        calenderCalEvent.className   = aColors[calenderCalEvent.status];
                        // 判断类型处理数据
                        var strEvent = form.actionType.value == 'update' && ! oDrop ? 'updateEvent' : 'renderEvent';
                        calendar.fullCalendar(strEvent, calenderCalEvent, true);
                        // 新增日程事件
                        if (strEvent == 'renderEvent' && ! oDrop) calendar.fullCalendar('unselect');
                        // 拖拽日程事件
                        if (oDrop) oDrop.remove();
                        oDrop = null;
                        calenderCalEvent = {};
                        modal.modal("hide");
                    }
                }).fail(ajaxFail);
            }
        });

        // 删除日程事件
        $('#delete-calendar').click(function(){
            // 删除之前先提醒
            layer.confirm('您确定需要删除这条数据吗?', {
                title: '确认操作',
                btn: ['确定','取消'],
                shift: 4,
                icon: 0
                // 确认删除
            }, function(){
                oLoading = layer.load();
                $.ajax({
                    url:        'delete',
                    type:       'POST',
                    dataType:   'json',
                    data:       {
                        'id':         calenderCalEvent._id,
                        'actionType': 'delete'
                    }
                }).always(alwaysClose).done(function(json) {
                    layer.msg(json.errMsg, {icon:json.errCode == 0 ? 6 : 5});
                    if (json.errCode == 0) {
                        calendar.fullCalendar('removeEvents' , function(ev){
                            return (ev._id == calenderCalEvent._id);
                        });
                        calenderCalEvent = {};
                        modal.modal("hide");
                    }
                }).fail(ajaxFail);
                // 取消删除
            }, function(){layer.msg('您取消了删除操作！', {time:800});});
        });
    })
</script>
<?php $this->endBlock() ?>