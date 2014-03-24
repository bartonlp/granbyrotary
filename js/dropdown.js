<!--
// Copyright (C) 2005-2008 Ilya S. Lyubinskiy. All rights reserved.
// Technical support: http://www.php-development.ru/
//
// YOU MAY NOT
// (1) Remove or modify this copyright notice.
// (2) Re-distribute this code or any part of it.
//     Instead, you may link to the homepage of this code:
//     http://www.php-development.ru/javascripts/dropdown.php
//
// YOU MAY
// (1) Use this code on your website.
// (2) Use this code as part of another product.
//
// NO WARRANTY
// This code is provided "as is" without warranty of any kind.
// You expressly acknowledge and agree that use of this code is at your own risk.


// ***** Popup Control *********************************************************

var disp;
var disptype;
var postype;

// ***** at_show_aux *****

function at_show_aux(parent, child)
{
  // get a refference to the parent and chile

  var p = document.getElementById(parent);
  var c = document.getElementById(child );

  var top  = (c["at_position"] == "y") ? p.offsetHeight+2 : 0;
  var left = (c["at_position"] == "x") ? p.offsetWidth +2 : 0;

  for (; p; p = p.offsetParent)
  {
    top  += p.offsetTop;
    left += p.offsetLeft;
//    alert("tag="+p.tagName+" offsetTop="+p.offsetTop+ " offsetLeft="+p.offsetLeft)
  }
  
  c.style.position = postype;
  
  c.style.top = (postype == 'absolute') ? top +'px' : '-30px';
    
//  c.style.top        = top +'px';
//  c.style.top        = '-30px';

  if(postype == 'absolute')
    c.style.left = left+'px';

//  alert("top="+c.style.top+" left="+c.style.left+" disp="+disp);

  c.style.visibility = "visible";

  if(disptype)
    c.style.display = disp;
}

// ***** at_show *****

function at_show()
{
  var p = document.getElementById(this["at_parent"]);
  var c = document.getElementById(this["at_child" ]);

  at_show_aux(p.id, c.id);

  clearTimeout(c["at_timeout"]);
}

// ***** at_hide *****

function at_hide()
{
  var p = document.getElementById(this["at_parent"]);
  var c = document.getElementById(this["at_child" ]);

  var s = "timeout('"+c.id+"')";
  c["at_timeout"] = setTimeout(s, 2000);
  
  //setTimeout("document.getElementById('"+c.id+"').style.visibility = 'hidden'", 333);
}

// Timeout

function timeout(cc) {
  var c = document.getElementById(cc);
  c.style.visibility = 'hidden';
  if(disptype)
    c.style.display = 'none';
}

// ***** at_click *****

function at_click()
{
  var p = document.getElementById(this["at_parent"]);
  var c = document.getElementById(this["at_child" ]);

  if (c.style.visibility != "visible") {
    at_show_aux(p.id, c.id);
  } else {
    c.style.visibility = "hidden";
    if(disptype)
      c.style.display = "none";
  }
  return false;
}

// ***** at_attach *****

// PARAMETERS:
// parent   - id of the parent html element
// child    - id of the child  html element that should be droped down
// showtype - "click" = drop down child html element on mouse click
//            "hover" = drop down child html element on mouse over
// position - "x" = display the child html element to the right
//            "y" = display the child html element below
// cursor   - omit to use default cursor or specify CSS cursor name
// BLP added
// type     - 'relative' or 'absolute', default is absolute
// mode     - 0 for only visibility, 1 use display
// mode only valid if 'relative'. If mode == 1 then the space for the
// child is NOT displayed when no click or hover.

function at_attach(parent, child, showtype, position, cursor, type, mode)
{
  var p = document.getElementById(parent);
  var c = document.getElementById(child);

  p["at_parent"]     = p.id;
  c["at_parent"]     = p.id;
  p["at_child"]      = c.id;
  c["at_child"]      = c.id;
  p["at_position"]   = position;
  c["at_position"]   = position;

  //alert ("type="+type+", mode="+mode);

  postype = type ? type : 'absolute';
  c.style.position   = postype;

  c.style.visibility = "hidden";

  
  if(mode == 1) {
    disptype = true;
    disp = c.style.display;
    c.style.display = "none";
  }

  switch (showtype)
  {
    case "click":
      p.onclick     = at_click;
      p.onmouseout  = at_hide;
      c.onmouseover = at_show;
      c.onmouseout  = at_hide;
      break;
    case "clickonly":
      p.onclick     = at_click;
      //p.onmouseout  = at_hide;
      //c.onmouseover = at_show;
      //c.onmouseout  = at_hide;
      break;

    case "hover":
      p.onmouseover = at_show;
      p.onmouseout  = at_hide;
      c.onmouseover = at_show;
      c.onmouseout  = at_hide;
      break;
  }
}
//-->