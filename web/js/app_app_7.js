var searchText = '';
var CKEDITOR_BASEPATH = '/js/ckeditor/';
          
$(document).ready(function(){
    // Text box clear button and action
    function tog(v) {return v? 'addClass' : 'removeClass';} 
    $(this).on('input', '.clearable', function(){$(this)[tog(this.value)]('x');})
        .on('mousemove', '.x', function( e ){$(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');})
        .on('click', '.onX', function(){$(this).removeClass('x onX').val('').change();});

    $('#nav').affix({
          offset: {
            top: 0
          }
    });	

    $('#sidebar').affix({
          offset: {
            top: 20
          }
    });
    
    $("#main-sidebar-toggle").click(function(){
        if ($('#main-sidebar .full-block').hasClass('sm')) {
            $('#main-body').removeClass('main-body-lg main-body-default').addClass('main-body-sm');
            $('#main-sidebar .full-block').removeClass('sm default').addClass('lg', {duration: 100});
            $('#main-sidebar-toggle').removeClass('sidebar-toggle-open').addClass('sidebar-toggle-close');
        } else {
            $('#main-body').removeClass('main-body-sm main-body-default').addClass('main-body-lg');
            $('#main-sidebar .full-block').removeClass('lg default').addClass('sm', {duration: 50});
            $('#main-sidebar-toggle').removeClass('sidebar-toggle-close').addClass('sidebar-toggle-open');
        }
    });

    /* Set Search type */
    $('.action-set-search-type').click(function(e){
        var searchTarget = $(this).attr('data-search-target');
        var searchType = $(this).attr('data-search-type');
        var searchTextInput = $(this).attr('data-search-input');
        if (searchType !== undefined) {
            $("#" + searchTextInput).attr('data-search-type', searchType);
        }        
        $("#" + searchTextInput).attr('data-search-target', searchTarget);
    });    
});

$.fn.isValid = function(){
    return this[0].checkValidity();
};

function searchItems(searchInput, url_items, cont_items, currentPage)
{
    var newSearchText = searchInput.val();
    if (searchText !== newSearchText) {
        displayItems(url_items, cont_items, currentPage);
    }
    searchText = newSearchText;
}

function deleteItem(button, currentUrl, cont_items, currentPage)
{
    var messageDeleteItem = 'Do your really want to delete this item?';
    var deleteActionUrl = button.attr('data-url');
    bootbox.confirm(messageDeleteItem, function(result) {
        if (result) {
            $.post(deleteActionUrl, function(response) {
                if (response.success === true) {
                    if (currentUrl !== undefined) {
                        displayItems(currentUrl, cont_items, currentPage);
                        getFlashBag();
                    } else {
                        location.reload();
                    }
                } else{
                    bootbox.alert(response.message);
                }
            }, 'json');
        } else {
            return true;
        }
    });
}

function getFlashBag()
{
    var flashBagContainer = $('#flashBag-container');
    var url = flashBagContainer.attr('data-url');
    $.get(url, function(response) {
        if (response.content !== undefined) {
            flashBagContainer.html(response.content);
            flashBagContainer.show();
            flashBagContainer.delay(4000).slideUp();
        }            
    }, 'json');
}    

function displayItem(button)
{
    var url = button.attr('data-url');
    var modal = $(button.attr('data-target'));
    
    if (modal !== undefined) {
        showModal(modal);
        $.get(url, function(response) {
            if (response.success === true) {
                var $content = $(response.content);
                modal.find('.modal-body').html($content.find('.modal-body').html());
                modal.find('.modal-footer').html($content.find('.modal-footer').html());                
            } else {
                modal.find('.modal-body').html(response.message);
            }
        }, 'json'); 
    }
}  

function showModal(modal)
{
    modal.modal('show');
    modal.find('.modal-body').html('<p>Loading ...</p>');
}

function hideModal(modal)
{
    modal.modal('hide');
    modal.html(''); 
}

/*
Display add/edit module form and returns the form as callback parameter if 
form display correctly, prepare and handle the post action.
*/
function displayForm(button, callback)
{
    var url = button.attr('data-url');
    var formContainer = $(button.attr('data-target'));
    showModal(formContainer);
    
    $.get(url, function(response) {
        if (response.success === true) {
            handleFormSubmission(response, formContainer);
            if (callback !== undefined) {
                setTimeout(function() {      
                    callback(formContainer);
                }, 300);
            }                 
        } else {
            formContainer.modal('hide');
            bootbox.alert(response.message);
        }
    }, 'json');
}

function handleFormSubmission(response, formContainer)
{
    // turn the whole html response into a jQuery object without inserting it 
    // into the DOM. This allows you to manipulate or look for specific elements 
    // or values and do different things with different parts of the response
    var $content = $(response.content);

    
    if(formContainer.find('.modal-body-content').length) {
        formContainer.find('.modal-body-content').html(response.content);
        formContainer.find('.form-body').addClass('modal-body');
        formContainer.find('.form-footer').addClass('modal-footer');
    } else {
        formContainer.html(response.content); 
    }
    
    var form = formContainer.find('form');
    form.submit(function(e){
        e.preventDefault();
        submitForm($(this), function(response) {
            // If content has been defined then we display this form again, probably there is a form validation error
            if (response.content !== undefined) {
                handleFormSubmission(response, formContainer);
            } else {
                if (response.success === true) {
                    location.reload();
                } else {
                    var formMessage = form.find('.form-message');
                    formMessage.html(getAlart(response.message, 'warning'));
                    formMessage.show();
                    formMessage.delay(2000).slideUp();
                }
            }
        }, 'json');
        return false;
    });
}

function submitForm($form, callback)
{
    var values = {};
    var fields = {};

    // If CKEDITOR is defined in this form, then we update it content
    if (typeof CKEDITOR !== 'undefined') {
        for (var instanceName in CKEDITOR.instances){
            CKEDITOR.instances[instanceName].updateElement();
        }
    }

    $.each($form.serializeArray(), function(i, field) {
        var fieldName = field.name;
        var fieldLength = fieldName.length;
        if ('[]' === fieldName.substr(fieldLength - 2, fieldLength)) {
            var fieldNewName = fieldName.substr(0, fieldLength - 2);
            if (fields[fieldNewName] === undefined) {
                fields[fieldNewName] = 0;
            } else {
                fields[fieldNewName] = fields[fieldNewName] + 1;
            }
            values[fieldNewName + '[' + fields[fieldNewName] + ']'] = field.value;  
        } else {
            values[fieldName] = field.value;
        }
    });

    // Throw the form values to the server!
    $.ajax({
        type        : $form.attr('method'),
        url         : $form.attr('action'),
        data        : values,
        success     : function(response) {
            callback(response);
        }
    });
}

function getAlart(mesage, type)
{
    var icon = {
        "success": "glyphicon glyphicon-ok-sign",
        "info": "glyphicon glyphicon-info-sign",
        "warning": "glyphicon glyphicon-exclamation-sign",
        "error": "glyphicon glyphicon-remove-sign"
    }; 
    type = type === 'error'? 'danger' : type;
    
    return '<div class="alert alert-' + type + '" role="alert"><span class="' + icon[type] + '" aria-hidden="true"></span> ' + mesage + '</div>';
}
