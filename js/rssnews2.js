// Generic rss feed display
// List of items that can be expanded

jQuery.fn.newsFeed2 = function(feed, offset) {
  var element = this;
  var oneDay = 86400000;
  var tday = new Date("Jan 1 2000");

  if(typeof(offset) != 'undefined') {
    var t = new Date();
    var off = oneDay * offset;
    var tt = t.getTime();
    tday = t.setTime(tt - off);
  }

  $(element).each(function() {
    var $container = $(this);
    $container.empty(); // get rid of any thing that might be there to support noscript browsers!

    var fadeHeight = $container.height() / 4;

    for (var yPos = 0; yPos < fadeHeight; yPos += 2) {
      $('<div></div>').css({
opacity: yPos / fadeHeight,
top: $container.height() - fadeHeight + yPos
      }).addClass('fade-slice').appendTo($container);
    }

    var $loadingIndicator = $('<img/>').attr({
      'src': '/images/loading.gif', 
      'alt': 'Loading. Please wait.'
    }).addClass('news-wait').appendTo($container);

    $.ajax( {
url: feed,
datatype: 'xml',
error: function(xmlreq, status) {
       $("<div>Error Loading RSS Feed: " +status+ "</div>").appendTo($container);
     },
success: function(data, status) {
      $loadingIndicator.remove();

      var inx = 0;
      
      $('rss item', data).each(function() {
        var pubDate = new Date($('pubDate', this).text());

        if(pubDate > tday) {

          var $link = $('<a></a>')
                      .attr('href', $('link', this).text())
                      .attr('target', '_blank')
                      .html("("+(++inx)+")" + " " + $('title', this).text());

          var $headline = $('<h4></h4>').append($link);


          var pubMonth = pubDate.getMonth() + 1;
          var pubDay = pubDate.getDate(); 
          var pubYear = pubDate.getFullYear();

          var $publication = $('<div></div>')
                             .addClass('publication-date')
                             .text(pubMonth + '/' + pubDay + '/' + pubYear);

          var $summary = $('<div></div>')
                         .addClass('summary')
                         .html($('description', this).text());

          $('<div></div>')
              .addClass('headline')
              .append($headline, $publication, $summary)
              .appendTo($container);
        }
      });

      var currentHeadline = 0, oldHeadline = 0;
      var hiddenPosition = $container.height() + 10;

      $('div.headline').eq(currentHeadline).css('top', 0);

      var headlineCount = $('div.headline').length;

      var pause;
      var rotateInProgress = false;

      var headlineRotate = function() {
        if (!rotateInProgress) {
          rotateInProgress = true;
          pause = false;
          currentHeadline = (oldHeadline + 1)
                            % headlineCount;

          $('div.headline').eq(oldHeadline).animate(
            {top: -hiddenPosition}, 'slow', function() {
            $(this).css('top', hiddenPosition);
          });

          $('div.headline').eq(currentHeadline).animate(
            {top: 0}, 'slow', function() {
            rotateInProgress = false;
            if (!pause) {
              pause = setTimeout(headlineRotate, 5000);
            }
          });
          oldHeadline = currentHeadline;
        }
      };

      if (!pause) {
        pause = setTimeout(headlineRotate, 5000);
      }

      $container.hover(function() {
        clearTimeout(pause);
        pause = false;
      }, function() {
        if (!pause) {
          pause = setTimeout(headlineRotate, 250);
        }
      });
     }
    });
  });
}

