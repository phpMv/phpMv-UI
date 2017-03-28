<?php
namespace Ajax\semantic\widgets\datatable;
use Ajax\common\BaseEnum;

abstract class PositionInTable extends BaseEnum {
	const BEFORETABLE="beforeTable",AFTERTABLE="afterTable",HEADER="thead",FOOTER="tfoot",BODY="tbody";
}
