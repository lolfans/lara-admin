
## 安装步骤
### 此版本是基于laravel 6.0 LTS 从github-muzilong那里fork过来备用，感觉挺赞的，保留以免不时之需
### 该开源项目已经经过我本人的实测和优化代码分层以及细小BUG的修复，完全可以用于普通的后台管理系统的基础后台
### 原项目在这里 https://github.com/github-muzilong/laravel55-layuiadmin
- 复制.env.example为.env
- 配置.env里的数据库连接信息
- composer install
- php artisan migrate
- php artisan db:seed
- php artisan key:generate
- 登录后台：host/admin   帐号：root  密码：123456
