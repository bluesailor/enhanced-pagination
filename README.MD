# EnhancedPagination - 增强版分页类

一个功能强大、易于使用的PHP分页类，支持多种CSS框架、国际化、移动端优化和跳转功能。

## ✨ 特性

- 🌍 **多语言支持** - 内置中文、英文、日文，支持自定义语言包
- 📱 **移动端优化** - 响应式设计，自动适配移动端和桌面端
- 🎨 **多框架支持** - Bootstrap 5.3、Tailwind CSS 4.1、自定义CSS三种版本
- 🎯 **页码跳转** - 支持直接输入页码快速跳转
- 📊 **完整统计** - 显示详细的分页统计信息
- 🔗 **参数保持** - 自动保持URL中的搜索和筛选参数
- 🚀 **API友好** - 支持JSON格式的分页数据输出
- ♿ **无障碍** - 符合Web无障碍标准

## 📦 安装

直接下载 `EnhancedPagination.php` 文件到项目中即可使用。

```php
require_once 'EnhancedPagination.php';
```

## 🚀 快速开始

### 基础使用

```php
<?php
require_once 'EnhancedPagination.php';

// 基本参数
$total = 1250;        // 总记录数
$current = $_GET['page'] ?? 1;  // 当前页码
$pageSize = 20;       // 每页显示数量

// 创建分页实例
$pagination = new EnhancedPagination($total, $current, $pageSize);

// 输出分页HTML
echo $pagination->render();

// 获取分页信息用于数据库查询
$info = $pagination->getInfo();
$sql = "SELECT * FROM users LIMIT {$info['start']}, {$info['limit']}";
?>
```

### 高级配置

```php
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'framework' => 'bootstrap',    // CSS框架: bootstrap|tailwind|custom
    'language' => 'zh-cn',         // 语言: zh-cn|en|ja
    'size' => 'lg',                // 尺寸: sm|''|lg
    'alignment' => 'center',       // 对齐: start|center|end
    'range' => 3,                  // 显示页码范围
    'showStats' => true,           // 显示统计信息
    'showJumper' => true,          // 显示跳转功能
    'mobileOptimized' => true      // 移动端优化
]);
```

## 📚 详细用法

### 1. 构造函数参数

```php
new EnhancedPagination($total, $current, $pageSize, $query, $options)
```

| 参数 | 类型 | 必需 | 说明 |
|------|------|------|------|
| `$total` | int | ✅ | 总记录数 |
| `$current` | int | ❌ | 当前页码，默认 1 |
| `$pageSize` | int | ❌ | 每页显示数量，默认 20 |
| `$query` | array | ❌ | URL参数数组，默认 [] |
| `$options` | array | ❌ | 配置选项，默认 [] |

### 2. 配置选项

| 选项 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `framework` | string | `'bootstrap'` | CSS框架：`bootstrap`、`tailwind`、`custom` |
| `language` | string | `'zh-cn'` | 语言：`zh-cn`、`en`、`ja` 或自定义 |
| `size` | string | `''` | 尺寸：`sm`、`''`(默认)、`lg` |
| `alignment` | string | `'center'` | 对齐方式：`start`、`center`、`end` |
| `range` | int | `3` | 当前页前后显示的页码数量 |
| `showStats` | bool | `true` | 是否显示统计信息 |
| `showJumper` | bool | `true` | 是否显示跳转功能 |
| `mobileOptimized` | bool | `true` | 是否启用移动端优化 |

### 3. 主要方法

#### render()
渲染分页HTML
```php
echo $pagination->render();
```

#### getInfo()
获取分页信息数组
```php
$info = $pagination->getInfo();
/*
返回数组包含：
- total: 总记录数
- total_pages: 总页数
- current: 当前页码
- page_size: 每页显示数量
- has_prev: 是否有上一页
- has_next: 是否有下一页
- start: SQL LIMIT 起始值
- limit: SQL LIMIT 数量
- from: 当前页起始记录号
- to: 当前页结束记录号
- prev_page: 上一页页码
- next_page: 下一页页码
*/
```

#### toArray() / toJson()
输出API格式数据
```php
// 返回数组
$data = $pagination->toArray();

// 返回JSON字符串
$json = $pagination->toJson();
```

## 🎨 CSS框架支持

### Bootstrap 5.3

```php
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'framework' => 'bootstrap'
]);
```

需要引入Bootstrap CSS和图标：
```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
```

### Tailwind CSS 4.1

```php
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'framework' => 'tailwind'
]);
```

需要引入Tailwind CSS：
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### 自定义CSS

```php
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'framework' => 'custom'
]);
```

不需要引入任何外部CSS，所有样式都内置在组件中。

## 🌍 国际化

### 内置语言

- **中文** (`zh-cn`)
- **英文** (`en`)
- **日文** (`ja`)

```php
// 使用英文
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'language' => 'en'
]);

// 使用日文
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'language' => 'ja'
]);
```

### 添加自定义语言

```php
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'language' => 'fr'
]);

// 添加法语语言包
$pagination->addLanguage('fr', [
    'previous' => 'Précédent',
    'next' => 'Suivant',
    'first' => 'Premier',
    'last' => 'Dernier',
    'goto' => 'Aller à',
    'page' => 'Page',
    'go' => 'Aller',
    'total_records' => 'Total :total enregistrements',
    'total_pages' => ':pages pages',
    'showing' => 'Affichage de :from à :to sur',
    'of' => 'de',
    'results' => 'résultats'
]);
```

## 📱 移动端优化

启用移动端优化后，分页会自动适配：

- **桌面端**：显示完整的分页导航
- **移动端**：显示简化版分页（上一页/下一页 + 页码信息）

