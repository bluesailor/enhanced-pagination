<?php
/**
 * Enhanced Pagination 一键安装脚本
 * 检查环境、初始化数据库、创建示例文件
 */

echo "=== Enhanced Pagination 安装脚本 ===\n\n";

// 1. 环境检查
echo "🔍 检查运行环境...\n";

// 检查PHP版本
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die("❌ PHP版本过低，需要 7.4.0 或更高版本，当前版本: " . PHP_VERSION . "\n");
}
echo "✅ PHP版本: " . PHP_VERSION . "\n";

// 检查PDO扩展
if (!extension_loaded('pdo')) {
    die("❌ PDO扩展未安装\n");
}
echo "✅ PDO扩展已安装\n";

// 检查SQLite扩展
if (!extension_loaded('pdo_sqlite')) {
    die("❌ PDO SQLite扩展未安装\n");
}
echo "✅ PDO SQLite扩展已安装\n";

// 检查目录权限
if (!is_writable(__DIR__)) {
    die("❌ 当前目录没有写入权限，请执行: chmod 755 " . __DIR__ . "\n");
}
echo "✅ 目录权限正常\n";

echo "\n";

// 2. 文件检查
echo "📁 检查必需文件...\n";

$requiredFiles = [
    'EnhancedPagination.php' => '分页核心类',
    'data.php' => '数据源文件',
    'index.php' => '演示主页',
    'api.php' => 'API接口'
];

$missingFiles = [];
foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}: {$file}\n";
    } else {
        echo "❌ {$description}: {$file} (缺失)\n";
        $missingFiles[] = $file;
    }
}

if (!empty($missingFiles)) {
    echo "\n⚠️  以下文件缺失，请确保所有项目文件都已下载:\n";
    foreach ($missingFiles as $file) {
        echo "   - {$file}\n";
    }
    die("\n请下载完整项目文件后重新运行安装脚本。\n");
}

echo "\n";

// 3. 初始化数据源
echo "💾 初始化数据源...\n";

try {
    require_once 'data.php';
    
    // 测试数据生成
    $users = getAllUsers();
    $stats = getStatistics();
    
    echo "✅ 数据源初始化成功，共 {$stats['total']} 条用户记录\n";
    
    // 显示统计信息
    foreach ($stats['by_status'] as $status => $count) {
        $statusText = $status === 'active' ? '活跃' : '非活跃';
        echo "   - {$statusText}: {$count} 人\n";
    }
    
} catch (Exception $e) {
    die("❌ 数据源初始化失败: " . $e->getMessage() . "\n");
}

echo "\n";

// 4. 创建配置文件（可选）
echo "⚙️  创建配置文件...\n";

$configContent = "<?php
/**
 * Enhanced Pagination 配置文件
 */

// 默认配置
return [
    'default_page_size' => 20,
    'max_page_size' => 100,
    'default_framework' => 'bootstrap',
    'default_language' => 'zh-cn',
    'cache_enabled' => false,
    'cache_duration' => 300, // 5分钟
    
    // 数据源配置
    'data_source' => [
        'type' => 'json_array',
        'total_records' => 200
    ],
    
    // 支持的框架
    'frameworks' => ['bootstrap', 'tailwind', 'custom'],
    
    // 支持的语言
    'languages' => ['zh-cn', 'en', 'ja']
];
";

if (!file_exists('config.php')) {
    file_put_contents('config.php', $configContent);
    echo "✅ 配置文件已创建: config.php\n";
} else {
    echo "ℹ️  配置文件已存在: config.php\n";
}

// 5. 创建.htaccess文件（Apache用户）
if (!file_exists('.htaccess')) {
    $htaccessContent = "# Enhanced Pagination .htaccess
RewriteEngine On

# 隐藏.php扩展名
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^\.]+)$ $1.php [NC,L]

# 安全设置
<Files \"data.php\">
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{QUERY_STRING} !^action=
        RewriteRule .* - [F,L]
    </IfModule>
</Files>

<Files \"config.php\">
    Deny from all
</Files>

# 缓存设置
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css \"access plus 1 month\"
    ExpiresByType application/javascript \"access plus 1 month\"
    ExpiresByType image/png \"access plus 1 month\"
    ExpiresByType image/jpg \"access plus 1 month\"
    ExpiresByType image/jpeg \"access plus 1 month\"
</IfModule>
";
    
    file_put_contents('.htaccess', $htaccessContent);
    echo "✅ Apache配置文件已创建: .htaccess\n";
}

echo "\n";

// 6. 生成启动脚本
echo "🚀 创建启动脚本...\n";

$startScript = "#!/bin/bash
# Enhanced Pagination 启动脚本

echo \"启动 Enhanced Pagination 演示服务器...\"
echo \"\"
echo \"请在浏览器中访问: http://localhost:8000\"
echo \"\"
echo \"可用页面:\"
echo \"  - 主演示: http://localhost:8000/\"
echo \"  - API演示: http://localhost:8000/api\"
echo \"  - 文档: http://localhost:8000/README.md\"
echo \"\"
echo \"按 Ctrl+C 停止服务器\"
echo \"\"

php -S localhost:8000
";

file_put_contents('start.sh', $startScript);
chmod('start.sh', 0755);
echo "✅ 启动脚本已创建: start.sh\n";

// Windows启动脚本
$startBat = "@echo off
echo 启动 Enhanced Pagination 演示服务器...
echo.
echo 请在浏览器中访问: http://localhost:8000
echo.
echo 可用页面:
echo   - 主演示: http://localhost:8000/
echo   - API演示: http://localhost:8000/api
echo   - 文档: http://localhost:8000/README.md
echo.
echo 按 Ctrl+C 停止服务器
echo.

php -S localhost:8000
pause
";

file_put_contents('start.bat', $startBat);
echo "✅ Windows启动脚本已创建: start.bat\n";

echo "\n";

// 7. 完成安装
echo "🎉 安装完成!\n\n";

echo "📋 快速开始:\n";
echo "   Linux/Mac: ./start.sh\n";
echo "   Windows:   start.bat\n";
echo "   手动启动:  php -S localhost:8000\n\n";

echo "🌐 访问地址:\n";
echo "   主演示页面: http://localhost:8000/\n";
echo "   API接口:    http://localhost:8000/api\n";
echo "   项目文档:   README.md\n\n";

echo "📚 使用说明:\n";
echo "   - 支持三种CSS框架: Bootstrap、Tailwind、自定义CSS\n";
echo "   - 支持多种语言: 中文、英文、日文\n";
echo "   - 内置200条JSON测试数据，支持搜索和筛选\n";
echo "   - 完整的API接口演示\n";
echo "   - 响应式设计，支持移动端\n";
echo "   - 无需数据库，纯PHP实现\n\n";

echo "🔧 自定义:\n";
echo "   - 修改 config.php 调整默认配置\n";
echo "   - 查看 README.md 了解详细用法\n";
echo "   - 参考 examples/ 目录中的示例代码\n\n";

echo "❓ 问题反馈:\n";
echo "   GitHub: https://github.com/bluesailor/enhanced-pagination\n\n";

// 询问是否立即启动服务器
if (php_sapi_name() === 'cli') {
    echo "是否现在启动演示服务器? (y/N): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    
    if (trim(strtolower($line)) === 'y') {
        echo "\n启动服务器...\n";
        echo "请在浏览器中访问: http://localhost:8000\n";
        echo "按 Ctrl+C 停止服务器\n\n";
        
        // 启动内置服务器
        passthru('php -S localhost:8000');
    }
}

echo "感谢使用 Enhanced Pagination! 🙏\n";
?>