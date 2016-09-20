<?php
// 定义标题和面包屑信息
$this->title = '我的日程管理';
$this->params['breadcrumbs'] = [
    [
        'label' => '日程管理',
        'url'   => ['/arrange/index']
    ],

    $this->title
];

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
                <h4> 代办事件 </h4>
            </div>

            <div class="widget-body">
                <div class="widget-main no-padding">
                    <div id="external-events">
                        <?php if ($arrange) : ?>
                        <?php foreach ($arrange as $value) : ?>
                        <div class="external-event <?=$timeColors[$value->time_status]?>" data-class="<?=$timeColors[$value->time_status]?>">
                            <i class="ace-icon fa fa-arrows"></i>
                            <?=$value->title?>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <label>
                            <input type="checkbox" class="ace ace-checkbox" id="drop-remove" />
                            <span class="lbl"> 删除移动掉事件 </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        handleMenuActive('\\/arrange\\/index');
        /* initialize the external events
         -----------------------------------------------------------------*/
        $('#external-events div.external-event').each(function() {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });

        /* initialize the calendar
         -----------------------------------------------------------------*/

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

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
            events: <?=$userArrange?>
//            events: [
//                {
//                    title: '这一天要办',
//                    start: new Date(y, m, 1),
//                    className: 'label-important'
//                },
//                {
//                    title: '长期代办事务',
//                    start: new Date(y, m, d - 5),
//                    end: new Date(y, m, d - 2),
//                    className: 'label-success'
//                },
//                {
//                    title: '一些事件',
//                    start: new Date(y, m, d - 3, 16, 0),
//                    allDay: false
//                }
//            ]
            ,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function(date, allDay) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
                var $extraEventClass = $(this).attr('data-class');


                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);

                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                if($extraEventClass) copiedEventObject['className'] = [$extraEventClass];

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }

            }
            ,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                bootbox.prompt("添加一个新的事件:", function(title) {
                    console.info(start, end, allDay);
                    if (title !== null) {
                        calendar.fullCalendar('renderEvent',
                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            },
                            true // make the event "stick"
                        );
                    }
                });

                calendar.fullCalendar('unselect');
            }
            ,
            eventClick: function(calEvent, jsEvent, view) {
                // display a modal
                var modal =
                        '<div class="modal fade">\
                          <div class="modal-dialog">\
                           <div class="modal-content">\
                             <div class="modal-body">\
                               <button type="button" class="close" data-dismiss="modal" style="margin-top:-10px;">&times;</button>\
                               <form class="no-margin">\
                                  <label> 更改事件名称 &nbsp;</label>\
                                  <input class="middle" autocomplete="off" type="text" value="' + calEvent.title + '" />\
                         <button type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i> 保存 </button>\
                       </form>\
                     </div>\
                     <div class="modal-footer">\
                        <button type="button" class="btn btn-sm btn-danger" data-action="delete"><i class="ace-icon fa fa-trash-o"></i> 删除这个事件 </button>\
                        <button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> 取消 </button>\
                     </div>\
                  </div>\
                 </div>\
                </div>';

                var modal = $(modal).appendTo('body');
                modal.find('form').on('submit', function(ev){
                    ev.preventDefault();
                    calEvent.title = $(this).find("input[type=text]").val();
                    calendar.fullCalendar('updateEvent', calEvent);
                    modal.modal("hide");
                });
                modal.find('button[data-action=delete]').on('click', function() {
                    calendar.fullCalendar('removeEvents' , function(ev){
                        return (ev._id == calEvent._id);
                    });
                    modal.modal("hide");
                });

                modal.modal('show').on('hidden', function(){
                    modal.remove();
                });

                //console.log(calEvent.id);
                //console.log(jsEvent);
                //console.log(view);
                // change the border color just for fun
                //$(this).css('border-color', 'red');
            }

        });


    })
</script>