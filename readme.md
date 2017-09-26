#BUPTscs2.0 计院服务站
## 文档
### 环境
apache2或者nginx
mysql
composer
### 数据

- 用户数据在 `/database/seeds/csvs/user.csv` 中，格式为
`学号,姓名,大班,小班,密码`，每个用户一行。
- 用户职务数据在 `/database/seeds/csvs/title_user.csv` 中，格式为
`学号,职务id,职务名,等级ABC,原始分,时长`。
职务id见TitleTableSeeder.php，如果是学生会职务则留空，(班级职务则`等级`、`原始分`留空，时长为0.5代表半年，1代表1年。
- 职务数据在`/database/seeds/TitleTableSeeder.php`
- 文娱体育活动数据在`/database/seeds/ActivityTableSeeder.php`
- 打分项数据在`NavTableSeeder.php`

### 逻辑
- 页面显示逻辑在`/resources/views/mosco/index.blade.php`，以及其它几个blade文件。
- 计算分数、互评逻辑、表单提交的处理都在`/app/Http/Controllers/MosController.php`中。
- 网址路由在`app/Http/routes.php`中

### 安装

```sh
# git clone或者下载这个仓库
composer install #或者是php composer.phar install
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
cp .env.example .env #复制一份环境配置
vim .env #修改数据库用户名密码等
php artisan key:generate #生成. env中的key
# 修改public里面的应用相应的db_config.php或者config.php
# 迁移和填充数据库，数据在database/seeds相应文件里
php artisan migrate --seed
# 如果是回滚数据库（记录会被删除） php artisan migrate:refresh --seed
# 部署
php artisan serve #也可以不这样部署，配置nginx跳转到public目录即可
# 如果migrate出现问题，可以把数据库的migration的表清空并删除其它表
# 然后执行 composer dump-autoload
```

@flipped 2016年10月23日22:49:19

@fipped 2017/4/22

@filpped 2017/9/18
