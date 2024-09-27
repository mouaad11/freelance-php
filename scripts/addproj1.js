function previewImages(event) {
    var preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    var files = event.target.files;
    var maxImages = 5; 
    
    if (files.length > maxImages) {
        alert('Maximum ' + maxImages + ' images allowed.');
        return;
    }
    
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-image'); 
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(file);
    }
}

// Add event listener to the file input
var fileInput = document.querySelector('input[type="file"]');
fileInput.addEventListener('change', function(event) {
    previewImages(event);
});