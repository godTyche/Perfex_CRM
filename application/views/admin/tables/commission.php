<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'subject',
    'amount',
    'datecreated',
    ];
$sIndexColumn     = 'articleid';
$sTable           = db_prefix() . 'commissions';
$additionalSelect = [
    'name',
    'groupid',
    'articleid',
    'slug',
    'staff_article',
    'amount',
     db_prefix() . 'commissions.description',
    ];
$join = [
    'LEFT JOIN ' . db_prefix() . 'commissions_groups ON ' . db_prefix() . 'commissions_groups.groupid = ' . db_prefix() . 'commissions.articlegroup',
    ];

$where   = [];
$filter  = [];
$groups  = $this->ci->commissions_model->get_kbg();
$_groups = [];
foreach ($groups as $group) {
    if ($this->ci->input->post('kb_group_' . $group['groupid'])) {
        array_push($_groups, $group['groupid']);
    }
}
if (count($_groups) > 0) {
    array_push($filter, 'AND articlegroup IN (' . implode(', ', $_groups) . ')');
}
if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if (!has_permission('commissions', '', 'create') && !has_permission('commissions', '', 'edit')) {
    array_push($where, ' AND ' . db_prefix() . 'commissions.active=1');
}

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'articlegroup') {
            $_data = $aRow['name'];
        } elseif ($aColumns[$i] == 'subject') {
            $link = admin_url('commissions/view/' . $aRow['articleid']);
            if (has_permission('commissions', '', 'edit')) {
                $_data = '<a href="' . admin_url('commissions/view/' . $aRow['articleid']) . '">' . $_data . '</a>';
            } else {
                $_data = '<a href="' . $link . '" target="_blank">' . $_data . '</a>';
            }

            if ($aRow['staff_article'] == 1) {
                $_data .= '<span class="label label-default pull-right">' . _l('internal_article') . '</span>';
            }

            $_data .= '<div class="row-options">';

            $_data .= '<a href="' . $link . '" target="_blank">' . _l('view') . '</a>';

            if (has_permission('commissions', '', 'edit')) {
                $_data .= ' | <a href="' . admin_url('commissions/article/' . $aRow['articleid']) . '">' . _l('edit') . '</a>';
            }

            if (has_permission('commissions', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('commissions/delete/' . $aRow['articleid']) . '" class="_delete text-danger">' . _l('delete') . '</a>';
            }

            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'datecreated') {
            $_data = _dt($_data);
        }

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
    }

    $output['aaData'][] = $row;
}