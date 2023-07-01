<?php

if (!function_exists('load_excel')) {
    function load_excel($file_path)
    {
        require_once APPPATH . 'third_party/phpoffice/phpspreadsheet/src/Bootstrap.php';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file_path);
        $worksheet = $spreadsheet->getActiveSheet();

        $data = [];
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $worksheet->getCell($col . $row)->getValue();
                $rowData[] = $cellValue;
            }
            $data[] = $rowData;
        }

        return $data;
    }
}
