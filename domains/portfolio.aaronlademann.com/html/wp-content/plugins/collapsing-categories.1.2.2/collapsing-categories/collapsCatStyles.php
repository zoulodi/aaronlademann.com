<?php
$style="#sidebar span.collapsing.categories {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsing.categories.item a.self {font-weight:bold}
#sidebar ul.collapsing.categories.list ul.collapsing.categories.list:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.item:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.item {list-style-type:none}
#sidebar ul.collapsing.categories.list li.collapsing.categories {
       text-indent:-1em;
       padding-left:1em;
       margin:0;
}
#sidebar ul.collapsing.categories.list li.collapsing.categories.item:before {content: '\\\\00BB \\\\00A0' !important;} 
#sidebar ul.collapsing.categories.list .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    cursor:pointer;
    padding-right:5px;}";

$default=$style;

$block="#sidebar li.collapsing.categories.item a {
            display:inline-block;
            text-decoration:none;
            margin:0;
            padding:0;
            }
#sidebar li.collapsing.categories.item ul li.collapsing.categories.item a {
            display:block;
}
#sidebar li.collapsing.categories.item a:hover {
            background:#CCC;
            text-decoration:none;
          }
#sidebar span.collapsing.categories {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsing.categories.item a.self {font-weight:bold}
#sidebar ul.collapsing.categories.list ul.collapsing.categories.list:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.item:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.item {list-style-type:none}
#sidebar ul.collapsing.categories.list li.collapsing.categories.item {
      }
#sidebar ul.collapsing.categories.list .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    float:left;
    cursor:pointer;
    padding-right:5px;
}
";

$noArrows="#sidebar span.collapsing.categories {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar li.collapsing.categories.item a.self {font-weight:bold}
#sidebar ul.collapsing.categories.list ul.collapsing.categories.list:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.item:before {content:'';} 
#sidebar ul.collapsing.categories.list li.collapsing.categories.item {list-style-type:none}
#sidebar ul.collapsing.categories.list .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    cursor:pointer;
    padding-right:5px;}";
$selected='default';
$custom=get_option('collapsCatStyle');
?>