```php
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'mobileOptimized' => true  // 默认启用
]);
```

## 🔍 实际应用示例

### 1. 基础列表分页

```php
<?php
require_once 'EnhancedPagination.php';

// 获取总记录数
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total = $stmt->fetchColumn();

// 当前页码
$current = $_GET['page'] ?? 1;
$pageSize = 20;

// 创建分页
$pagination = new EnhancedPagination($total, $current, $pageSize);

// 获取分页信息
$info = $pagination->getInfo();

// 查询当前页数据
$stmt = $pdo->prepare("SELECT * FROM users LIMIT ?, ?");
$stmt->execute([$info['start'], $info['limit']]);
$users = $stmt->fetchAll();

// 显示数据
foreach ($users as $user) {
    echo "<div>{$user['name']}</div>";
}

// 显示分页
echo $pagination->render();
?>
```

### 2. 搜索结果分页

```php
<?php
require_once 'EnhancedPagination.php';

// 搜索参数
$keyword = $_GET['keyword'] ?? '';
$status = $_GET['status'] ?? '';

// 构建查询条件
$where = "WHERE 1=1";
$params = [];

if ($keyword) {
    $where .= " AND name LIKE ?";
    $params[] = "%{$keyword}%";
}

if ($status) {
    $where .= " AND status = ?";
    $params[] = $status;
}

// 获取总记录数
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users {$where}");
$stmt->execute($params);
$total = $stmt->fetchColumn();

// 创建分页（保持搜索参数）
$pagination = new EnhancedPagination($total, $_GET['page'] ?? 1, 20, $_GET);

// 获取分页信息
$info = $pagination->getInfo();

// 查询当前页数据
$stmt = $pdo->prepare("SELECT * FROM users {$where} LIMIT ?, ?");
$stmt->execute(array_merge($params, [$info['start'], $info['limit']]));
$users = $stmt->fetchAll();

// 显示分页
echo $pagination->render();
?>
```

### 3. API接口返回

```php
<?php
require_once 'EnhancedPagination.php';

header('Content-Type: application/json');

$total = 1250;
$current = $_GET['page'] ?? 1;
$pageSize = 20;

$pagination = new EnhancedPagination($total, $current, $pageSize);
$info = $pagination->getInfo();

// 模拟数据
$data = [];
for ($i = $info['from']; $i <= $info['to']; $i++) {
    $data[] = ['id' => $i, 'name' => "用户{$i}"];
}

// 返回JSON
echo json_encode([
    'code' => 200,
    'message' => 'success',
    'data' => $data,
    'pagination' => $pagination->toArray()
], JSON_UNESCAPED_UNICODE);
?>
```

### 4. 静态方法创建

```php
// 使用静态方法快速创建
$pagination = EnhancedPagination::create($total, $current, $pageSize, $_GET, [
    'framework' => 'tailwind',
    'language' => 'en',
    'size' => 'lg'
]);

echo $pagination->render();
```

## 🎯 样式定制

### Bootstrap 主题定制

```css
/* 自定义Bootstrap分页颜色 */
.pagination .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
```

### Tailwind 样式覆盖

```css
/* 自定义Tailwind分页样式 */
.pagination-wrapper .bg-blue-600 {
    @apply bg-purple-600;
}

.pagination-wrapper .border-blue-600 {
    @apply border-purple-600;
}
```

### 自定义CSS修改

由于自定义CSS版本的样式都内联在组件中，可以通过CSS覆盖来修改：

```css
/* 覆盖自定义样式 */
.pagination-link {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
}

.pagination-active .pagination-link {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
}
```

## 🔧 高级功能

### 动态切换语言

```php
$pagination = new EnhancedPagination($total, $current, $pageSize);

// 根据用户选择切换语言
$userLang = $_SESSION['language'] ?? 'zh-cn';
$pagination->setLanguage($userLang);

echo $pagination->render();
```

### 条件显示组件

```php
$options = [
    'showStats' => $total > 0,           // 有数据时才显示统计
    'showJumper' => $totalPages > 10,    // 页数较多时才显示跳转
];

$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, $options);
```

### 自定义页码范围

```php
// 当前页前后各显示5页
$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'range' => 5
]);

// 小屏幕时减少显示范围
$range = (isset($_SERVER['HTTP_USER_AGENT']) && 
          preg_match('/Mobile|Android|iPhone/', $_SERVER['HTTP_USER_AGENT'])) ? 2 : 5;

$pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
    'range' => $range
]);
```

## 🐛 问题排查

### 常见问题

1. **分页不显示**
   - 检查总记录数是否大于0
   - 确认页面大小设置是否正确

2. **样式不正确**
   - 确认已引入对应的CSS框架
   - 检查框架版本是否匹配

3. **跳转功能不工作**
   - 确认页面已加载完成
   - 检查浏览器控制台是否有JavaScript错误

4. **移动端显示异常**
   - 确认页面已设置viewport meta标签
   - 检查CSS媒体查询是否生效

### 调试技巧

```php
// 输出分页信息进行调试
$info = $pagination->getInfo();
var_dump($info);

// 检查URL构建
echo $pagination->buildUrl(1);

// 验证配置参数
print_r($pagination);
```

## 📄 许可证

MIT License - 可自由使用、修改和分发。

## 🤝 贡献

欢迎提交Issue和Pull Request来改进这个分页类。

## 📞 支持

如有问题或建议，请在GitHub上创建Issue或联系维护者。

---

**EnhancedPagination** - 让分页变得简单而强大！

项目地址：https://github.com/bluesailor/enhanced-pagination