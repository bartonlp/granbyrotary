// BLP 2015-03-15 -- Javascript for webstats.php 

jQuery(document).ready(function($) {
  var tablename="";

  var flags = {webmaster: false, robots: false, ip: false , page: false};

  // Check Flags look at other flags

  function checkFlags(flag) {
    var msg;

    if(flag) { // Flag is true.
      switch(flag) {
        case 'webmaster': // default is don't show
          $(".webmaster").parent().hide();
          msg = "Show ";
          flags.webmaster = false;
          break;
        case 'robots': // true means we are showing robots
          $('.robots').parent().hide();
          msg = "Show ";
          flags.robots = false;
          break;
        case 'ip': // true means only this ip is showing so we want to make all ips show
          $(".ip").removeClass('ip');
          $("#tracker tr").show();

          if(flags.page) {
            $("#tracker td:first-child:not('.page')").parent().hide();
          }
             
          if(!flags.webmaster) {
            $(".webmaster").parent().hide();
          }
          if(!flags.robots) {
            $(".robots").parent().hide();
          }
          msg = "Only ";
          flags.ip = false;
             
          break;
        case 'page': // true means we are only showing this page
          $(".page").removeClass('page');
          $("#tracker tr").show();
                          
          if(flags.ip) {
            $("#tracker td:nth-child(2):not('.ip')").parent().hide();
          }

          if(!flags.webmaster) {
            $(".webmaster").parent().hide();
          }
          if(!flags.robots) {
            $(".robots").parent().hide();
          }
          msg = "Only ";
          flags.page = false;
          break;
      }
      $("#"+ flag).text(msg + flag);
      calcAv();
      return;
    }   

    for(var f in flags) {
      if(flags[f]) { // if true
        switch(f) {
          case 'webmaster':
            flags.webmaster = false;
            if(true in flags) {
              $(".webmaster").parent().not(":hidden").show();
            } else {
              $(".webmaster").parent().show();
            }
            flags.webmaster = true;
            msg = "Hide ";
            break;
          case 'robots':
            flags.robots = false;
            if(true in flags) {
              $('.robots').parent().not(":hidden").show();
            } else {
              $(".robots").parent().show();
            }
            flags.robots = true;
            msg = "Hide ";
            break;
          case 'ip': 
            $("#tracker tr td:nth-child(2):not('.ip')").parent().hide();
            msg = "All ";
            break;
          case 'page':
            $("#tracker tr td:first-child:not('.page')").parent().hide();
            msg = "All ";
            break;
        }
        $("#"+ f).text(msg + f);
      }   
    }
    calcAv();
  }

  // For the Tracker table calculate the average time all users spent looking at stuff

  function calcAv() {
    // Calculate the average time spend using the NOT hidden elements
    var av = 0, cnt = 0;

    // Look for a NOT hidden #tracker child 7 which is the 'time' spent field.

    $("#tracker tbody :not(:hidden) td:nth-child(7)").each(function(i, v) {
      var t = $(this).text();
      if(!t) return true; // Continue

      // If time spent then turn the nn:nn:nn into seconds t and add it to av and inc cnt.

      var ar = t.match(/^(\d{2}):(\d{2}):(\d{2})$/);
      t = parseInt(ar[1], 10) * 3600 + parseInt(ar[2],10) * 60 + parseInt(ar[3],10);
      if(t > 7200) return true; // Continue if over two hours 
 
      av += t;
      ++cnt;      
    });

    av = av/cnt; // Average

    // Now turn seconds back into nn:nn:nn

    var hours = Math.floor(av / (3600)); 
   
    var divisor_for_minutes = av % (3600);
    var minutes = Math.floor(divisor_for_minutes / 60);
 
    var divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);

    var tm = hours.pad()+":"+minutes.pad()+":"+seconds.pad();

    console.log("Tracker: av time spent: ", tm);

    $("#average").html(tm);
  }

  Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {s = "0" + s;}
    return s;
  }

  // End of tracker logic
  //************************************************

  // create a div for name popup

  $("body").append("<div id='popup' style='position: absolute; display: none; border: 2px solid black; background-color: #8dbdd8; padding: 5px;'></div>");

  $(".wrapper").on("click", "#memberpagecnt td:nth-child(2)", function(e) {
    var id = $(this).text();
    var pos = $(this).offset();
    var name = idName[id];
    $("#popup").text(name).css({display: 'block', top: pos.top, left: pos.left+50});
  });

  // The h2 element with class table gets the 'Show Table' spam added.
  // The 'Show Table' span is the clickable toggle that we use to get
  // the table the first time and then to show or hide the display.
  
  $("h2.table").each(function() {
    // The span is class showhide.
    $(this).append(" <span class='showhide' style='color: red'>Show Table</span>");
  });

  // attach class tablesorter to all except our counter and nav-bar
  // At this point none of the Show/Hide table exist!!!

  $("table").not($("#hitCountertbl, #nav-bar table")).addClass('tablesorter');
  $("#counter, #counter2, #memberHits, #otherMemberHits, #memberNot, #memberpagecnt")
      .tablesorter();
  
  // div.table is where we are going to put the tables inside
  // <div class='table' name="name"> so hide them all initially.
  
  $("div.table").hide();

  $(".showhide").css("cursor", "pointer");

  // When the Show/Hide span with class showhide is clicked
  // we do an Ajax call to get the data and
  // append it to the <div class='table' name="..."> the first time.
  // After that we only show/hide the table.

  $(".showhide").click(function() {
    // tgl is not set initially so false.

    $("#popup").hide();

    if(!this.tgl) {
      // Show

      tablename = $(this).parent().next().attr("name"); // global
      var tbl = $("#"+tablename); // The <table>
      var t = $(this); // The span
      var s = t.parent().next(); // <div class="table"

      s.show();
      t.text("Hide Table");

      // if the table has already been instantiated just show it above
      // if not then create the table

      if(!tbl.length) {
        // Make the table from the database via Ajax

        $("body").css("cursor", "wait");
        t.css("cursor", "wait");

        $.get(ajaxfile, { table: tablename }, function(data) {
          // We append the data to the empty <div class='table'
          // name="..."> element.
          $("div[name='"+tablename+"']").append(data);

          //***
          // I think this should only be the table we just made.
          // and we don't need to do it globally on init
          // attach class tablesorter to all except our counter

          $("#"+tablename).addClass('tablesorter');
          
          // Switch for the specific table

          switch(tablename) {
            case "counter":
            case "counter2":
            case "memberpagecnt":
            case "daycount":
              $("#"+tablename).tablesorter(); 
              break;

            case "tracker":
              calcAv();

              // To start Webmaster is hidden

              $("#tracker td:nth-child(2) span.co-ip").each(function(i, v) {
                if($(v).text() == myIp) {
                  $(v).parent().addClass("webmaster").css("color", "green").parent().hide();
                }
              });

              // To start Robots are hidden

              $(".bot td:nth-child(4)").addClass('robots').css("color", "red").parent().hide();

              // Put a couple of buttons before the table

              $("#tracker").before("<div id='trackerselectdiv'>"+
                                   "<button id='webmaster'>Show webmaster</button>"+
                                   "<button id='robots'>Show robots</button>"+
                                   "<button id='page'>Only page</button>"+
                                   "<button id='ip'>Only ip</button>"+
                                   "</div>");

              // ShwoHide Webmaster clicked

              $("#webmaster").click(function(e) {
                if(flags.webmaster) {
                  checkFlags('webmaster');
                } else {
                  // Show
                  flags.webmaster = true;
                  // Now show only my IP
                  checkFlags();
                }
                //flags.webmaster = !flags.webmaster;
              });

              // Page clicked

              $("#tracker td:first-child").click(function(e) {
                if(flags.page) {
                  checkFlags('page');
                } else {
                  // show only this page
                  flags.page = true;
                  var page = $(this).text();
                  $("#tracker tr td:first-child").each(function(i, v) {
                    if($(v).text() == page) {
                      $(v).addClass('page');
                    }
                  });
                  checkFlags();
                }
              });

              // IP address clicked

              $("#tracker td:nth-child(2)").click(function(e) {
                if(flags.ip) {
                  checkFlags('ip');
                } else {
                  // show only IP
                  flags.ip = true;
                  var ip = $(this).text();
                  $("#tracker tr td:nth-child(2)").each(function(i, v) {
                    if($(v).text() == ip) {
                      $(v).addClass('ip');
                    }
                  });
                  checkFlags();
                }
              });

              // ShowHideBots clicked

              $("#robots").click(function() {
                if(flags.robots) {
                  // hide
                  checkFlags('robots');
                } else {
                  // show
                  flags.robots = true;
                  checkFlags();
                }
              });

              $("#ip").click(function() {
                if(flags.ip) {
                  // hide
                  checkFlags('ip');
                } else {
                  // show
                  alert("click on the IP address you want to show");
                }
              });

              $("#page").click(function() {
                if(flags.page) {
                  // hide
                  checkFlags('page');
                } else {
                  // show
                  alert("click on the page you want to show");
                }
              });
              $("#"+tablename).tablesorter(); 
              break;

            case "osoursite1":
              // [[1,1]] means, column 1 (columns start at zero) should sort decending
              // (0=assending). The default for columns is NO sort order (arrow up/down).
              // Note the little arrow is pointing down because we selected [1,1] all of the other
              // arrows are up/down indicating no selected order.

              $("#osoursite1, #osoursite2").tablesorter({ sortList: [[1,1]],
                headers: { 1: {sorter: "currency"},
                3: {sorter: "currency"},
                5: {sorter: "currency"}}});
              
              break;
            case "pageHits":
              $("#pageHits").tablesorter(); //{ sortList:[[1,1]] }
              break;
            case "ipAgentHits":
              $("#ipAgentHits").tablesorter(); // { sortList:[[4,1]] }

              // Set up the Ip Agent Hits table

              $("#ipAgentHits").before("<p>You can toggle the display of only members or all visitors"+
                "<input type='submit' value='Show/Hide NonMembers' id='hideShowNonMembers' /><br/>"+
                "You can toggle the display of the ID "+
                "<input type='submit' value='Show/Hide ID' id='hideShowIdField' /><br/>"+
                "Your can toggle the display of the Webmaster "+
                "<input type='submit' value='Show/Hide Webmaster' id='hideShowWebmaster' /> "+
                "<p class='botmsg'><span style='color: red;'>"+
                "Bots from bots table are red</span><br>"+
                "You can toggle the dispaly of Bots "+
                "<input type='submit' value='Show/Hide Bots' id='hidebots' /></p>");

              // Ip Agent Hits.
              // Hide all ids of zero
              $(".noId, .botmsg").hide();
              
              // Button to toggle bots show/hide

              $("#hidebots").click(function() {
                if(!this.flag) {
                  // show
                  $("#ipAgentHits tr[style^='color: red']").show();
                } else {
                  // hide
                  $("#ipAgentHits tr[style^='color: red']").hide();
                }
                this.flag = !this.flag;
              });

              // Button to toggle between all and members only in Ip Agent Hits
              // table

              $("#hideShowNonMembers").click(function() {
                if(!this.flag) {
                  $(".noId").not("[name='blp']").show();
                  $(".botmsg").show();
                  $("#hidebots").prop("flag", true);
                } else {
                  $(".noId").hide();
                  $(".botmsg").hide();
                }
                this.flag = !this.flag;
              });

              // Hide the ID field in Ip Agent Hits table
              $("#ipAgentHits td:nth-child(3)").hide();
              $("#ipAgentHits thead th:nth-child(3)").hide();

              // Button to toggle between no ID and showing ID in Ip Agent Hits
              // table

              $("#hideShowIdField").click(function() {
                if(!this.flag) {
                  $("#ipAgentHits td:nth-child(3)").show();
                  $("#ipAgentHits thead th:nth-child(3)").show();
                } else {
                  $("#ipAgentHits td:nth-child(3)").hide();
                  $("#ipAgentHits thead th:nth-child(3)").hide();
                }
                this.flag = !this.flag;
              });

              // Hide the webmaster (me) in the Ip Agent Hits table
              $(".blp").hide();

              // Button to toggle between hide and show of Webmaster in Ip Agents
              // Hits Table

              $("#hideShowWebmaster").click(function() {
                if(!this.flag) {
                  $(".blp").show();
                } else {
                  $(".blp").hide();
                }
                this.flag = !this.flag
              });
              break;
          } // end of switch

          $("body").css("cursor", "default");
          t.css("cursor", "pointer");
        });
      }
    } else {
      // Hide

      $(this).parent().next().hide();
      $(this).text("Show Table");
    }
    this.tgl = !this.tgl; // Toggle Show/Hide
    // End of click
  });

  // ipAgents click on agent to add to bots

  $(".wrapper").on("click", "#ipAgentHits td:nth-child(2)" , function(e) {
    var self = $(this);
    var id = $("td:nth-child(3)", self.parent()).text();
    if(id != '0')
      return;

    var agent = self.text();
    var ip = $("td:nth-child(1)" , self.parent()).text();
    console.log("ipAgents ip: %s, agent: %s", ip, agent);
    $.ajax({url: "webstats.php",
            data: {page: 'updatebots', ip: ip, agent: agent},
            type: "get",
            dataType: "json",
            success: function(data) {
              console.log("ipAgents OK data:", data);
              if(data.n) {
                self.html("<span style='color: white; background: red'>"+agent+"</span>");
              }
            },
            error: function(err) {
              console.log("ipAgents ERR:", err);
            }
    });
  });
});
