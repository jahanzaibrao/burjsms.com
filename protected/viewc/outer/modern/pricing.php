<?php include('header.php') ?>
     

<div class="spacing"></div>

<div class="row">
    <div class="special-title centered-text">
          <i class="icon-ticket"></i>
          <h2><?php echo SCTEXT('Our Pricing')?></h2>
          <p><?php echo SCTEXT('We offer excellent quality service with reasonable pricing.')?></p>
          <p class="shortline"></p>
        </div>
</div> 

<div class="spacing"></div>

<div class="row">
    
    <!-- start content -->
    
    <?php 
    $content = htmlspecialchars_decode($pdata['content']);
    //check for shortcode

if(sizeof($data['allplans'])>0){
    //there are plan shortcodes present
    
    foreach($data['allplans'] as $plan){
        $id = $plan['id'];
        $to_replace = '[PLANID='.$id.']';
        $replace_str= '';
        
        if($plan['type']=='vol'){
            //volume based plan
            
            $pricing_data = unserialize($plan['opdata']->opt_data);
            $replace_str .='<table class="sc_responsive wd100 table row-border order-column ">
                                                        <thead>
                                                            <tr>
                                                                <th class="span-12">SMS Volume</th>
                                                                ';
                                                                // iterate first item to match the route labels
                                                                $k = 0;
                                                                foreach($pricing_data[0] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        ); 
                                                                        $k = key($rtObj);
                                                                    
                                                              $replace_str .='
                                                                <th id="rhcol'.$ind.'">'.$rtObj[$k]->title.'</th>';
                                                                 }}
            $replace_str .='
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td data-colname="SMS Volume">
                                                                     '.$pricing_data[0]['min'].' to '.$pricing_data[0]['max'].' SMS
                                                                </td>';
                                                                
                                                                // iterate item 
    $k=0;
                                                                foreach($pricing_data[0] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj1 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        ); 
                                                                    $k = key($rtObj1);
                                                                $replace_str .='
                                                                <td>'.Doo::conf()->currency .' '. $val.'/sms</td>';
                                                         }}
            $replace_str .='
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                     '.$pricing_data[1]['min'].' to '.$pricing_data[1]['max'].' SMS
                                                                </td>';
                                                                
                                                                // iterate item 
    $k=0;
                                                                foreach($pricing_data[1] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj2 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        ); 
                                                                    $k = key($rtObj2);
                                                               $replace_str.='
                                                                <td>'.Doo::conf()->currency.' '. $val.'/sms</td>';
                                                                 }}
            $replace_str.='
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    '.$pricing_data[2]['min'].' to '.$pricing_data[2]['max'] .' SMS
                                                                </td>';
                                                                
                                                                // iterate item 
    $k=0;
                                                                foreach($pricing_data[2] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj3 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        ); 
                                                                    $k = key($rtObj3);
                                                                $replace_str .='
                        
                                                                <td>'.Doo::conf()->currency.' '. $val.'/sms</td>';
                                                            }}
            
            $replace_str.='
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    '.$pricing_data[3]['min'].' to '.$pricing_data[3]['max'] .'SMS
                                                                </td>';
                                                                
                                                                // iterate item 
    $k=0;
                                                                foreach($pricing_data[3] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj4 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        ); 
                                                                    $k = key($rtObj4);
                        
                        $replace_str .='
                                                                <td>'.Doo::conf()->currency.' '.$val.'/sms</td>';
                                                         }}
            $replace_str .='
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    '.$pricing_data[4]['min'] .' to '.$pricing_data[4]['max'] .'SMS
                                                                </td>';
                                                                
                                                                // iterate item 
    $k=0;
                                                                foreach($pricing_data[4] as $ind=>$val){
                                                                    if(intval($ind)!=0){
                                                                       $rtObj5 = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($ind) {
                                                                                return $e->id == $ind;
                                                                            }
                                                                        ); 
                                                                    $k = key($rtObj5);
                                                               $replace_str .='
                                                                <td>'.Doo::conf()->currency .' '.$val.'/sms</td>';
                                                        }}
            $replace_str .='
                                                            </tr>

                                                        </tbody>
                                                    </table> 
                                                    <div class="span-12 panel panel-info panel-custom text-right">
                                                        <a href="'.Doo::conf()->APP_URL.'web/sign-up/?ptype=0&pid='.$id.'" class="button">'.SCTEXT('Sign up with above plan').'</a>
                                                    </div>
                                                    
                                                    ';
            
            
        }else{
            //subscription based plan
            
            $total_opts = count($plan['opdata']);
            $tableclass= $total_opts>3?'medium-3 large-3':'medium-4 large-4';
            $replace_str .= '<div class="row">';
            $ctr = 1;
            foreach($plan['opdata'] as $opt){
            $subdata = unserialize($opt->opt_data);
               // echo '<pre>';var_dump($subdata);die;
                $period = $subdata['cycle']=='m'?'per Month':'per Year';
                $replace_str .= '<div class="'.$tableclass.' columns">';
                $replace_str .= '<div class="mod modPriceBox"> <div class="info"><p class="level">'.$subdata['name'].'</p> <p class="price"><span class="dollar">'.Doo::conf()->currency.'</span><span class="number">'.$subdata['fee'].'</span> &nbsp;'.$period.'</p></div>';
                $replace_str .= '<div class="features"><div class="spacing"></div>';
                $replace_str .='<div class="popt-item poptrt">';
                foreach($subdata['route_credits'] as $rid=>$cre){
                                                                
                                                                $rtObj = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($rid) {
                                                                                return $e->id == $rid;
                                                                            }
                                                                        ); 
                                                                        $k = key($rtObj);
                                                               $replace_str.='
                                                                <div class="rtblock"><div class=" text-center label" style="background-color: '.$data['skin']['code'].'; font-size: 16px; padding: 5px;color:#fff;">Route:  &nbsp;'.$rtObj[$k]->title .'</div><div class="rtopts"><div class="control-label">SMS Credits:</div>  '.number_format($cre).' SMS<div class="control-label">'.SCTEXT('Additional Purchases').':</div> '.Doo::conf()->currency.' '.$subdata['route_add_sms_rate'][$rid].' per sms</div></div>';
                                                             }
                $replace_str .='</div>';
                
                $replace_str .='<div class="spacing"></div>
                                <ul class="">
                                    <li><p>
                                    '.$subdata['description'].'
                                    </p></li>
                                                               
                                </ul></div><!--end:features-->
                                <p class="start">';
                if($subdata['optin']=='0'){
                    $replace_str .= '<a class="button" href="'.Doo::conf()->APP_URL.'web/sign-up/?ptype=1&pid='.$id.'&subopt='.$subdata['idn'].'">'.SCTEXT('Signup Now').'</a>';
                }else{
                    $replace_str .= '<a class="button" href="'.Doo::conf()->APP_URL.'web/contact-us/?sub='.$subdata['idn'].'">'.SCTEXT('Contact Us').'</a>';
                }
                $replace_str .='			
                            </p></div></div>';
            
            $ctr++;
                
            }
            
            $replace_str .= '</div>';
            
        }
        
         $content = str_replace($to_replace,$replace_str,$content);
        
    }
    
    echo $content;
    
}else{
    //no plans present
    echo $content;
}

                        ?>
    
    <!-- end content -->
    
</div>



<?php include('footer.php') ?>
<!-- Localized -->