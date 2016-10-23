(function($, window, document) {

// Listen for the jQuery ready event on the document
$(function() {
    
    var selectedItem;
    var flickrCategories = $("#flickr-categories");
    var flickrPhotoContainer = $('#flickr-photo-container');
    
    // API service 
    var flickrService = {
        getCategories: function() {
            return $.ajax({
              url: "api/flickr/categories",
              type: "get"
            });
        },
        getPhotos: function(criteria) {
            return $.ajax({
              url: "api/flickr/photos",
              type: "get",
              data: criteria
            });
        },
        getPhoto: function(id) {
            return $.ajax({
              url: "api/flickr/photos/" + id,
              type: "get",
            });
        },
    };

    
    flickrService.getCategories().done(function(data) {
        var dynamicItems = '';
        $.each(data.categories, function(index, category) {
            dynamicItems += '<li class="list-group-item" data-id="' + category.tag + '">' + category.title + '</li>';
        });

        flickrCategories.append(dynamicItems);        
    });
    
    flickrCategories.on("click", "li", function() {
        if (selectedItem !== undefined) {
            selectedItem.removeClass('selected');
        }
        // Set the new item as the selected one
        selectedItem = $(this);
        selectedItem.addClass('selected');
        
        // Generate search criteria
        var criteria = {
            tag: selectedItem.attr('data-id')
        };
        
        // Get photos from API and append it to container
        flickrPhotoContainer.html('Loading ...');
        flickrService.getPhotos(criteria).done(function(data) {
            var dynamicItems = '';
            $.each(data.photos, function(index, photo) {
                dynamicItems += '<img src="' + photo.urls.s + '" data-id="' + photo.id + '" alt="' + photo.title + '" class="img-thumbnail flickr-photo">';
            });
            
            flickrPhotoContainer.html('');
            flickrPhotoContainer.append(dynamicItems);        
        });
    });    
    
    flickrPhotoContainer.on("click", ".flickr-photo", function() {
        var photoId = $(this).attr('data-id');
        flickrService.getPhoto(photoId).done(function(data) {
            
        });
    });    

});

}(window.jQuery, window, document));