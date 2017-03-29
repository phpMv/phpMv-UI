<?php
return '<%mTagName% class="%mClass%" %properties%>
<%tagName% id="split-%identifier%" class="%class%">%btnCaption%</%tagName%>
<%tagName% id="%identifier%" class="%class%" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="caret"></span>
</%tagName%>
<ul class="dropdown-menu" role="menu" aria-labelledby="%identifier%">
%items%
</ul>
</%mTagName%>';
