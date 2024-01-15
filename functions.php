<?php
function SelectOptions($options) {
    $html = '<select class="form-control" name="tipos[]" required>' . PHP_EOL;

    foreach ($options as $value => $label) {
        $html .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($label) . '</option>' . PHP_EOL;
    }

    $html .= '</select>' . PHP_EOL;

    return $html;
}

// Exemplo de uso para os tipos de campos
$tiposOptions = array(
    'INT' => 'INT',
    'INT PRIMARY KEY' => 'INT PRIMARY KEY',
    'VARCHAR(255)' => 'VARCHAR(255)',
    'TEXT' => 'TEXT',
    'DATE' => 'DATE',
    'DATETIME' => 'DATETIME',
    'FLOAT' => 'FLOAT',
    'DOUBLE' => 'DOUBLE',
    'DECIMAL' => 'DECIMAL',
    'BOOL' => 'BOOL',
    'TINYINT' => 'TINYINT',
    'SMALLINT' => 'SMALLINT',
    'MEDIUMINT' => 'MEDIUMINT',
    'BIGINT' => 'BIGINT',
    'ENUM(\'value1\', \'value2\', ...)' => 'ENUM',
);

echo SelectOptions($tiposOptions);
?>
