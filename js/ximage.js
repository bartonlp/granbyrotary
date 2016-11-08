/* For the image slideshow at the top of index.php */

var bannerImages = new Array, binx = 0;

// Banner photos

dobanner(banner_photos);

function dobanner(photos) {
  for(var i=0, l=0; i < photos.length; ++i) {
    var image = new Image;
    image.inx = i;
    image.src = photos[i];

    $(image).load(function() {
      bannerImages[this.inx] = this;
    });

    $(image).error(function(err) {
      console.log(err);
    });
  }
};

function bannershow() {
  if(binx > bannerImages.length-1) {
    binx = 0;
  }

  var image = bannerImages[binx++];

  $("#slideshow").attr('src', image.src);
  setTimeout(bannershow, 5000);
}

$(window).load(function() {
  bannershow();
});

