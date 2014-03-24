 // JavaScript for news.php

// First section uses jQuery
// USE jQuery but let prototype keep on using $
// Logic for Dictionary Letters

jQuery.noConflict(); // we will use jQuery here but in other places we use prototype.js

// The scanned letters

var imageNames = new Array("scan0000.jpg", "scan0001.jpg",
                           "scan0002.jpg", "scan0003.jpg",
                           "scan0004.jpg", "scan0005.jpg",
                           "scan0006.jpg", "scan0007.jpg",
                           "scan0008.jpg", "scan0009.jpg"
                          );
var images = new Array;
var imageSize;
var picInx =  0;

// jQuery ready function. Pass in $ for use within ready function.

jQuery(document).ready(function($) {
  $("<button id='nextLetter'>Next</button> <button id='prevLetter'>Prev</button>").insertAfter("#dictionaryLetters p");
  $("<br><button id='letterNum' style='margin: 10px 0 10px; background-color: #FEF0C9; border: 1px solid black; padding: 3px'>Letter 1</button>").insertAfter("#prevLetter");

  $("#nextLetter").click(function() {
    ++picInx;
    if(picInx >= imagesSize) {
      picInx = 0;
    }
    $("#dict-letter").html(images[picInx]);
    $("#letterNum").text("Letter " + (picInx+1));
  });

  $("#prevLetter").click(function() {
    if(picInx <= 0) {
      picInx = imagesSize;
    }
    --picInx;
    $("#dict-letter").html(images[picInx]);
    $("#letterNum").text("Letter " + (picInx+1));
  });

  for(var i=0; i < imageNames.length; ++i) {
    images[i] = new Image();
    images[i].src = "images/dict-letters/" + imageNames[i];
    images[i].alt = "images/dict-letters/" + imageNames[i];
  }

  imagesSize = i;
  var d = $("#dict-letter");
  d.html(images[picInx]);
});

// End of jQuery logic
//--------------------

