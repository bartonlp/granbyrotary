// News feed

$(document).ready(function() {
  $('#news-feed').each(function() {
    var $container = $(this);
    $container.empty();

    var fadeHeight = $container.height() / 4;

    //console.log("fadeHeight="+ fadeHeight);
    
    for (var yPos = 0; yPos < fadeHeight; yPos += 2) {
      $('<div></div>').css({
opacity: yPos / fadeHeight,
top: $container.height() - fadeHeight + yPos
      }).addClass('fade-slice').appendTo($container);
    }

    var $loadingIndicator = $('<img/>').attr({
      'src': 'images/loading.gif', 
      'alt': 'Loading. Please wait.'
    }).addClass('news-wait').appendTo($container);

    $.ajax( {
url: 'articles/createrss.php',
datatype: 'xml',
error: function(xmlreq, status) {
       $("<div>Error Loading RSS Feed: " +status+ "</div>").appendTo($container);
       //alert("ERROR: " +status);
     },
success: function(data, status) {
      $loadingIndicator.remove();

      console.log(data);
      $('rss item', data).each(function(inx) {
        var $link = $('<a></a>')
                    .attr('href', $('link', this).text())
                    .attr('target', '_blank')
                    .html("("+(inx + 1)+")" + " " + $('title', this).text());

        var $headline = $('<h4></h4>').append($link);

        var pubDate = new Date($('pubDate', this).text());
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
});

