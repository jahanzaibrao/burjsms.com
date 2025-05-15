<?php include('header.php') ?>

<div class="sub_page_top" style="margin-bottom:0px;">
<div class="container">
        
        <section class="row-fluid">
        <header>
          <h1 class="sub_page_hdng"><?php echo SCTEXT('Our Pricing')?></h1>
        </header>
        <p><?php echo SCTEXT('We offer excellent quality service with reasonable pricing.')?></p>
      
    </section>
</div>
</div>

</div>
<!--End Slide 1-->



<div class="slide" id="slide3" data-slide="3" data-stellar-background-ratio="0.5">
 
    
    <div class="container">
       
        
        <section class="row-fluid">
           
            
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
                                                                <th>SMS Volume</th>
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
                                                    <div class="span-12 text-right">
                                                        <a href="'.Doo::conf()->APP_URL.'web/sign-up/?ptype=0&pid='.$id.'" class="btn btn-danger">'.SCTEXT('Sign up with above plan').'</a>
                                                    </div>
                                                    
                                                    ';
            
            
        }else{
            //subscription based plan
            
            $total_opts = count($plan['opdata']);
            $tableclass= $total_opts>3?'width:20%':'width:29%';
            $replace_str .= '<div class="table2"><ul>';
            $ctr = 1;
            foreach($plan['opdata'] as $opt){
            $subdata = unserialize($opt->opt_data);
               // echo '<pre>';var_dump($subdata);die;
                $period = $subdata['cycle']=='m'?'per Month':'per Year';
                $replace_str .= '<li style="'.$tableclass.'">';
                $replace_str .='<h3 class="red_bg">'.$subdata['name'].'</h3>';
                $replace_str .= '<h1 class="light">'.Doo::conf()->currency.'<span>'.$subdata['fee'].'</span> &nbsp;'.$period.'</h1>';
                foreach($subdata['route_credits'] as $rid=>$cre){
                                                                
                                                                $rtObj = array_filter(
                                                                            $data['rdata'],
                                                                            function ($e) use ($rid) {
                                                                                return $e->id == $rid;
                                                                            }
                                                                        ); 
                                                                        $k = key($rtObj);
                                                               $replace_str.='
                                                                <div class="rtblock"><div class=" text-center label" style="background-color: '.$data['skin']['code'].'; font-size: 16px; padding: 5px;color:#fff;">Route:  &nbsp;'.$rtObj[$k]->title .'</div><div class="rtopts"><div class="control-label">SMS Credits:</div>  '.$cre.' SMS<div class="control-label">'.SCTEXT('Additional Purchases').':</div> '.Doo::conf()->currency.' '.$subdata['route_add_sms_rate'][$rid].' per sms</div></div>';
                                                             }
                //$replace_str .='</div>';
                
                $replace_str .='<ul class="features">
                                    <li><p>
                                    '.$subdata['description'].'
                                    </p></li>
                                                               
                                </ul>';
                 if($subdata['optin']=='0'){
                    $replace_str .='<p class="signup"><a class="btn btn-danger" href="'.Doo::conf()->APP_URL.'web/sign-up/?ptype=1&pid='.$id.'&subopt='.$subdata['idn'].'">'.SCTEXT('Sign Up').'</a></p>';
                }else{
                    $replace_str .='<p class="signup"><a class="btn btn-danger" href="'.Doo::conf()->APP_URL.'web/contact-us/?sub='.$subdata['idn'].'">'.SCTEXT('Contact Us').'</a></p>';
                    
                }
                                
                $replace_str .= '</li>';
              
            
            $ctr++;
                
            }
            
            $replace_str .= '</ul></div>';
            
        }
        
         $content = str_replace($to_replace,$replace_str,$content);
        
    }
    
    echo $content;
    
}else{
    //no plans present
    echo $content;
}

                        ?>
            
            
            
        </section>
    
    </div>
  

<?php include('footer.php') ?>