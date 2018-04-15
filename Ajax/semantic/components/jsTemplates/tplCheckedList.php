<?php
return '$("%identifier% .master")
  .checkbox({
    onChecked: function() {if(!$(this).hasClass("_notAllChecked")){$(this).closest(".checkbox").siblings(".list").find(".checkbox").checkbox("check");}},
    onUnchecked: function() {$(this).closest(".checkbox").siblings(".list").find(".checkbox").checkbox("uncheck");}
  })
;
$("%identifier% .list .child.checkbox")
  .checkbox({
    fireOnInit : %fireOnInit%,
    onChange   : function() {
      var $listGroup      = $(this).closest(".list"),
        $parentCheckbox = $listGroup.closest(".item").children(".checkbox"),
        $checkbox       = $listGroup.find(".checkbox"),
        allChecked      = true,
        allUnchecked    = true;
      $checkbox.each(function() {
        if( $(this).checkbox("is checked") ) {
          allUnchecked = false;
        }
        else {
          allChecked = false;
        }
      });
      if(allChecked) {
        $parentCheckbox.checkbox("set checked");
      }
      else if(allUnchecked) {
		if(!$parentCheckbox.children("input").first().hasClass("_notAllChecked")){
        	$parentCheckbox.checkbox("set unchecked");
		}
      }
      else {
        $parentCheckbox.checkbox("set indeterminate");
      }
		%onChange%
    }
  })
;';
