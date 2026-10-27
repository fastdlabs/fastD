# Tools

## check-helper-usage.php

检查 `src/` 中是否新增了对 `helpers.php` 全局 helper 的调用。

```bash
composer check:helpers
```

- 基线文件：`tools/helper-baseline.json`
- 当前 fastD `src/` 基线为 **0**
- 如确有需要新增调用，必须显式执行以下命令并审查 diff：

```bash
php tools/check-helper-usage.php --update
```

CI 会在计数超过基线时失败。
