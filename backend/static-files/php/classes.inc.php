<?php


class Option {
	public string $label;
	public string $value;

	public function __construct($label, $value) {
		$this->label = $label;
		$this->value = $value;
	}
}

class SurveyItem {
	private string $type;
	private string $tblName;
	private string $colName;
	private string $desc;
	private $options;

	public function __construct($type, $colName, $desc, $options=null){
		$this->type    = $type;
		$this->tblName = 'participant';

		if ($type == 'dropdown' || $type == 'checkbox assoc tbl') {
			$this->tblName = "$colName assoc tbl";
			$colName .= ' id';
		}

		$this->colName = str_replace(' ', '-', $colName);

		$this->desc    = $desc;

		if ($type == 'radio') {
			$this->options = $options;
		}
	}

	public function echoHtml() {
		$extraClass = $this->type == 'checkbox bool' ? ' checkbox-bool' : '';
		$html = "<div class = \"survey-item$extraClass\">";

		$tblNameHyph = str_replace(' ', '-', $this->tblName);
		$nameVal = "$tblNameHyph.$this->colName";

		$id = "id=\"$this->tblName.$this->colName.label\"";

		$html .= "<label $id class = \"survey-item-label\">\n";
		$html .= "\t$this->desc\n";
		$html .= "</label>\n";

		switch ($this->type) {
			case "radio":
				foreach ($this->options as $option) {
					$idVal   = "$nameVal.$option->value";
					$id      = "id    = \"$idVal\"";
					$name    = "name  = \"$nameVal\"";
					$value   = "value = \"$option->value\"";
					$type    = 'type  = "radio"';
					
					$html .= "<input $name $value $id $type required>\n";
					$html .= "\n";
					
					// ------------------------------------------

					$for   = "for = \"$idVal\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>\n";
				}
				break;

			case "textbox":
				$name   = "name = \"$nameVal\"";
				$id = "$tblNameHyph.$this->colName";
				
				$html .= "<input $name class=\"textbox\" $id type = \"text\" required>";

				break;

			case "dropdown":
				$this->options = readRefTbl($this->tblName);
				$name = "name = \"$nameVal\"";
				$html .= "<select $name id=\"$nameVal\">";

				foreach ($this->options as $option) {
					$idVal  = "$nameVal.$option->value";
					$id     = "id = \"$idVal\"";
					$value  = "value = \"$option->value\"";
					$for    = "for = \"$idVal\"";
					
					$html .= " $<option $value $id required>$option->label</option>";
					$html .= "\n";
				}

				$html .= "</select>";

				break;

			case "checkbox bool":
				$idVal  = "$nameVal.1";
				$id     = "id = \"$idVal\"";
				$name   = "name = \"$idVal\"";
				$value  = "value = \"1\"";
				$for    = "for = \"$idVal\"";
				$type   = 'type = "checkbox"';
				
				$html .= "<input $name $value $id $type>";
				$html .= "\n";

				// ------------------------------------------

				$for   = "for = \"$idVal\"";

				$html .= "<label $for>\n";
				$html .= "\t$this->desc";
				$html .= "</label>";
				$html .= "<br>";

				break;

			case "checkbox assoc tbl":
				// "options" in this context refers to checkboxes
				$this->options = readRefTbl($this->tblName);
				
				foreach ($this->options as $option) {
					$idVal  = "$nameVal.$option->value";
					$id     =    "id = \"$idVal\"";
					$name   =  "name = \"$idVal\"";
					$value  = "value = \"$option->value\"";
					$for    =   "for = \"$idVal\"";
					$type   =  'type = "checkbox"';
					
					$html .= "<input $name $value $id $type>";
					$html .= "\n";

					// ------------------------------------------

					$for   = "for = \"$idVal\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>";
					$html .= "<br>";
				}

				$html .= "</select>";

				break;

			case "textarea":
				$name = "name = \"$nameVal\"";
				$id = "\"$this->colName\"";
				
				$html .= "<textarea $name $id rows = \"8\" cols = \"60\" placeholder=\"Enter optional response\">\n";
				$html .= "</textarea>\n";
				break;
		}

		$html .= "</div>\n";
		$html .= "\n";
		echo $html;
	}
}