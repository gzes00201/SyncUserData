#設置
資料庫建置 需 utf8mb4_unicode_ci  

#指令
同步使用者資料指令為
 php artisan  syncUser:fromUrl  --url http://jsonplaceholder.typicode.com/users  
 
 #排程啟動(Linux)
 * * * * *  root php /path/to/artisan schedule:run >> /dev/null 2>&1      
