 <div id="page-content">
            <div class="content-header">
                            <div class="page-header">
                                <h1>
                                    Mobile Prefixes <small>Mobile-operator-circle prefix mapping for reports</small>
                                </h1>
                            </div>
                        </div>
                    
                     <?php include('breadcrums.php') ?>
                  <?php include('notification.php') ?>    
                  
                    <div class="row">
                        <div class="col-md-12 block">
							<div class="clearfix sepH_b">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo $data['baseurl'] ?>addPrefix" class="btn btn-primary"><i class="icon-plus icon-white"></i> Add New Prefix</a>
                                    <a href="<?php echo $data['baseurl'] ?>importPrefixes" class="btn btn-info"><i class="icon-upload-alt icon-white"></i> Import Prefixes</a>
									
                                    
                                </div>
                            </div><br />
                            <table id="dt_e" class=" table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Prefix</th>
										<th>Operator</th>
										<th>Circle</th>
										<th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                        
                
            </div>
          
           
            
<script>
	$(document).ready(function(){
		$('#dt_e').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
					 "aaSorting": [],
					 "sDom": '<"dataTables_length"l><"dataTables_tools"T><"dataTables_filter"f>rtip',
					  "oTableTools": {
            "sRowSelect": "multi",
            "aButtons": [
                "select_all", "select_none", 
				//--delete--//
					 {
                    "sExtends":    "text",
					"sButtonText": "Delete",
                    "fnClick": function ( nButton, oConfig, oFlash ) {
                        //-----
						var mcnt = $(".DTTT_selected").length;
						if(mcnt==0){
							bootbox.alert('No Prefix Selected. Please select at least one prefix to perform this action.');
						}else{
						 bootbox.confirm("Are you sure you wanna Delete "+mcnt+" prefixes?", function (result) {
                    if(result){
						//get all selected contacts
						var cids = [];
						$(".DTTT_selected").each(function(){
							$id = $(this).children().find("[name='pids[]']").val();
							cids.push($id);
							});
						$cidstr = cids.join(",");
						$.ajax({
				type: 'POST',
				url: app_url+'delManyPrefixes',
				data: {
					pids: $cidstr
					},
				success: function(res){
					location.reload();
					}
				});
					}
                });
						//-----
						}
                    }
					 }
            ]
			
        },
					 "sAjaxSource": app_url+"getAllPrefixes"
				} );
	});
</script>
<style>
div.selector span {width:130px !important;}
</style>