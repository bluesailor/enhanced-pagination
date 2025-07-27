<?php
// File: /classes/EnhancedPagination.php

/**
 * 增强版分页类
 * 支持国际化、跳转功能、移动端优化、多CSS框架
 */
class EnhancedPagination
{
    /** @var int 总记录数 */
    public $total;
    
    /** @var int 每页条数 */
    public $pageSize;
    
    /** @var int 当前页码 */
    public $current;
    
    /** @var array 额外GET参数 */
    public $query;
    
    /** @var int 显示多少页范围 */
    public $range;
    
    /** @var string 分页大小 (sm, md, lg) */
    public $size;
    
    /** @var bool 是否显示统计信息 */
    public $showStats;
    
    /** @var string 分页对齐方式 (start, center, end) */
    public $alignment;
    
    /** @var string CSS框架类型 (bootstrap, tailwind, custom) */
    public $framework;
    
    /** @var string 语言 (zh-cn, en, ja, ko, etc.) */
    public $language;
    
    /** @var bool 是否显示跳转功能 */
    public $showJumper;
    
    /** @var bool 是否启用移动端优化 */
    public $mobileOptimized;
    
    /** @var array 国际化文本 */
    protected $i18n = [
        'zh-cn' => [
            'previous' => '上一页',
            'next' => '下一页',
            'first' => '首页',
            'last' => '尾页',
            'goto' => '跳转到',
            'page' => '页',
            'go' => '确定',
            'total_records' => '共 :total 条记录',
            'total_pages' => ':pages 页',
            'current_page' => '当前第 :current 页',
            'showing' => '显示第 :from 到 :to 项结果',
            'of' => '共',
            'results' => '条结果',
            'page_size' => '每页显示',
            'items' => '条'
        ],
        'en' => [
            'previous' => 'Previous',
            'next' => 'Next',
            'first' => 'First',
            'last' => 'Last',
            'goto' => 'Go to',
            'page' => 'Page',
            'go' => 'Go',
            'total_records' => 'Total :total records',
            'total_pages' => ':pages pages',
            'current_page' => 'Current page :current',
            'showing' => 'Showing :from to :to of',
            'of' => 'of',
            'results' => 'results',
            'page_size' => 'Show',
            'items' => 'items per page'
        ],
        'ja' => [
            'previous' => '前へ',
            'next' => '次へ',
            'first' => '最初',
            'last' => '最後',
            'goto' => 'ページへ移動',
            'page' => 'ページ',
            'go' => '移動',
            'total_records' => '全 :total 件',
            'total_pages' => ':pages ページ',
            'current_page' => '現在 :current ページ目',
            'showing' => ':from から :to を表示',
            'of' => '全',
            'results' => '件中',
            'page_size' => '表示件数',
            'items' => '件'
        ]
    ];
    
    /**
     * 构造函数
     */
    public function __construct(
        $total, 
        $current = 1, 
        $pageSize = 20, 
        $query = [], 
        $options = []
    ) {
        // 基础参数
        $this->total = max(0, intval($total));
        $this->pageSize = max(1, intval($pageSize));
        $this->current = max(1, intval($current));
        $this->query = is_array($query) ? $query : [];
        
        // 可选参数
        $this->range = max(1, intval($options['range'] ?? 3));
        $this->size = in_array($options['size'] ?? '', ['sm', 'lg']) ? $options['size'] : '';
        $this->showStats = (bool)($options['showStats'] ?? true);
        $this->alignment = in_array($options['alignment'] ?? 'center', ['start', 'center', 'end']) ? $options['alignment'] : 'center';
        $this->framework = in_array($options['framework'] ?? 'bootstrap', ['bootstrap', 'tailwind', 'custom']) ? $options['framework'] : 'bootstrap';
        $this->language = $options['language'] ?? 'zh-cn';
        $this->showJumper = (bool)($options['showJumper'] ?? true);
        $this->mobileOptimized = (bool)($options['mobileOptimized'] ?? true);
        
        // 确保当前页不超过总页数
        $totalPages = $this->getTotalPages();
        $this->current = min($this->current, $totalPages);
    }
    
    /**
     * 获取总页数
     */
    public function getTotalPages()
    {
        return max(1, ceil($this->total / $this->pageSize));
    }
    
