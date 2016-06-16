<?php
return '
<nav class="navbar %class%" id="%identifier%">
  <div class="%fluid%">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#%identifier%">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="%brandHref%" target="%brandTarget%">%brand%%brandImage%</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="collapse-%identifier%">
%navZones%
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>';