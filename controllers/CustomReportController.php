<?php

class ExportReportsCsvHeaders_CustomReportController extends Pimcore_Controller_Action_Admin_Reports {

    public function init() {
        parent::init();

        $this->checkPermission("reports");
    }
    
    public function downloadCsvAction() {
        set_time_limit(300);

        $sort = $this->getParam("sort");
        $dir = $this->getParam("dir");
        $filters = ($this->_getParam("filter") ? json_decode($this->getParam("filter"), true) : null);
        $drillDownFilters = $this->getParam("drillDownFilters", null);

        $config = Tool_CustomReport_Config::getByName($this->getParam("name"));

        $columns = $config->getColumnConfiguration();
        $fields = array();
        foreach($columns as $column) {
            if($column['export']) {
                $fields[] = $column['name'];
            }
        }

        $configuration = $config->getDataSourceConfig();
        $configuration = $configuration[0];
        $adapter = Tool_CustomReport_Config::getAdapter($configuration, $config);

        $result = $adapter->getData($filters, $sort, $dir, null, null, $fields, $drillDownFilters);

        $exportFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/report-export-" . uniqid() . ".csv";
        @unlink($exportFile);

        $fp = fopen($exportFile, 'w');

        /*
         * Include headers in Csv file
         * 
         */
        $field_name = array();
        foreach($columns as $column) {
            if(!empty($fields) && !empty($column['export'])) {
                if(!empty($column['label'])) {
                    $field_name[] = $column['label'];
                } else {
                    $field_name[] = $column['name'];
                }
            } else {
                if(!empty($column['label'])) {
                    $field_name[] = $column['label'];
                } else {
                    $field_name[] = $column['name'];
                }
            }
        }

	    fputcsv($fp, array_values($field_name));
        
        foreach ($result['data'] as $row) {
            fputcsv($fp, array_values($row));
        }

        fclose($fp);

        header("Content-type: text/plain");
        header("Content-Length: " . filesize($exportFile));
        header("Content-Disposition: attachment; filename=\"export.csv\"");

        while(@ob_end_flush());
        flush();
        readfile($exportFile);

        exit;
    }

}

