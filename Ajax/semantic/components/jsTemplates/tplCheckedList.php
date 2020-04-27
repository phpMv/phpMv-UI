<?php
return '
var deferredEach=function (arr, batchSize) {
var deferred = $.Deferred();var index = 0;
function chunk () {var lastIndex = Math.min(index + batchSize, arr.length);
	for(;index<lastIndex;index++){deferred.notify(index, arr[index]);}
    if (index >= arr.length) {deferred.resolve();} else {setTimeout(chunk, 0);}
};
setTimeout(chunk, 0);return deferred.promise();
};
$("%identifier% .master")
  .checkbox({
    onChecked: function() {if($(this).closest(".loading").length==0 && !$(this).hasClass("_notAllChecked")){$(this).closest(".checkbox").siblings(".list").find(".checkbox").checkbox("check");}},
    onUnchecked: function() {if($(this).closest(".loading").length==0){$(this).closest(".checkbox").siblings(".list").find(".checkbox").checkbox("uncheck");}}
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
		$listGroup.closest(".segment").addClass("loading");
		deferredEach($checkbox,5).progress(function(index, item){
        if( $(item).checkbox("is checked") ) {
          allUnchecked = false;
        }
        else {
          allChecked = false;
        }
		if(!allUnchecked && !allChecked) return;
      }).done(function(){
		$listGroup.closest(".segment.loading").removeClass("loading");
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
	});
    }
  })
;';
