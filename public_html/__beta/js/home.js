// subsequent

window.addEvent('domready', function(){
    
    var win_size = window.getScrollSize();    
    
    var slideTimer = 4000;  //time between slides (1 second = 1000), a.k.a. the interval duration  
    var transitionTime = 2000; //transition time (1 second = 1000)  
    var which = 1;
    var maxx = 5;
    
    
     $$('#theRotator div').setStyle('opacity', 0);
    $$('#theRotator div').setStyle('display', 'block');
    if($('d1'))
        $('d1').setStyle('opacity', 1);

    var slideFuntion = new function() {
    
        var okgo = function() {
           var outEl = $('d' + which);
            if(which >= maxx)
                which = 1;
            else    
                which++;
            
            var inEl = $('d' + which);
            
              var item_out = new Fx.Morph(outEl, {
                     duration: transitionTime, 
                     transition: Fx.Transitions.Quad.easeInOut, 
                     wait:false
            });
            
            var item_in = new Fx.Morph(inEl, {
                     duration: transitionTime, 
                     transition: Fx.Transitions.Quad.easeInOut, 
                     wait:false
            });
            
             item_out.start({ 
                'opacity':[1,0]
            });
            
            item_in.start({ 
                'opacity':[0,1] 
            });
        };
        
        okgo.periodical(slideTimer, this);
    }
 
            

});