
## 安装步骤
### 此版本是基于laravel 6.0 LTS 从github-muzilong那里fork过来备用
### 该项目已经经过我本人的实测与代码分层优化以及细小BUG的修复，完全可以用于普通的后台管理系统的基础后台
### 原项目在这里 https://github.com/github-muzilong/laravel55-layuiadmin
- git clone https://github.com/lolfans/lara-admin.git -b simple    这是最简洁的代码 
- 复制.env.example为.env
- 配置.env里的数据库连接信息
- composer install
- php artisan migrate
- php artisan db:seed
- php artisan key:generate
- 登录后台：host/admin   帐号：root  密码：123456
