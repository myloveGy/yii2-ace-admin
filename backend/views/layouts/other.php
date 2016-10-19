<title>Dashboard - Ace Admin</title>

<!-- ajax layout which only needs content area -->
<div class="page-header">
    <h1>
        Dashboard
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            overview &amp; stats
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
    </div>
</div>


<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="/public/assets/js/excanvas.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var scripts = [null,"/public/assets/js/jquery.dataTables.min.js","/public/assets/js/jquery.dataTables.bootstrap.js", null];
    ace.load_ajax_scripts(scripts, function() {
        //inline scripts related to this page
        jQuery(function($) {
            var oTable1 =
                $('#sample-table-2')
                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                    .dataTable( {
                        bAutoWidth: false,
                        "aoColumns": [
                            { "bSortable": false },
                            null, null,null, null, null,
                            { "bSortable": false }
                        ],
                        "aaSorting": [],

                        //,
                        //"sScrollY": "200px",
                        //"bPaginate": false,

                        //"sScrollX": "100%",
                        //"sScrollXInner": "120%",
                        //"bScrollCollapse": true,
                        //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                        //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                        //"iDisplayLength": 50
                    } );
            /**
             var tableTools = new $.fn.dataTable.TableTools( oTable1, {
			"sSwfPath": "../../copy_csv_xls_pdf.swf",
	        "buttons": [
	            "copy",
	            "csv",
	            "xls",
				"pdf",
	            "print"
	        ]
	    } );
             $( tableTools.fnContainer() ).insertBefore('#sample-table-2');
             */


            $(document).on('click', 'th input:checkbox' , function(){
                var that = this;
                $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function(){
                        this.checked = that.checked;
                        $(this).closest('tr').toggleClass('selected');
                    });
            });


            $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
            function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();

                var off2 = $source.offset();
                //var w2 = $source.width();

                if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                return 'left';
            }

        })
    });
</script>