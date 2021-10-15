// subsequent

window.addEvent('domready', function(){
    
    var myAccordion = new Fx.Accordion($$('#theSideNavigation a.sub_nav'), 'div.sub_nav',{
                display: -1,
                alwaysHide: true });
                
            
    if($('search_box')) {
        $('search_box').addEvent('focus', function(e) { 
                $('search_box').value = '';
                $('search_box').addClass('focused');
            });
        $('search_box').addEvent('blur', function(e) { 
                if($('search_box').value == '') {
                    $('search_box').value = 'type search here';
                    $('search_box').removeClass('focused');
                }
            }); 
    }

});