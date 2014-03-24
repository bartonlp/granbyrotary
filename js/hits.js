// JavaScript for hits.php. Requires jQuery

jQuery(document).ready(function($) {
  var tablename="{$_GET['table']}";
  // create a div for name popup
  $("body").append("<div id='popup' style='position: absolute; display: none; border: 2px solid black; background-color: #8dbdd8; padding: 5px;'></div>");

  $("h2.table").each(function() {
    $(this).append(" <span class='showhide' style='color: red'>Show Table</span>");
  });

  $("table").not("#hitCountertbl").addClass('tablesorter'); // attach class tablesorter to all except our counter
  $("#memberHits, #otherMemberHits, #memberNot").tablesorter();
  $("#lamphost table").tablesorter({ sortList: [[2,1]], headers: { 1: {sorter: "currency"} } } );
  $("#OScnt table").tablesorter({ sortList:[[1,1]] , headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}}});
  $("#browserCnt table").tablesorter({ sortList:[[1,1]], headers: { 1: {sorter: "currency"}, 2: {sorter: "currency"}} });

  $("div.table").hide();

  if(tablename != "") {
    $("div[name='"+tablename+"']").show();
    $("div[name='"+tablename+"']").prev().children().first().text("Hide Table");
  }
  $(".showhide").css("cursor", "pointer");

  var flip = 0;
  //$(".showhide").toggle(function() {

  $(".showhide").click(function() {
    if(flip++ % 2 == 0) {
      tablename = $(this).parent().next().attr("name");
      var tbl = $("#"+tablename); // The <table
      var t = $(this); // The span
      var s = t.parent().next(); // <div class="table"
      if(tbl.length) {
        s.show(); // show the <div class="table"
        t.text("Hide Table");
      } else {
        $("body").css("cursor", "wait");
        t.css("cursor", "wait");

        $.get("$self", { table: tablename }, function(data) {
          $("div[name='"+tablename+"']").append(data);
          $("table").not("#hitCountertbl").addClass('tablesorter'); // attach class tablesorter to all except our counter
          s.show();
          t.text("Hide Table");

          // Switch for the specific table

          switch(tablename) {
            case "osoursite":
              $("#osoursite1, #osoursite2").tablesorter({ sortList: [[1,1]],
                headers: { 1: {sorter: "currency"}, 3: {sorter: "currency"}, 5: {sorter: "currency"} } });
              break;
            case "oslamphost":
              $("#oslamphost1, #oslamphost2").tablesorter({ sortList: [[1,1]],
                headers: { 1: {sorter: "currency"} } });
              break;
            case "pageHits":
              $("#pageHits").tablesorter(); //{ sortList:[[1,1]] }
              break;
            case "ipAgentHits":
              $("#ipAgentHits").tablesorter(); // { sortList:[[4,1]] }

              // Set up the Ip Agent Hits table
              var mlinetext = "<p>You can toggle the display of only members or all visitors "+
                              "<input type='submit' value='Show/Hide NonMembers' "+
                              "id='hideShowNonMembers'><br/> "+
                              "You can toggle the display of the ID "+
                              "<input type='submit' value='Show/Hide ID' id='hideShowIdField' /><br/> "+
                              "Your can taggle the display of the Webmaster "+
                              "<input type='submit' value='Show/Hide Webmaster' "+
                              "id='hideShowWebmaster' /> "+
                              "<p><span class='botmsg' style='color: red; visibility: hidden'>"+
                              "Bots from bots table are red</span></p>";

              $("#ipAgentHits").before(mlinetext);

              // Ip Agent Hits.
              // Hide all ids of zero
              $(".noId").hide();

              // Button to toggle between all and members only in Ip Agent Hits
              // table
              $("#hideShowNonMembers").toggle(function() {
                $(".noId").not("[name='blp']").show();
                $(".botmsg").css("visibility", "visible");
              }, function() {
                $(".noId").hide();
                $(".botmsg").css("visibility", "hidden");
              });

              // Hide the ID field in Ip Agent Hits table
              $("#ipAgentHits td:nth-child(3)").hide();
              $("#ipAgentHits thead th:nth-child(3)").hide();

              // Button to toggle between no ID and showing ID in Ip Agent Hits
              // table
              $("#hideShowIdField").toggle(function() {
                $("#ipAgentHits td:nth-child(3)").show();
                $("#ipAgentHits thead th:nth-child(3)").show();
              }, function() {
                $("#ipAgentHits td:nth-child(3)").hide();
                $("#ipAgentHits thead th:nth-child(3)").hide();
              });

              // Hide the webmaster (me) in the Ip Agent Hits table
              $(".blp").hide();

              // Button to toggle between hide and show of Webmaster in Ip Agents
              // Hits Table
              $("#hideShowWebmaster").toggle(function() {
                $(".blp").show();
              }, function() {
                $(".blp").hide();
              });
              break;

            case "ipHits":
              $("#ipHits").tablesorter(); // {sortList:[[2,1]] }
              // Set up Ip Hits
              $("#ipHits").before("<p>Move the mouse over ID to see the member&apos;s name.<br/> \
              Click to toggle <i>all</i> or <i>members-only</i> \
              <input id='membersonly' type='submit' value='toggle all/members-only' /></p>");

              $("#ipHits tbody tr td:nth-child(2)").hover(function(e) {
                var name = "Non Member";
                if($(this).text() != '0') {
                  name = $("#Id_"+$(this).text()).text();
                }
                $("#popup").text(name).css({ top: e.pageY+20, left: e.pageX }).show();
              }, function() {
                $("#popup").hide();
              });

              // Hide and mark all IDs that are zero in the Ip Hits table
              $("#ipHits tbody tr td:nth-child(2)").each(function() {
                var \$this = $(this);
                if(\$this.text() == '0') \$this.parent().addClass('ipHitsNoId').hide();
              });

              // Members only button toggles members and all in Ip Hits table
              $("#membersonly").toggle(function() {
                $(".ipHitsNoId").show();
              }, function() {
                $(".ipHitsNoId").hide();
              });
              break;
          } // end of switch
          $("body").css("cursor", "default");
          t.css("cursor", "pointer");
        });
      }
    } else {
      $(this).parent().next().hide();
      $(this).text("Show Table");
    });
});

