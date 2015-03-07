<?php
/**
 * 配置显示的所有的模块
 */
return array(

    'Custom' => array(
        'label' => '客户管理', 'icon' => 'menu-icon fa fa-group', 'url' => array('/Custom/article/checkPiv'),
        'items' => array(
              array('label' => '查询分配', 'url' => array('/site/index')),
              array('label' => '客户资源分配', 'url' => array('/site/page', 'view' => 'about')),
              array('label' => '公海资源', 'url' => array('/site/contact')),
          ),
    ),
   
    'Chance' => array(
        'label' => '机会管理', 'icon' => 'menu-icon fa fa-stack-exchange','url' => array('/Chance/'),
         'items' => array(
                    array('label' => '安排联系机会', 'url' => array('/site/index')),
                    array('label' => '我的机会', 'url' => array('/site/page', 'view' => 'about')),
                    array('label' => '未联系机会', 'url' => array('/site/contact')),
           ),
    ),
  

    
     'Report' => array(
        'label' => '报表分析','icon' => 'menu-icon fa fa-table', 'url' => array('/Report/'),
        'items' => array(
                    array('label' => '业绩报表', 'url' => array('/site/index')),
                    array('label' => '联系量统计', 'url' => array('/site/page', 'view' => 'about')),
                    array('label' => '话务员工作统计', 'url' => array('/site/contact')),
                    array('label' => '安排时间分布', 'url' => array('/site/contact')),
                    array('label' => '开3, 4类跟踪分析', 'url' => array('/site/contact')),
                    array('label' => '新分资源跟踪分析', 'url' => array('/site/contact')),
                    array('label' => '成交师开14，15，17类跟踪分析', 'url' => array('/site/contact')),
                    array('label' => '资源录入统计', 'url' => array('/site/contact')),
                    array('label' => '售后-联系量统计', 'url' => array('/site/contact')),
                    array('label' => '售后-新分资源跟踪分析', 'url' => array('/site/contact')),
                    array('label' => '售后-续费会员分析', 'url' => array('/site/contact')),
                ),
    ),
    
     'User' => array(
        'label' => '权限管理','icon' => 'menu-icon fa fa-key', 'url' => array('/User/'),
        'items' => array(
                array('label' => '用户管理', 'url' => array('/User/users/index')),
                array('label' => '部门管理', 'url' => array('/site/page', 'view' => 'about')),
                array('label' => '组别管理', 'url' => array('/site/contact')),
                array('label' => '部门组别管理', 'url' => array('/site/contact')),
                array('label' => '菜单资源管理', 'url' => array('/site/contact')),
                array('label' => '角色管理', 'url' => array('/site/contact')),
                array('label' => '权限配置', 'url' => array('/site/contact')),
            ),
    ),
    
     'Finance' => array(
        'label' => '财务数据', 'icon' => 'menu-icon fa fa-list-alt','url' => array('/Finance/'),
         'items' => array(
            array('label' => '财务数据录入', 'url' => array('/Finance/finance/create')),
            array('label' => '财务数据查询', 'url' => array('/Finance/finance/index')),
        ),
    ),
   
      'Service' => array(
        'label' => '售后管理', 'icon' => 'menu-icon  fa fa-user-md','url' => array('/Service/'),
         'items' => array(
            array('label' => '新分客户', 'url' => array('/site/index')),
            array('label' => '今日联系', 'url' => array('/site/page', 'view' => 'about')),
            array('label' => '遗留数据', 'url' => array('/site/page', 'view' => 'about')),
            array('label' => '查询分配', 'url' => array('/site/page', 'view' => 'about')),
        ),
    ),
);