    /**
     * 获取国际化文本
     */
    protected function getText($key, $params = [])
    {
        $lang = $this->i18n[$this->language] ?? $this->i18n['zh-cn'];
        $text = $lang[$key] ?? $key;
        
        foreach ($params as $param => $value) {
            $text = str_replace(':' . $param, $value, $text);
        }
        
        return $text;
    }
    
    /**
     * 获取分页数据
     */
    public function getInfo()
    {
        $totalPages = $this->getTotalPages();
        $current = $this->current;
        
        $start = ($current - 1) * $this->pageSize;
        $limit = $this->pageSize;
        
        return [
            'total' => $this->total,
            'total_pages' => $totalPages,
            'current' => $current,
            'page_size' => $this->pageSize,
            'has_prev' => ($current > 1),
            'has_next' => ($current < $totalPages),
            'start' => $start,
            'limit' => $limit,
            'from' => min($this->total, $start + 1),
            'to' => min($this->total, $start + $this->pageSize),
            'prev_page' => $current > 1 ? $current - 1 : null,
            'next_page' => $current < $totalPages ? $current + 1 : null,
        ];
    }
    
    /**
     * 构建URL
     */
    protected function buildUrl($page)
    {
        $params = $this->query;
        if ($page > 1) {
            $params['page'] = $page;
        } else {
            unset($params['page']);
        }
        return empty($params) ? '?' : '?' . http_build_query($params);
    }
    
    /**
     * 获取页码范围
     */
    protected function getPageRange()
    {
        $totalPages = $this->getTotalPages();
        $current = $this->current;
        $range = $this->range;
        
        $start = max(1, $current - $range);
        $end = min($totalPages, $current + $range);
        
        // 如果显示的页码数量不足，尝试补充
        $showCount = $end - $start + 1;
        $maxShow = min($totalPages, $range * 2 + 1);
        
        if ($showCount < $maxShow) {
            if ($start == 1) {
                $end = min($totalPages, $start + $maxShow - 1);
            } elseif ($end == $totalPages) {
                $start = max(1, $end - $maxShow + 1);
            }
        }
        
        return [$start, $end];
    }
    
    /**
     * Bootstrap 5.3 版本
     */
    protected function renderBootstrap()
    {
        $info = $this->getInfo();
        $totalPages = $info['total_pages'];
        $current = $info['current'];
        
        if ($totalPages <= 1 && empty($this->query)) {
            return '';
        }
        
        $sizeClass = $this->size ? "pagination-{$this->size}" : '';
        $alignClass = "justify-content-{$this->alignment}";
        
        $html = '<div class="pagination-wrapper">';
        
        // 移动端优化：简化版分页
        if ($this->mobileOptimized) {
            $html .= '<div class="d-block d-md-none">';
            $html .= $this->renderMobileBootstrap($info);
            $html .= '</div>';
            $html .= '<div class="d-none d-md-block">';
        }
        
        $html .= '<nav aria-label="' . $this->getText('page') . '">';
        $html .= '<ul class="pagination ' . $sizeClass . ' ' . $alignClass . ' m-0">';
        
        // 首页
        if ($current > 1) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . htmlspecialchars($this->buildUrl(1)) . '" title="' . $this->getText('first') . '">';
            $html .= '<i class="bi bi-chevron-double-left"></i><span class="d-none d-sm-inline"> ' . $this->getText('first') . '</span>';
            $html .= '</a></li>';
        }
        
