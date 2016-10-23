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
    
    /* Display Selected Item in Bootstrap Button Dropdown Title */
    $(".action-delete").click(function(e){
        e.preventDefault();
        var messageDeleteItem = 'Do your really want to delete this order?';
        var deleteActionUrl = $(this).attr('href');
        bootbox.confirm(messageDeleteItem, function(result) {
            if (result) {
                $.post(deleteActionUrl, function(response) {
                    if (response.success === true) {
                        location.reload();
                    } else {
                        bootbox.alert(response.message);
                    }
                }, 'json');
            } else {
                return true;
            }
        });

    });  
    
    /* Display Selected Item in Bootstrap Button Dropdown Title */
    $(".dropdown-menu li a").click(function(){
      $(this).parents(".dropdown").find('.btn').html($(this).text() + ' <span class="caret"></span>');
      $(this).parents(".dropdown").find('.btn').val($(this).data('value'));
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
    
    //Ladda.bind('button[type=submit]');
});

$.fn.isValid = function(){
    return this[0].checkValidity();
};

function getPaht(url, param)
{
    var queryString = '';
    $.each(param, function( key, value ) {
        if (value !== undefined) {
            queryString = queryString + '&' + key + '=' + value;
        }
    });
    return url + queryString;
}

// activate the tooltip    
function fireTooltip(itemId)
{
    $('#' + itemId).tooltip({ selector: '[data-toggle="tooltip"]' });
}

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

function displayItem(button, cont_item)
{
    var url = button.attr('data-url');
    var modal = $(button.attr('data-target'));
    if (modal !== undefined) {
        showModal(modal);
    }
    
    $.get(url, function(response) {
        if (response.success === true) {
            if (modal !== undefined) {
                modal.html(response.content);
            } else {
                cont_item.html(response.content);
                cont_item.show();
            }
        } else {
            if (modal !== undefined) {
                cont_item.modal('hide');
            }
            
            bootbox.alert(response.message);
        }
    }, 'json'); 
}  

function displayItems(url, cont_items, page)
{
    var searchText = $("#input-search").val();
    var param;
    if ('' !== searchText) {
        var searchTarget = $("#input-search").attr('data-search-target');
        var searchType = $("#input-search").attr('data-search-type');
        if (searchTarget !== undefined) {
            if (searchType !== undefined) {
                param = {searchText: searchText, searchTarget: searchTarget, searchType: searchType, page: page};
            } else {
                param = {searchText: searchText, searchTarget: searchTarget, page: page};
            }
        } else {
            param = {searchText: searchText, page: page};
        }
    } else {
        param = {page: page};
    }
    loadingMessage(true, cont_items);
    $.get(url, param, function(response) {
        loadingMessage(false, cont_items);
        if (response.success === true) {
            cont_items.html(response.content);
        } else {
            bootbox.alert(response.message);
        }
    }, 'json'); 
}

function sortAble(sortContainer, callback) {
    // Sortable actions   
    sortContainer.sortable({
        connectWith: '.d-sortable-connected',
        dropOnEmpty: true,
        update: function(event, ui) {
            var sortIds = $(this).sortable('toArray').toString();
            var url = $(this).attr('data-url');

            simpleGet(url, {sortIds: sortIds}, callback);
        }
    });
}

function showModal(modal)
{
    modal.modal('show');
    modal.html('<div class="modal-dialog"><div class="modal-content"><div class="modal-body"><p>Loading ...</p></div></div></div>'); 
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
function handleForm(button, currentUrl, cont_items, currentPage, callback)
{
    var url = button.attr('data-url');
    var formModal = $(button.attr('data-target'));
    showModal(formModal);
    
    $.get(url, function(response) {
        if (response.success === true) {
            postForm(response, formModal, currentUrl, cont_items, currentPage);
            if (callback !== undefined) {
                setTimeout(function() {      
                    callback(formModal);
                }, 300);
            }                 
        } else {
            formModal.modal('hide');
            bootbox.alert(response.message);
        }
    }, 'json');
}

function simpleGet(url, param, callback) 
{
    $.get(url, param, function(response) {
        if (response.success !== true) {
            bootbox.alert(response.message);
        }
        if (callback !== undefined) {
            setTimeout(function() {        
                callback(response);
            }, 300);                
        }
    }, 'json');
}

function actionLink(button)
{
    var url = button.attr('data-url');
    location.href = url;
}

function loadingMessage(status, cont_items)
{
    if (true === status) {
        cont_items.addClass("d-loading-background");
    } else {
        cont_items.removeClass("d-loading-background");
    }
}

function postForm(response, formContainer, currentUrl, cont_items, currentPage)
{
    formContainer.html(response.content); 
    var form = formContainer.find('form');
    form.submit(function(e){
        e.preventDefault();
        postFormContent($(this), function(response) {
            // If content has been defined then we display this form again, probably there is a form validation error
            if (response.content !== undefined) {
                postForm(response, formContainer, currentUrl, cont_items, currentPage);
            } else {
                if (response.success === true) {
                    if (currentUrl === undefined || cont_items === undefined) {
                        location.reload();
                    } else {
                        formContainer.modal('toggle');
                        displayItems(currentUrl, cont_items, currentPage);
                    }                    
                } else {
                    Ladda.stopAll();
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

function postFormContent($form, callback)
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