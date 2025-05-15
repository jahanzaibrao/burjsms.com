<!--Feedback notifications -->
                                                 <?php if (isset($data['announcements'])){
													 foreach($data['announcements'] as $ann){
														 $type = $ann->type==1?'alert-success':($ann->type==2?'alert-info':'alert-danger');
													 ?>
												<div class=" alert alert-custom <?php echo $type ?>">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<i class="fa fa-check-circle fa-2x"></i> <div class="marqbox"> <span> <?php echo SCTEXT($ann->msg) ?> </span> </div>
					</div>
													 <?php } } ?>
                                            
													 <style>
.marqbox {
	height: 20px;

overflow: hidden;

position: relative;

width: 90%;

display: inline-block;
}
.marqbox span {
 position: absolute;
 width: 100%;
 height: 100%;
 margin: 0;
 font-weight: bold;
 text-align: left;
 /* Starting position */
 -moz-transform:translateX(100%);
 -webkit-transform:translateX(100%);	
 transform:translateX(100%);
 /* Apply animation to this element */	
 -moz-animation: marqbox 20s linear infinite;
 -webkit-animation: marqbox 20s linear infinite;
 animation: marqbox 20s linear infinite;
}
/* Move it (define the animation) */
@-moz-keyframes marqbox {
 0%   { -moz-transform: translateX(100%); }
 100% { -moz-transform: translateX(-100%); }
}
@-webkit-keyframes marqbox {
 0%   { -webkit-transform: translateX(100%); }
 100% { -webkit-transform: translateX(-100%); }
}
@keyframes marqbox {
 0%   { 
 -moz-transform: translateX(100%); /* Firefox bug fix */
 -webkit-transform: translateX(100%); /* Firefox bug fix */
 transform: translateX(100%); 		
 }
 100% { 
 -moz-transform: translateX(-100%); /* Firefox bug fix */
 -webkit-transform: translateX(-100%); /* Firefox bug fix */
 transform: translateX(-100%); 
 }
}
</style>
