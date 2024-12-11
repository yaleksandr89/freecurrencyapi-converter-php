# 货币转换器 (FreecurrencyAPI)

### 选择语言

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../../../README.md) | [English](README_en.md) | [Español](README_es.md) | **已选** | [Français](README_fr.md) | [Deutsch](README_de.md) |

---

**Currency Converter** — 这是一个用于操作货币兑换 API 的项目。您可以请求汇率、执行货币转换并通过预设的测试检查功能。

## 项目结构

反映了 `CurrencyConverterBundle` 的结构，不包括 `Symfony` 框架的结构。

```plaintext
root_dir/
├── bundles/
│   ├── CurrencyConverterBundle/
│      ├── Action/                        # 处理用户操作的控制器
│      ├── Command/                       # 用于管理捆绑包功能的控制台命令
│      ├── DependencyInjection/           # 用于依赖和参数配置的逻辑
│      ├── DTO/                           # 用于结构化信息的数据传输对象
│      ├── Entity/                        # 数据库操作的实体
│      ├── Form/                          # 用于创建和处理表单的文件
│      ├── Migrations/                    # 修改数据库结构的迁移脚本
│      ├── Repository/                    # 数据访问和处理的存储库
│      ├── Resources/                     # 模板、本地化和其他资源
│      ├── Service/                       # 业务逻辑和辅助服务
│      ├── CurrencyConverterBundle.php    # 将捆绑包集成到项目中的主文件
```

---
