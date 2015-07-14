var motoFilter = {
    changeMarkEvent: function($form){
        var selected_mark_id = $form.children('select[name=mark]').children('option:selected').val();
        $.ajax({
            url: '/index.php?route=module/moto_filter/getChilds',
            data: {'child_id': selected_mark_id},
            dataType: 'json',
            type: 'post',
            success: function(response){
                if(response.length) {
                    $form.children('select[name=model]').children().remove();
                    $form.children('select[name=model]').append('<option value="0">Выберите модель</option>');
                    $(response).each(function(){
                        $form.children('select[name=model]').append('<option value="' + this.category_id + '">' + this.name + '</option>');
                    });
                    $form.children('select[name=model]').selectpicker('update');
                    $form.children('select[name=model]').selectpicker('deselectAll');
                    $form.children('select[name=year]').selectpicker('deselectAll');
                } else {
                    $form.children('select[name=model]').children().remove();
                    $form.children('select[name=model]').append('<option value="0">Выберите модель</option>');
                    $form.children('select[name=model]').selectpicker('deselectAll');
                    $form.children('select[name=model]').selectpicker('update');
                    $form.children('select[name=year]').children().remove();
                    $form.children('select[name=year]').append('<option value="0">Выберите год</option>');
                    $form.children('select[name=year]').selectpicker('deselectAll');
                    $form.children('select[name=year]').selectpicker('update');
                }
            },
            error: function(){
                alert('Не удалось получить список моделей по выбранной марке');
            }
        });
    },
    changeModelEvent: function($form){
        var selected_model_id = $form.children('select[name=model]').children('option:selected').val();
        $.ajax({
            url: '/index.php?route=module/moto_filter/getChilds',
            data: {'child_id': selected_model_id},
            type: 'post',
            dataType: 'json',
            success: function(response){
                if(response.length) {
                    $form.children('select[name=year]').children().remove();
                    $form.children('select[name=year]').append('<option value="0">Выберите год</option>');
                    $(response).each(function(){
                        $form.children('select[name=year]').append('<option value="' + this.category_id + '">' + this.name + '</option>');
                    });
                    $form.children('select[name=year]').selectpicker('update');
                    $form.children('select[name=year]').selectpicker('deselectAll');
                } else {
                    $form.children('select[name=year]').children().remove();
                    $form.children('select[name=year]').append('<option value="0">Выберите год</option>');
                    $form.children('select[name=year]').selectpicker('deselectAll');
                    $form.children('select[name=year]').selectpicker('update');
                }
            },
            error: function(){
                alert('Не удалось получить список годов по выбранной модели');
            }
        });
    },
    applyFilter: function($form){
        var child_id = false;
        $form.children('select').children('option:selected').each(function(){
            if($(this).val() != 0) {
                child_id = $(this).val();
            }
        });
        
        var category_id = parseInt($('input[name=request-path]').val());
        var data = {
            'path': window.location.href
        }
        if(child_id) { 
            data.filter_id = child_id;
        }
        if(category_id) {
            data.category_id = category_id;
        }
        $.ajax({
            url: '/index.php?route=module/moto_filter/getLink',
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response){
                if(response.uri) {
                    window.location = response.uri;
                } else {
                    console.log(response);
                    alert('На сервере ведутся технические работы.');
                }
            },
            error: function(){
                alert('По вашему запросу ничего не найденно.');
            }
        });
    }
}
/* MotoFilter events */
$(document).ready(function(){
    
    $(document).on('change', '.moto_filter > select[name=mark]', function(){
        motoFilter.changeMarkEvent($(this).parent('form'));
    });
    $(document).on('change', '.moto_filter > select[name=model]', function(){
        motoFilter.changeModelEvent($(this).parent('form'));
    });
    $(document).on('click', '.moto_filter > button.filterSend', function(e){
        console.log('before apply');
        motoFilter.applyFilter($(this).parent('form'));
        console.log('after apply');
        e.preventDefault();
        return false;
    })
});