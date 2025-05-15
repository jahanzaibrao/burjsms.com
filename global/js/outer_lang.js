function SCTEXT(str){
        var lstr = str.toLowerCase();
        if(!localStorage.getItem(lstr) || localStorage.getItem(lstr)==''){
            return str;
        }else{
            return localStorage.getItem(lstr);
            //return sctext[$str];
        }
    }    
        
        
        if(!localStorage.getItem('home') || localStorage.getItem('app_lang')!=app_lang){
           $.ajax({
                url: app_url+'getScText',
                success: function(res){
                    if(res=='en'){
                        localStorage.clear();
                        localStorage.setItem("app_lang",app_lang);
                    }else{
                        //populate js array
                        var transtxt = [];
                        transtxt = JSON.parse(res);
                        for( var word in transtxt){
                            localStorage.setItem(word, transtxt[word]);
                        }
                        localStorage.setItem("app_lang",app_lang);
                    }

                }

            });
        }