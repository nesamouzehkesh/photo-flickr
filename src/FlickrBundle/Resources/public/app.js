(function($, window, document) {

// Listen for the jQuery ready event on the document
$(function() {
    
    var selectedItem;
    var categoriesContainer = $("#categories-container");
    var galleryContainer = $('#gallery-container');
    var photoContainer = $('#photo-container');
    
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

    // Load list of all categories
    flickrService.getCategories().done(function(data) {
        var dynamicItems = '';
        $.each(data.categories, function(index, category) {
            dynamicItems += '<li class="list-group-item" data-id="' + 
                    category.tag + '">' + 
                    category.title + '</li>';
        });

        categoriesContainer.append(dynamicItems);        
    });
    
    // Get the photos of one category
    categoriesContainer.on("click", "li", function() {
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
        
        photoContainer.hide();
        galleryContainer.fadeIn();        
        
        var galleryBody = galleryContainer.find('.body');
        var galleryTitle = galleryContainer.find('.title');
        // Get photos from API and append it to container
        galleryBody.html('Loading ...');
        galleryTitle.html('');
        flickrService.getPhotos(criteria).done(function(data) {
            var dynamicItems = '';
            $.each(data.photos, function(index, photo) {
                dynamicItems += '<img src="' + 
                        photo.urls.s + '" data-id="' + 
                        photo.id + '" data-url-m="' + 
                        photo.urls.m + '" alt="' + 
                        photo.title + '" class="img-thumbnail flickr-photos"/>';
            });
            
            galleryTitle.html(selectedItem.html());
            galleryBody.html(dynamicItems);
        });
    });    
    
    // Show one photo more info
    galleryContainer.on("click", ".flickr-photos", function() {
        var photoId = $(this).attr('data-id');
        var photoUrlMediom = $(this).attr('data-url-m');
        flickrService.getPhoto(photoId).done(function(data) {
            flickrService.getPhoto(photoId).done(function(data) {
                
                photoContainer.find('.photo-title').html(data.photo.title);
                photoContainer.find('.photo-description').html(data.photo.description);
                photoContainer.find('.photo-owner').html(data.photo.owner.name);
                photoContainer.find('.photo-img').html('<img src="' + photoUrlMediom +'" class="img-thumbnail flickr-photo"/>');
                
                galleryContainer.hide();
                photoContainer.fadeIn();
            });
        });
    });    
    
    // Show the gallery page
    photoContainer.on("click", ".back-button", function() {
        photoContainer.hide();
        galleryContainer.fadeIn();
    });  
});

}(window.jQuery, window, document));