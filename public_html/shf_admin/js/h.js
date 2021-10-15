// subsequent

window.addEvent('domready', function(){
    
    $$('.remover').each(function(el) {
        el.addEvent('click', function(e) {
            if(!confirm("Are you sure that you want to remove this Product Evaluator?\r\nThis can not be undone!"))
					e.stop();
        });
    });
    
    var inn = ' <div class="fform"><label><i>New Field:</i></label><input type="text" name="more_field[]" value="" /></div>';
    var inn_count = 1
    $$('.add_field').each(function(el) {
        el.addEvent('click', function(e) {
            e.stop();
            m = $('more_fields' + inn_count);
            h = m.get('html');
            inn_count++;
            mf = '<div id="more_fields' + inn_count + '"></div>';
            
            m.set('html', h + inn + mf);            
            
        });
    });
    
    var ddd = new Date();
    
   
   new DatePicker('.dater', {
		pickerClass: 'datepicker_vista',
		inputOutputFormat: 'Y-m-d',
        format: 'M d, Y'
		// yearPicker: false,
		// minDate: { date: '10-03-2009', format: 'd-m-Y' },
		// maxDate: { date: '25-06-2009', format: 'd-m-Y' }
	});

    if($('the_editor')) {
        //CKEDITOR.replace( 'FCKeditor1');
        
        CKEDITOR.replace( 'FCKeditor1', {
            filebrowserBrowseUrl : '/shf_admin/lib/ckeditor/browser/browse.php',
            filebrowserUploadUrl : '/shf_admin/lib/ckeditor/uploader/upload.php'
        });
        
    }

});