        // 上一页
        if ($info['has_prev']) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . htmlspecialchars($this->buildUrl($current - 1)) . '" aria-label="' . $this->getText('previous') . '">';
            $html .= '<span aria-hidden="true"><i class="bi bi-chevron-left"></i></span><span class="d-none d-sm-inline"> ' . $this->getText('previous') . '</span>';
            $html .= '</a></li>';
        }
        
        // 页码
        [$start, $end] = $this->getPageRange();
        
        // 左省略号
        if ($start > 1) {
            if ($start > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // 中间页码
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $current) {
                $html .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($this->buildUrl($i)) . '">' . $i . '</a></li>';
            }
        }
        
        // 右省略号
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // 下一页
        if ($info['has_next']) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . htmlspecialchars($this->buildUrl($current + 1)) . '" aria-label="' . $this->getText('next') . '">';
            $html .= '<span class="d-none d-sm-inline">' . $this->getText('next') . ' </span><span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>';
            $html .= '</a></li>';
        }
        
        // 尾页
        if ($current < $totalPages) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . htmlspecialchars($this->buildUrl($totalPages)) . '" title="' . $this->getText('last') . '">';
            $html .= '<span class="d-none d-sm-inline">' . $this->getText('last') . ' </span><i class="bi bi-chevron-double-right"></i>';
            $html .= '</a></li>';
        }
        
        $html .= '</ul></nav>';
        
        if ($this->mobileOptimized) {
            $html .= '</div>';
        }
        
        // 跳转功能
        if ($this->showJumper && $totalPages > 1) {
            $html .= $this->renderJumperBootstrap($totalPages);
        }
        
        // 统计信息
        if ($this->showStats) {
            $html .= $this->renderStatsBootstrap($info);
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 移动端 Bootstrap 版本
     */
    protected function renderMobileBootstrap($info)
    {
        $html = '<div class="d-flex justify-content-between align-items-center">';
        
        // 上一页
        if ($info['has_prev']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($info['current'] - 1)) . '" class="btn btn-outline-primary btn-sm">';
            $html .= '<i class="bi bi-chevron-left"></i> ' . $this->getText('previous');
            $html .= '</a>';
        } else {
            $html .= '<span class="btn btn-outline-secondary btn-sm disabled">';
            $html .= '<i class="bi bi-chevron-left"></i> ' . $this->getText('previous');
            $html .= '</span>';
        }
        
        // 页码信息
        $html .= '<span class="small text-muted">';
        $html .= $info['current'] . ' / ' . $info['total_pages'];
        $html .= '</span>';
        
        // 下一页
        if ($info['has_next']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($info['current'] + 1)) . '" class="btn btn-outline-primary btn-sm">';
            $html .= $this->getText('next') . ' <i class="bi bi-chevron-right"></i>';
            $html .= '</a>';
        } else {
            $html .= '<span class="btn btn-outline-secondary btn-sm disabled">';
            $html .= $this->getText('next') . ' <i class="bi bi-chevron-right"></i>';
            $html .= '</span>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Bootstrap 跳转功能
     */
    protected function renderJumperBootstrap($totalPages)
    {
        $html = '<div class="pagination-jumper mt-2 d-flex justify-content-center align-items-center gap-2">';
        $html .= '<span class="small text-muted">' . $this->getText('goto') . '</span>';
        $html .= '<input type="number" class="form-control form-control-sm pagination-jump-input" ';
        $html .= 'style="width: 80px;" min="1" max="' . $totalPages . '" value="' . $this->current . '">';
        $html .= '<span class="small text-muted">' . $this->getText('page') . '</span>';
        $html .= '<button type="button" class="btn btn-sm btn-outline-primary pagination-jump-btn">' . $this->getText('go') . '</button>';
        $html .= '</div>';
        
        $html .= $this->renderJumpScript();
        
        return $html;
    }
    
    /**
     * Bootstrap 统计信息
     */
    protected function renderStatsBootstrap($info)
    {
        $html = '<div class="pagination-stats text-center mt-2">';
        $html .= '<small class="text-muted">';
        
        if ($this->total > 0) {
            $html .= $this->getText('showing', [
                'from' => $info['from'],
                'to' => $info['to']
            ]);
            $html .= ' ' . $this->getText('of') . ' ';
            $html .= $this->getText('total_records', ['total' => $info['total']]);
            $html .= '，' . $this->getText('total_pages', ['pages' => $info['total_pages']]);
        } else {
            $html .= $this->getText('total_records', ['total' => 0]);
        }
        
        $html .= '</small>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Tailwind CSS 版本
     */
    protected function renderTailwind()
    {
        $info = $this->getInfo();
        $totalPages = $info['total_pages'];
        $current = $info['current'];
        
        if ($totalPages <= 1 && empty($this->query)) {
            return '';
        }
        
        $sizeClasses = [
            'sm' => 'px-2 py-1 text-xs',
            '' => 'px-3 py-2 text-sm',
            'lg' => 'px-4 py-3 text-base'
        ];
        $sizeClass = $sizeClasses[$this->size] ?? $sizeClasses[''];
        
        $alignClasses = [
            'start' => 'justify-start',
            'center' => 'justify-center',
            'end' => 'justify-end'
        ];
        $alignClass = $alignClasses[$this->alignment];
        
        $html = '<div class="pagination-wrapper">';
        
        // 移动端优化
        if ($this->mobileOptimized) {
            $html .= '<div class="block md:hidden">';
            $html .= $this->renderMobileTailwind($info);
            $html .= '</div>';
            $html .= '<div class="hidden md:block">';
        }
        
        $html .= '<nav class="flex items-center ' . $alignClass . ' space-x-1" aria-label="' . $this->getText('page') . '">';
        
        // 首页
        if ($current > 1) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl(1)) . '" ';
            $html .= 'class="' . $sizeClass . ' bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-md" ';
            $html .= 'title="' . $this->getText('first') . '">';
            $html .= '<span class="sr-only">' . $this->getText('first') . '</span>';
            $html .= '<svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M8.445 14.832A1 1 0 0010 14v-2.798l5.445 3.63A1 1 0 0017 14V6a1 1 0 00-1.555-.832L10 8.798V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z"/>';
            $html .= '</svg>';
            $html .= '<span class="hidden sm:inline ml-1">' . $this->getText('first') . '</span>';
            $html .= '</a>';
        }
        
        // 上一页
        if ($info['has_prev']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($current - 1)) . '" ';
            $html .= 'class="' . $sizeClass . ' bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-md">';
            $html .= '<span class="sr-only">' . $this->getText('previous') . '</span>';
            $html .= '<svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/>';
            $html .= '</svg>';
            $html .= '<span class="hidden sm:inline ml-1">' . $this->getText('previous') . '</span>';
            $html .= '</a>';
        }
        
        // 页码
        [$start, $end] = $this->getPageRange();
        
        // 左省略号
        if ($start > 1) {
            if ($start > 2) {
                $html .= '<span class="' . $sizeClass . ' bg-white border border-gray-300 text-gray-500 rounded-md">...</span>';
            }
        }
        
        // 中间页码
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $current) {
                $html .= '<span class="' . $sizeClass . ' bg-blue-600 border border-blue-600 text-white rounded-md">' . $i . '</span>';
            } else {
                $html .= '<a href="' . htmlspecialchars($this->buildUrl($i)) . '" ';
                $html .= 'class="' . $sizeClass . ' bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-md">' . $i . '</a>';
            }
        }
        
        // 右省略号
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $html .= '<span class="' . $sizeClass . ' bg-white border border-gray-300 text-gray-500 rounded-md">...</span>';
            }
        }
        
        // 下一页
        if ($info['has_next']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($current + 1)) . '" ';
            $html .= 'class="' . $sizeClass . ' bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-md">';
            $html .= '<span class="hidden sm:inline mr-1">' . $this->getText('next') . '</span>';
            $html .= '<span class="sr-only">' . $this->getText('next') . '</span>';
            $html .= '<svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>';
            $html .= '</svg>';
            $html .= '</a>';
        }
        
        // 尾页
        if ($current < $totalPages) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($totalPages)) . '" ';
            $html .= 'class="' . $sizeClass . ' bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-700 rounded-md" ';
            $html .= 'title="' . $this->getText('last') . '">';
            $html .= '<span class="hidden sm:inline mr-1">' . $this->getText('last') . '</span>';
            $html .= '<span class="sr-only">' . $this->getText('last') . '</span>';
            $html .= '<svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M11.555 5.168A1 1 0 0010 6v2.798L4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832L10 11.202V14a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4z"/>';
            $html .= '</svg>';
            $html .= '</a>';
        }
        
        $html .= '</nav>';
        
        if ($this->mobileOptimized) {
            $html .= '</div>';
        }
        
        // 跳转功能
        if ($this->showJumper && $totalPages > 1) {
            $html .= $this->renderJumperTailwind($totalPages);
        }
        
        // 统计信息
        if ($this->showStats) {
            $html .= $this->renderStatsTailwind($info);
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 移动端 Tailwind 版本
     */
    protected function renderMobileTailwind($info)
    {
        $html = '<div class="flex justify-between items-center">';
        
        // 上一页
        if ($info['has_prev']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($info['current'] - 1)) . '" ';
            $html .= 'class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-md">';
            $html .= '<svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/>';
            $html .= '</svg>';
            $html .= $this->getText('previous');
            $html .= '</a>';
        } else {
            $html .= '<span class="px-3 py-2 text-sm bg-gray-100 border border-gray-300 text-gray-400 rounded-md">';
            $html .= '<svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/>';
            $html .= '</svg>';
            $html .= $this->getText('previous');
            $html .= '</span>';
        }
        
        // 页码信息
        $html .= '<span class="text-sm text-gray-500">';
        $html .= $info['current'] . ' / ' . $info['total_pages'];
        $html .= '</span>';
        
        // 下一页
        if ($info['has_next']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($info['current'] + 1)) . '" ';
            $html .= 'class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-md">';
            $html .= $this->getText('next');
            $html .= '<svg class="w-4 h-4 inline ml-1" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>';
            $html .= '</svg>';
            $html .= '</a>';
        } else {
            $html .= '<span class="px-3 py-2 text-sm bg-gray-100 border border-gray-300 text-gray-400 rounded-md">';
            $html .= $this->getText('next');
            $html .= '<svg class="w-4 h-4 inline ml-1" fill="currentColor" viewBox="0 0 20 20">';
            $html .= '<path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>';
            $html .= '</svg>';
            $html .= '</span>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Tailwind 跳转功能
     */
    protected function renderJumperTailwind($totalPages)
    {
        $html = '<div class="pagination-jumper mt-3 flex justify-center items-center space-x-2">';
        $html .= '<span class="text-sm text-gray-500">' . $this->getText('goto') . '</span>';
        $html .= '<input type="number" class="pagination-jump-input w-16 px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" ';
        $html .= 'min="1" max="' . $totalPages . '" value="' . $this->current . '">';
        $html .= '<span class="text-sm text-gray-500">' . $this->getText('page') . '</span>';
        $html .= '<button type="button" class="pagination-jump-btn px-3 py-1 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-md">' . $this->getText('go') . '</button>';
        $html .= '</div>';
        
        $html .= $this->renderJumpScript();
        
        return $html;
    }
    
    /**
     * Tailwind 统计信息
     */
    protected function renderStatsTailwind($info)
    {
        $html = '<div class="pagination-stats text-center mt-3">';
        $html .= '<p class="text-sm text-gray-500">';
        
        if ($this->total > 0) {
            $html .= $this->getText('showing', [
                'from' => $info['from'],
                'to' => $info['to']
            ]);
            $html .= ' ' . $this->getText('of') . ' ';
            $html .= $this->getText('total_records', ['total' => $info['total']]);
            $html .= '，' . $this->getText('total_pages', ['pages' => $info['total_pages']]);
        } else {
            $html .= $this->getText('total_records', ['total' => 0]);
        }
        
        $html .= '</p>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 自定义CSS版本
     */
    protected function renderCustom()
    {
        $info = $this->getInfo();
        $totalPages = $info['total_pages'];
        $current = $info['current'];
        
        if ($totalPages <= 1 && empty($this->query)) {
            return '';
        }
        
        $sizeClass = $this->size ? "pagination-{$this->size}" : '';
        $alignClass = "pagination-{$this->alignment}";
        
        $html = '<div class="pagination-wrapper ' . $sizeClass . ' ' . $alignClass . '">';
        
        // 添加自定义CSS
        $html .= $this->renderCustomCSS();
        
        // 移动端优化
        if ($this->mobileOptimized) {
            $html .= '<div class="pagination-mobile">';
            $html .= $this->renderMobileCustom($info);
            $html .= '</div>';
            $html .= '<div class="pagination-desktop">';
        }
        
        $html .= '<nav class="pagination-nav" aria-label="' . $this->getText('page') . '">';
        $html .= '<ul class="pagination-list">';
        
        // 首页
        if ($current > 1) {
            $html .= '<li class="pagination-item">';
            $html .= '<a href="' . htmlspecialchars($this->buildUrl(1)) . '" class="pagination-link pagination-first" title="' . $this->getText('first') . '">';
            $html .= '<span class="pagination-icon">«</span><span class="pagination-text">' . $this->getText('first') . '</span>';
            $html .= '</a></li>';
        }
        
        // 上一页
        if ($info['has_prev']) {
            $html .= '<li class="pagination-item">';
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($current - 1)) . '" class="pagination-link pagination-prev">';
            $html .= '<span class="pagination-icon">‹</span><span class="pagination-text">' . $this->getText('previous') . '</span>';
            $html .= '</a></li>';
        }
        
        // 页码
        [$start, $end] = $this->getPageRange();
        
        // 左省略号
        if ($start > 1) {
            if ($start > 2) {
                $html .= '<li class="pagination-item pagination-disabled"><span class="pagination-link">...</span></li>';
            }
        }
        
        // 中间页码
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $current) {
                $html .= '<li class="pagination-item pagination-active"><span class="pagination-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="pagination-item"><a href="' . htmlspecialchars($this->buildUrl($i)) . '" class="pagination-link">' . $i . '</a></li>';
            }
        }
        
        // 右省略号
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $html .= '<li class="pagination-item pagination-disabled"><span class="pagination-link">...</span></li>';
            }
        }
        
        // 下一页
        if ($info['has_next']) {
            $html .= '<li class="pagination-item">';
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($current + 1)) . '" class="pagination-link pagination-next">';
            $html .= '<span class="pagination-text">' . $this->getText('next') . '</span><span class="pagination-icon">›</span>';
            $html .= '</a></li>';
        }
        
        // 尾页
        if ($current < $totalPages) {
            $html .= '<li class="pagination-item">';
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($totalPages)) . '" class="pagination-link pagination-last" title="' . $this->getText('last') . '">';
            $html .= '<span class="pagination-text">' . $this->getText('last') . '</span><span class="pagination-icon">»</span>';
            $html .= '</a></li>';
        }
        
        $html .= '</ul></nav>';
        
        if ($this->mobileOptimized) {
            $html .= '</div>';
        }
        
        // 跳转功能
        if ($this->showJumper && $totalPages > 1) {
            $html .= $this->renderJumperCustom($totalPages);
        }
        
        // 统计信息
        if ($this->showStats) {
            $html .= $this->renderStatsCustom($info);
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 移动端自定义版本
     */
    protected function renderMobileCustom($info)
    {
        $html = '<div class="pagination-mobile-nav">';
        
        // 上一页
        if ($info['has_prev']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($info['current'] - 1)) . '" class="pagination-mobile-btn pagination-mobile-prev">';
            $html .= '<span class="pagination-icon">‹</span> ' . $this->getText('previous');
            $html .= '</a>';
        } else {
            $html .= '<span class="pagination-mobile-btn pagination-mobile-disabled">';
            $html .= '<span class="pagination-icon">‹</span> ' . $this->getText('previous');
            $html .= '</span>';
        }
        
        // 页码信息
        $html .= '<span class="pagination-mobile-info">';
        $html .= $info['current'] . ' / ' . $info['total_pages'];
        $html .= '</span>';
        
        // 下一页
        if ($info['has_next']) {
            $html .= '<a href="' . htmlspecialchars($this->buildUrl($info['current'] + 1)) . '" class="pagination-mobile-btn pagination-mobile-next">';
            $html .= $this->getText('next') . ' <span class="pagination-icon">›</span>';
            $html .= '</a>';
        } else {
            $html .= '<span class="pagination-mobile-btn pagination-mobile-disabled">';
            $html .= $this->getText('next') . ' <span class="pagination-icon">›</span>';
            $html .= '</span>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 自定义跳转功能
     */
    protected function renderJumperCustom($totalPages)
    {
        $html = '<div class="pagination-jumper">';
        $html .= '<span class="pagination-jumper-label">' . $this->getText('goto') . '</span>';
        $html .= '<input type="number" class="pagination-jump-input" min="1" max="' . $totalPages . '" value="' . $this->current . '">';
        $html .= '<span class="pagination-jumper-label">' . $this->getText('page') . '</span>';
        $html .= '<button type="button" class="pagination-jump-btn">' . $this->getText('go') . '</button>';
        $html .= '</div>';
        
        $html .= $this->renderJumpScript();
        
        return $html;
    }
    
    /**
     * 自定义统计信息
     */
    protected function renderStatsCustom($info)
    {
        $html = '<div class="pagination-stats">';
        
        if ($this->total > 0) {
            $html .= $this->getText('showing', [
                'from' => $info['from'],
                'to' => $info['to']
            ]);
            $html .= ' ' . $this->getText('of') . ' ';
            $html .= $this->getText('total_records', ['total' => $info['total']]);
            $html .= '，' . $this->getText('total_pages', ['pages' => $info['total_pages']]);
        } else {
            $html .= $this->getText('total_records', ['total' => 0]);
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * 自定义CSS样式
     */
    protected function renderCustomCSS()
    {
        return '<style>
        .pagination-wrapper {
            margin: 20px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        .pagination-center {
            text-align: center;
        }
        
        .pagination-start {
            text-align: left;
        }
        
        .pagination-end {
            text-align: right;
        }
        
        .pagination-nav {
            display: inline-block;
        }
        
        .pagination-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 4px;
        }
        
        .pagination-item {
            display: flex;
        }
        
        .pagination-link {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            text-decoration: none;
            color: #374151;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 14px;
            line-height: 1.4;
            min-width: 40px;
            justify-content: center;
        }
        
        .pagination-link:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #111827;
        }
        
        .pagination-active .pagination-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }
        
        .pagination-disabled .pagination-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        
        .pagination-text {
            margin: 0 4px;
        }
        
        .pagination-icon {
            font-weight: bold;
        }
        
        /* 大小变体 */
        .pagination-sm .pagination-link {
            padding: 4px 8px;
            font-size: 12px;
            min-width: 32px;
        }
        
        .pagination-lg .pagination-link {
            padding: 12px 16px;
            font-size: 16px;
            min-width: 48px;
        }
        
        /* 移动端样式 */
        .pagination-mobile {
            display: block;
        }
        
        .pagination-desktop {
            display: none;
        }
        
        .pagination-mobile-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        
        .pagination-mobile-btn {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .pagination-mobile-btn:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }
        
        .pagination-mobile-disabled {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        
        .pagination-mobile-info {
            font-size: 14px;
            color: #6b7280;
            padding: 0 10px;
        }
        
        /* 跳转功能 */
        .pagination-jumper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 12px;
        }
        
        .pagination-jumper-label {
            font-size: 14px;
            color: #6b7280;
        }
        
        .pagination-jump-input {
            width: 60px;
            padding: 4px 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
        }
        
        .pagination-jump-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        
        .pagination-jump-btn {
            padding: 4px 12px;
            background-color: #3b82f6;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .pagination-jump-btn:hover {
            background-color: #2563eb;
        }
        
        /* 统计信息 */
        .pagination-stats {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
            color: #6b7280;
        }
        
        /* 响应式 */
        @media (min-width: 768px) {
            .pagination-mobile {
                display: none;
            }
            
            .pagination-desktop {
                display: block;
            }
            
            .pagination-text {
                display: inline;
            }
        }
        
        @media (max-width: 767px) {
            .pagination-text {
                display: none;
            }
            
            .pagination-link {
                padding: 8px;
                min-width: 36px;
            }
            
            .pagination-jumper {
                flex-wrap: wrap;
            }
        }
        </style>';
    }
    
    /**
     * 跳转功能JavaScript
     */
    protected function renderJumpScript()
    {
        $currentQuery = $this->query;
        $baseUrl = '?';
        if (!empty($currentQuery)) {
            unset($currentQuery['page']);
            $baseUrl = '?' . http_build_query($currentQuery) . '&';
        }
        
        return '<script>
        (function() {
            const jumpBtn = document.querySelector(".pagination-jump-btn");
            const jumpInput = document.querySelector(".pagination-jump-input");
            
            if (jumpBtn && jumpInput) {
                jumpBtn.addEventListener("click", function() {
                    const page = parseInt(jumpInput.value);
                    const maxPage = parseInt(jumpInput.getAttribute("max"));
                    
                    if (page >= 1 && page <= maxPage) {
                        if (page === 1) {
                            window.location.href = "' . htmlspecialchars(rtrim($baseUrl, '&')) . '";
                        } else {
                            window.location.href = "' . htmlspecialchars($baseUrl) . 'page=" + page;
                        }
                    } else {
                        alert("' . $this->getText('page') . ' 1-" + maxPage);
                    }
                });
                
                jumpInput.addEventListener("keypress", function(e) {
                    if (e.key === "Enter") {
                        jumpBtn.click();
                    }
                });
            }
        })();
        </script>';
    }
    
    /**
     * 渲染分页HTML
     */
    public function render()
    {
        switch ($this->framework) {
            case 'tailwind':
                return $this->renderTailwind();
            case 'custom':
                return $this->renderCustom();
            case 'bootstrap':
            default:
                return $this->renderBootstrap();
        }
    }
    
    /**
     * 返回JSON格式分页数据（用于API）
     */
    public function toArray()
    {
        return $this->getInfo();
    }
    
    /**
     * 转为JSON字符串（用于API）
     */
    public function toJson()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 添加自定义语言包
     */
    public function addLanguage($lang, $texts)
    {
        $this->i18n[$lang] = array_merge($this->i18n['zh-cn'], $texts);
        return $this;
    }
    
    /**
     * 设置语言
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }
    
    /**
     * 快速创建分页实例的静态方法
     */
    public static function create($total, $current = 1, $pageSize = 20, $query = [], $options = [])
    {
        return new self($total, $current, $pageSize, $query, $options);
    }
}