<?php
class TLinkPager extends CLinkPager {

    /**
     * 当前页的选中样式
     * @var string
     */
    public $selectedPageCssClass = 'active';

    /**
     * 显示分页链接的个数
     * @var int
     */
    public $maxButtonCount = 7;

    /**
     * 下一页 链接的样式
     * @var string
     */
    public $nextPageLabel = '<i class="ace-icon fa fa-angle-right"></i>';

    /**
     * 上一页 链接的样式
     * @var string
     */
    public $prevPageLabel = '<i class="ace-icon fa fa-angle-left"></i>';

    /**
     * 第一页 链接的样式
     * @var string
     */
    public $firstPageLabel = '<i class="ace-icon fa fa-angle-double-left"></i>';

    /**
     * 最后页 链接的样式
     * @var string
     */
    public $lastPageLabel = '<i class="ace-icon fa fa-angle-double-right"></i>';

    /**
     * 分页头设为空，不显示翻页字样
     * @var string
     */
    public $header = '';

    /**
     * 尾部设置为空
     * @var string
     */
    public $footer = '';

    /**
     * 分页容器 ul 的默认的样式
     * @var string
     */
    public $htmlOptions = array('class' => 'pagination pull-right');

    public function run() {
        $this->registerClientScript();
        $buttons = $this->createPageButtons();

        if (($pageCount = $this->getPageCount()) > 1) {
            $params = $_GET;
            $currPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            unset($params['page']);
            $params = array_unique($params);
            foreach ($params as $key => $val) {
                if (empty($val)) {
                    unset($params[$key]);
                }
            }
            $url = Yii::app()->controller->createPageUrl($params);
        }

        if (empty($buttons))
            return;
        echo $this->header;
        echo CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons));
        echo $this->footer;
    }

}
