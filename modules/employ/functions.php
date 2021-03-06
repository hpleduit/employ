<?php

/**
 * @Project NUKEVIET 4.x
 * @Author KennyNguyen (nguyentiendat713@gmail.com)
 * @Copyright (C) 2016 KennyNguyen .All rights reserved
 * @Website support https://www.nuke.vn
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 22 Sep 2016 13:09:49 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_EMPLOY', true );

global $global_array_cat;
$global_array_cat = array();
$catid = 0;
$parentid = 0;
$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';
$array_mod_title = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
$list = $nv_Cache->db($sql, 'catid', $module_name);
if(!empty($list))
{
    foreach ($list as $l) {
        $global_array_cat[$l['catid']] = $l;
        $global_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
        if ($alias_cat_url == $l['alias']) {
            $catid = $l['catid'];
            $parentid = $l['parentid'];
        }
    }
}

global $global_array_location;
$global_array_location = array();
$location_id = 0;
$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';
$array_mod_title = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_location ORDER BY weight ASC';
$list = $nv_Cache->db($sql, 'location_id', $module_name);
if(!empty($list))
{
    foreach ($list as $l) {
        $global_array_location[$l['location_id']] = $l;
        $global_array_location[$l['location_id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
        if ($alias_cat_url == $l['alias']) {
            $location_id = $l['location_id'];
        }
    }
}


global $global_array_salary;
$global_array_salary = array();
$sid = 0;
$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';
$array_mod_title = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_salary_cat ORDER BY weight ASC';
$list = $nv_Cache->db($sql, 'sid', $module_name);
if(!empty($list))
{
    foreach ($list as $l) {
        $global_array_salary[$l['sid']] = $l;
        $global_array_salary[$l['sid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
        if ($alias_cat_url == $l['alias']) {
            $sid = $l['sid'];
        }
    }
}

global $global_array_age;
$global_array_age = array();
$aid = 0;
$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';
$array_mod_title = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_age_cat ORDER BY weight ASC';
$list = $nv_Cache->db($sql, 'aid', $module_name);
if(!empty($list))
{
    foreach ($list as $l) {
        $global_array_age[$l['aid']] = $l;
        $global_array_age[$l['aid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
        if ($alias_cat_url == $l['alias']) {
            $aid = $l['aid'];
        }
    }
}


//Xac dinh RSS
if ($module_info['rss']) {
    $rss[] = array(
        'title' => $module_info['custom_title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss']
    );
}

foreach ($global_array_cat as $catid_i => $array_cat_i) {
    if ($catid_i > 0 and $array_cat_i['parentid'] == 0) {
        $act = 0;
        $submenu = array();
        if ($catid_i == $catid or $catid_i == $parentid) {
            $act = 1;
            if (! empty($global_array_cat[$catid_i]['subcatid'])) {
                $array_catid = explode(',', $global_array_cat[$catid_i]['subcatid']);
                foreach ($array_catid as $sub_catid_i) {
                    $array_sub_cat_i = $global_array_cat[$sub_catid_i];
                    $sub_act = 0;
                    if ($sub_catid_i == $catid) {
                        $sub_act = 1;
                    }
                    $submenu[] = array( $array_sub_cat_i['title'], $array_sub_cat_i['link'], $sub_act );
                }
            }
        }
        $nv_vertical_menu[] = array( $array_cat_i['title'], $array_cat_i['link'], $act, 'submenu' => $submenu );
    }

    //Xac dinh RSS
    if ($catid_i and $module_info['rss']) {
        $rss[] = array(
            'title' => $module_info['custom_title'] . ' - ' . $array_cat_i['title'],
            'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'] . '/' . $array_cat_i['alias']
        );
    }
}
unset($result, $catid_i, $parentid_i, $title_i, $alias_i);

$module_info['submenu'] = 0;

$page = 1;
$per_page = $module_config[$module_name]['per_page'];
$st_links = $module_config[$module_name]['st_links'];
$count_op = sizeof($array_op);
if (! empty($array_op) and $op == 'main') {
    $op = 'main';
    if ($count_op == 1 or substr($array_op[1], 0, 5) == 'page-') {
        if ($count_op > 1 or $catid > 0) {
            $op = 'viewcat';
            if( isset($array_op[1]) and substr($array_op[1], 0, 5) == 'page-' ){
                $page = intval(substr($array_op[1], 5));   
            }
        }
        elseif ($catid == 0) {
            $contents = $lang_module['nocatpage'] . $array_op[0];       
            if (isset($array_op[0]) and substr($array_op[0], 0, 5) == 'page-') {
                $page = intval(substr($array_op[0], 5));
            }
        }
    } elseif ($count_op == 2) {
        $array_page = explode('-', $array_op[1]);
        $alias_url = $array_op[1];
        if ($alias_url != '') {
            if ($catid > 0) {
				$op = 'detail';
			} else {
				//muc tieu neu xoa chuyen muc cu hoac doi ten alias chuyen muc thi van rewrite duoc bai viet
				$_row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE alias = ' . $db->quote($alias_url) )->fetch();
				if (!empty($_row) and isset($global_array_cat[$_row['catid']])) {
    				$url_Permanently = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$_row['catid']]['alias'] . '/' . $_row['alias'] . $global_config['rewrite_exturl'], true );
    				header( "HTTP/1.1 301 Moved Permanently" );
    				header( 'Location:' . $url_Permanently );
    				exit();
				}
			}
        }
    }
    $parentid = $catid;
    while ($parentid > 0) {
        $array_cat_i = $global_array_cat[$parentid];
        $array_mod_title[] = array(
            'catid' => $parentid,
            'title' => $array_cat_i['title'],
            'link' => $array_cat_i['link']
        );
        $parentid = $array_cat_i['parentid'];
    }
    sort($array_mod_title, SORT_NUMERIC);
}
